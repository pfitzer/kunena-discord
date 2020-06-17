<?php
/**
 * @package    kunena-discord
 *
 * @author     michael <michael@mp-development.de>
 * @copyright  Michael Pfister
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://github.com/pfitzer/kunena-discord
 */

use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die;

/**
 * Kunena-discord plugin.
 *
 * @package   kunena-discord
 * @since     1.0.0
 */
class plgKunenaDiscord extends CMSPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	protected $webhooks;

    /**
     * plgSystemKunenaDiscord constructor.
     * @param $subject
     * @param array $config
     */
    public function __construct($subject, array $config = array())
    {
        parent::__construct($subject, $config);
        $this->setWebhooks();
        $this->domain = $this->params->get('domain');
    }

    /**
     * Get Kunena activity stream integration object.
     *
     * @return \KunenaDiscord|null
     * @since 1.0.0
     */
    public function onKunenaGetActivity()
    {
        require_once __DIR__ . "/push.php";
        return new KunenaDiscord($this->webhooks, $this->domain);
    }

    /**
     * set the configured webhooks
     *
     * @since 2.0.0
     */
    private function setWebhooks()
    {
        for ($i = 1; $i <= 10; $i++) {
            $hook = $this->params->get('webhook' . $i);
            if (!$hook) {
                continue;
            }
            $this->webhooks[] = [$hook, $this->params->get('webhookcats' . $i)];
        }
    }

}
