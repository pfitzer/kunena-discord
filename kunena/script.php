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
class plgKunenaDisocord
{
	/**
	 * Constructor
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	public function __construct(JAdapterInstance $adapter) {}

	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($route, JAdapterInstance $adapter) {}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($route, JAdapterInstance $adapter) {}

	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function install(JAdapterInstance $adapter) {}

	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function update(JAdapterInstance $adapter) {
	    echo "You have now the ability to set 10 different webhooks and choose the suitable Kunena categories.\n";
	    echo "\n";
	    echo "<b>Update from version 1.x</b>\n";
        echo "If you update from version prior to 2.0.0, please go to the plugin settings and set the Discord webhook url again!\n";
    }

	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	public function uninstall(JAdapterInstance $adapter) {}
}
