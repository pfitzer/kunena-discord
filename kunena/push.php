<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;

class KunenaDiscord extends KunenaActivity
{



    /**
     * @var null
     */
    private $webhook= null;

    /**
     * KunenaActivityCommunity constructor.
     *
     * @param $params
     *
     * @since version
     */
    public function __construct($webhook)
    {
        $this->webhook = $webhook;
        $this->lang = JFactory::getLanguage();
        $this->lang->load('plg_kunena-discord', JPATH_ADMINISTRATOR);
    }

    /**
     * @param KunenaForumMessage $message
     *
     * @return bool|void
     *
     * @since version
     */
    public function onAfterReply($message)
    {
        $this->_prepareAndSend(
            $message,
            Text::_("PLG_KUNENA_PUSHALERT_REPLY_TITLE"),
            Text::_("PLG_KUNENA_PUSHALERT_REPLY_MSG")
        );
    }

    /**
     * @param KunenaForumTopic $message
     *
     * @return bool|void
     *
     * @since version
     */
    public function onAfterPost($message)
    {
        $this->_prepareAndSend(
            $message,
            Text::_("PLG_KUNENA_PUSHALERT_TOPIC_TITLE"),
            Text::_("PLG_KUNENA_PUSHALERT_TOPIC_MSG")
        );
    }

    /**
     * @param KunenaDatabaseObject $message
     *
     * @return boolean
     * @since Kunena
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
     * @param $title
     * @param $pushMessage
     * @param $url
     *
     *
     * @since version
     */
    private function _send_message($title, $pushMessage, $url)
    {
        $content = Text::_('PLG_KUNENA-DISCORD_MESSAGE_NEW') . ' [hyperlink](' . $url . ')';
        $hookObject = json_encode([
            /*
             * The general "message" shown above your embeds
             */
            "content" => $content,
            /*
             * The username shown in the message
             */
            "username" => "Forum",
            /*
             * Whether or not to read the message in Text-to-speech
             */
            "tts" => false,
            /*
             * An array of Embeds
             */
            "embeds" => [
                /*
                 * Our first embed
                 */
                [
                    // Set the title for your embed
                    "title" => "Google.com",

                    // The type of your embed, will ALWAYS be "rich"
                    "type" => "rich",

                    // A description for your embed
                    "description" => "",

                    // The URL of where your title will be a link to
                    "url" => "https://www.google.com/",

                    /* A timestamp to be displayed below the embed, IE for when an an article was posted
                     * This must be formatted as ISO8601
                     */
                    "timestamp" => "2018-03-10T19:15:45-05:00",

                    // The integer color to be used on the left side of the embed
                    "color" => hexdec( "FFFFFF" ),

                    // Footer object
                    "footer" => [
                        "text" => "Google TM",
                        "icon_url" => "https://pbs.twimg.com/profile_images/972154872261853184/RnOg6UyU_400x400.jpg"
                    ],

                    // Image object
                    "image" => [
                        "url" => "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png"
                    ],

                    // Thumbnail object
                    "thumbnail" => [
                        "url" => "https://pbs.twimg.com/profile_images/972154872261853184/RnOg6UyU_400x400.jpg"
                    ],

                    // Author object
                    "author" => [
                        "name" => "Alphabet",
                        "url" => "https://www.abc.xyz"
                    ],

                    // Field array of objects
                    "fields" => [
                        // Field 1
                        [
                            "name" => "Data A",
                            "value" => "Value A",
                            "inline" => false
                        ],
                        // Field 2
                        [
                            "name" => "Data B",
                            "value" => "Value B",
                            "inline" => true
                        ],
                        // Field 3
                        [
                            "name" => "Data C",
                            "value" => "Value C",
                            "inline" => true
                        ]
                    ]
                ]
            ]

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


        $ch = curl_init();

        curl_setopt_array( $ch, [
            CURLOPT_URL => $this->webhook,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                "Length" => strlen( $hookObject ),
                "Content-Type" => "application/json"
            ]
        ]);

        $response = curl_exec( $ch );
        curl_close( $ch );
    }

    /**
     * @param KunenaDatabaseObject $message
     * @param string $translatedTitle
     * @param string $translatedMsg
     *
     * @return void
     */
    private function _prepareAndSend($message, $translatedTitle, $translatedMsg)
    {
        if ($this->_checkPermissions($message)) {
            $title = sprintf($translatedTitle, $message->name);
            $pushMessage = sprintf($translatedMsg, $message->subject);
            $url = $message->getTopic()->getUrl();
            $this->_send_message($title, $pushMessage, $url);
        }
    }
}
