<?php
/**
 * @package     kunena-discord
 * @subpackage
 *
 * @author     michael <michael@mp-development.de>
 * @copyright   Michael Pfister
 * @license      GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

use Joomla\CMS\Http\Http;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;

/**
 * @package     ${NAMESPACE}
 *
 * @since version
 */
class KunenaDiscord extends KunenaActivity
{


    /**
     * @var null
     */
    private $webhook = null;

    /**
     * KunenaDiscord constructor.
     * @param $webhook
     */
    public function __construct($webhook)
    {
        $this->webhook = $webhook;
        $this->lang = JFactory::getLanguage();
        $this->lang->load('plg_kunena_discord', JPATH_ADMINISTRATOR);
    }

    /**
     * @param string $message
     *
     *
     * @since version
     */
    public function onAfterReply($message)
    {
        $this->_prepareAndSend(
            $message,
            Text::_("PLG_KUNENA_DISCORD_MESSAGE_NEW")
        );
    }

    /**
     * @param string $message
     *
     *
     * @since version
     */
    public function onAfterPost($message)
    {
        $this->_prepareAndSend(
            $message,
            Text::_("PLG_KUNENA_DISCORD_MESSAGE_NEW")
        );
    }

    /**
     * @param $message
     *
     * @return bool
     *
     * @since version
     */
    private function _checkPermissions($message)
    {
        $category = $message->getCategory();
        $accesstype = $category->accesstype;

        if ($accesstype != 'joomla.group' && $accesstype != 'joomla.level') {
            return false;
        }

        // FIXME: Joomla 2.5 can mix up groups and access levels
        if ($accesstype == 'joomla.level' && $category->access <= 2) {
            return true;
        } elseif ($category->pub_access == 1 || $category->pub_access == 2) {
            return true;
        } elseif ($category->admin_access == 1 || $category->admin_access == 2) {
            return true;
        }

        return false;
    }

    /**
     * @param $pushMessage
     * @param $url
     * @param $message
     *
     *
     * @throws Exception
     * @since version
     */
    private function _send_message($pushMessage, $url, $message)
    {
        $app = JFactory::getApplication();
        $content = '**' . $pushMessage . '** *' . $message->subject . '* [Link](' . $url . ')';
        $date = new Date('now');
        $hookObject = json_encode([
            /*
             * The general "message" shown above your embeds
             */
            "content" => $content,
            /*
             * The username shown in the message
             */
            "username" => $app->get('sitename'),
            /*
             * Whether or not to read the message in Text-to-speech
             */
            "tts" => false
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $request = new Http();
        $request->post($this->webhook, $hookObject);
    }

    /**
     * @param $message
     * @param $translatedMsg
     *
     *
     * @since version
     */
    private function _prepareAndSend($message, $translatedMsg)
    {
        if ($this->_checkPermissions($message)) {
            $pushMessage = sprintf($translatedMsg, $message->subject);
            $url = htmlspecialchars_decode(JUri::base() . mb_substr($message->getPermaUrl(), 1) . '#' . $message->id);
            $this->_send_message($pushMessage, $url, $message);
        }
    }
}
