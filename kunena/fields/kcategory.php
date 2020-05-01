<?php

defined('_JEXEC') or die('Restricted access');


JFormHelper::loadFieldClass('list');

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_kunena/models');

/**
 *
 * @author michael
 */
class JFormFieldKCategory extends JFormFieldList
{
    //The field class must know its own type through the variable $type.
    protected $type = 'KCategory';

    /**
     * @var array
     */
    private $options = array();

    /**
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getOptions()
    {
        if (empty($this->options)) {
            $model = JModelLegacy::getInstance('Category', 'KunenaModel', array('ignore_request' => true));
            $cats = $model->getCategories();
            foreach ($cats as $cat) {
                foreach ($cat as $obj) {
                    if ($obj->level === 0 || $obj->published === 0) {
                        continue;
                    }
                    $this->options[] = ["value" =>$obj->id, "text" => $obj->name];
                }
            }
            $this->options = array_merge(parent::getOptions(), $this->options);
        }
        return $this->options;
    }
}
