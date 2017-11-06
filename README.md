# WordPress automatic submenu plugin

[![Build Status](https://travis-ci.org/barryceelen/wp-submenu-3000.svg?branch=master)](https://travis-ci.org/barryceelen/wp-submenu-3000)

Automatically add submenu items to hierarchical post type menu items if no submenu item is set.

Include or ignore specific menus via a filter:

```
add_filter( 'submenu_3000', 'prefix_my_cool_filter', 10, 4 );

/**
 * Only add submenu items to a specific theme location.
 *
 * @param string   $item_output The menu item's starting HTML output.
 * @param WP_Post  $item        Menu item data object.
 * @param int      $depth       Depth of menu item. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 */
function prefix_my_cool_filter( $item_output, $item, $depth, $args ) {

	if ( 'primary' !== $args->theme_location ) {
		return false;
	}
}
```
