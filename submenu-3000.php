<?php
/**
 * Main plugin file
 *
 * @package   Submenu_3000
 * @author    Barry Ceelen <b@rryceelen.com>
 * @license   GPL-3.0+
 * @link      https://github.com/barryceelen/wp-submenu-3000
 * @copyright 2017 Barry Ceelen
 *
 * Plugin Name:       Submenu 3000
 * Plugin URI:        https://github.com/barryceelen/wp-submenu-3000
 * Description:       Automatically add submenu items to navigation menu items.
 * Version:           2.0.0
 * Author:            Barry Ceelen
 * Author URI:        https://github.com/barryceelen
 * Text Domain:       submenu-3000
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/barryceelen/wp-submenu-3000
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! is_admin() ) {
	require_once( 'inc/functions.php' );
	add_filter( 'walker_nav_menu_start_el', 'submenu_3000_filter_nav_menu_item', 10, 4 );
}

