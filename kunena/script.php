<?php
/**
 * @package    kunena-discord
 *
 * @author     michael <michael@mp-development.de>
 * @copyright  Michael Pfister
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://github.com/pfitzer/kunena-discord
 */

defined('_JEXEC') or die;

/**
 * Kunena-discord script file.
 *
 * @package   kunena-discord
 * @since     1.0.0
 */
class plgKunenaDiscordInstallerScript
{
	/**
	 * Constructor
	 *
	 */
	public function __construct($parent) {}

	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
     *
	 * @return  boolean  True on success
	 */
	public function preflight($route, $parent) {}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($route, $parent) {}

	/**
	 * Called on installation
	 *
	 *
	 * @return  boolean  True on success
	 */
	public function install($parent) {}

	/**
	 * Called on update
	 *
	 *
	 * @return  boolean  True on success
	 */
	public function update($parent) {
	    echo "<p><b>Update from version 1.x</b></p>";
        echo "<p>If you update from version prior to 2.0.0, please go to the plugin settings and set the Discord webhook url again!</p>";
    }

	/**
	 * Called on uninstallation
	 *
	 */
	public function uninstall($parent) {}
}
