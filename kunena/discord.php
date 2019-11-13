<?php
/**
 * @package    kunena-discord
 *
 * @author     michael <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die;

/**
 * Kunena-discord plugin.
 *
 * @package   kunena-discord
 * @since     1.0.0
 */
class plgSystemKunenaDiscord extends CMSPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	protected $webhook;

    /**
     * plgSystemKunenaDiscord constructor.
     * @param $subject
     * @param array $config
     */
    public function __construct($subject, array $config = array())
    {
        $this->webhook = $this->params->get('webhook');
        if (!$this->webhook) {
            throw new InvalidArgumentException("Webhook can`t be null. Please donfigure a webhhok.");
        }

        parent::__construct($subject, $config);
    }

    /**
     * Get Kunena activity stream integration object.
     *
     * @return \KunenaPushalert|null
     * @since Kunena
     */
    public function onKunenaGetActivity()
    {
        require_once __DIR__ . "/push.php";
        return new KunenaPushalert($this->webhook);
    }

}
