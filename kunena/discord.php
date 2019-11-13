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
class plgKunenaDiscord extends CMSPlugin
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
        parent::__construct($subject, $config);
        $this->webhook = $this->params->get('webhook');
        if (!$this->webhook) {
            throw new InvalidArgumentException("Webhook can`t be null. Please donfigure a webhhok.");
        }
    }

    /**
     * Get Kunena activity stream integration object.
     *
     * @return \KunenaDiscord|null
     * @since Kunena
     */
    public function onKunenaGetActivity()
    {
        require_once __DIR__ . "/push.php";
        return new KunenaDiscord($this->webhook);
    }

}
