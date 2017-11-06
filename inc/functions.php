<?php
/**
 * Contains the plugin functions
 *
 * @package    Submenu_3000
 * @author     Barry Ceelen <b@rryceelen.com>
 * @license    GPL-3.0+
 * @link       https://github.com/barryceelen/wp-submenu-3000
 * @copyright  2017 Barry Ceelen
 */

/**
 * Add submenu items to menu items that do not already have submenu items set.
 *
 * @since 1.0.0
 * @param string   $item_output The menu item's starting HTML output.
 * @param WP_Post  $item        Menu item data object.
 * @param int      $depth       Depth of menu item. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 * @return string
 */
function submenu_3000_filter_nav_menu_item( $item_output, $item, $depth, $args ) {

	/**
	 * Filters whether to automatically add submenu items to this menu item.
	 *
	 * @since 1.0.0
	 * @param string   $item_output The menu item's starting HTML output.
	 * @param WP_Post  $item        Menu item data object.
	 * @param int      $depth       Depth of menu item. Used for padding.
	 * @param stdClass $args        An object of wp_nav_menu() arguments.
	 */
	if ( apply_filters( 'submenu_3000', false, $item_output, $item, $depth, $args ) ) {
		return $item_output;
	}

	if ( 'post_type' !== $item->type ) {
		return $item_output;
	}

	if ( 0 !== $args->depth && $depth === $args->depth ) {
		return $item_output;
	}

	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		return $item_output;
	}

	require_once( 'class-submenu-3000-walker.php' );

	$walker = new Submenu_3000_Walker;

	$submenu = wp_list_pages( array(
		'child_of'    => $item->object_id,
		'depth'       => 0 === $args->depth ? 0 : ($args->depth - $depth) + 1,
		'echo'        => false,
		'post_type'   => $item->object,
		'sort_column' => 'menu_order, post_title',
		'title_li'    => null,
		'walker'      => $walker,
	) );

	if ( ! empty( $submenu ) ) {

		// Default class.
		$classes = array( 'sub-menu' );

		/** This filter is documented in includes/class-submenu-3000-walker.php */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$item_output = $item_output . "<ul $class_names>" . $submenu . '</ul>';
	}

	return $item_output;
}
