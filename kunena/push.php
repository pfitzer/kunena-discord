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
     * @var \Joomla\CMS\Language\Language
     */
    private $lang;

    /**
     * @var \Joomla\CMS\Application\CMSApplication
     */
    private $app;

    /**
     * KunenaDiscord constructor.
     * @param $webhook
     * @throws Exception
     */
    public function __construct($webhook)
    {
        $this->webhook = $webhook;
        $this->lang = JFactory::getLanguage();
        $this->lang->load('plg_kunena_discord', JPATH_ADMINISTRATOR);
        $this->app = JFactory::getApplication();
    }

    /**
     * @param KunenaForumMessage $message
     */
    public function onAfterReply($message)
    {
        $this->_prepareAndSend(
            $message,
            JText::_("PLG_KUNENA_DISCORD_MESSAGE_NEW")
        );
    }

    /**
     * @param KunenaForumMessage $message
     */
    public function onAfterPost($message)
    {
        $this->_prepareAndSend(
            $message,
            JText::_("PLG_KUNENA_DISCORD_MESSAGE_NEW")
        );
    }

    /**
     * @param KunenaForumMessage $message
     * @return bool
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
     * @param KunenaForumMessage $message
     */
    private function _send_message($pushMessage, $url, $message)
    {
        $content = '**' . $pushMessage . '** *' . $message->subject . '* [Link](' . $url . ')';
        $hookObject = json_encode([
            /*
             * The general "message" shown above your embeds
             */
            "content" => $content,
            /*
             * The username shown in the message
             */
            "username" => $this->app->get('sitename'),
            /*
             * Whether or not to read the message in Text-to-speech
             */
            "tts" => false
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $request = new Http();
        $response = $request->post($this->webhook, $hookObject);
        if ($response->code != 204) {
            $body = json_decode($response->body);
            $this->app->enqueueMessage(JText::_('PLG_KUNENA_DISCORD_ERROR') . ' ' . $body->message, 'Warning');
        }
    }

    /**
     * @param KunenaForumMessage $message
     * @param $translatedMsg
     */
    private function _prepareAndSend($message, $translatedMsg)
    {
        if ($this->_checkPermissions($message)) {
            $pushMessage = sprintf($translatedMsg, $message->subject);
            try {
                $url = htmlspecialchars_decode(JUri::base()
                    . mb_substr($message->getPermaUrl(), 1)
                    . '#' . $message->id);
                $this->_send_message($pushMessage, $url, $message);
            } catch (Exception $e) {
                $this->app->enqueueMessage(JText::_('PLG_KUNENA_DISCORD_ERROR'), 'error');
            }
        }
    }
}
