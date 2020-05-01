<?php

defined('_JEXEC') or die('Restricted access');


JFormHelper::loadFieldClass('list');

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_kunena/models');

/**
 * Description of fishes
 *
 * @author michael
 */
class JFormFieldKCategory extends JFormFieldList
{
    //The field class must know its own type through the variable $type.
    protected $type = 'KCategory';

    public function getOptions()
    {
        $model = JModelLegacy::getInstance('ModelCategory', 'Kunena', array('ignore_request' => true));

        // $options = array_merge(parent::getOptions(), $fishes);
        // return $options;
    }
}
