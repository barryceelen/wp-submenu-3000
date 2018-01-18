# WordPress automatic submenu plugin

[![Build Status](https://travis-ci.org/barryceelen/wp-submenu-3000.svg?branch=master)](https://travis-ci.org/barryceelen/wp-submenu-3000)

If no submenu item is explicitly set, this plugin automatically adds submenu items if the menu item points to a hierarchical post type.

The plugin acts on all menus and respects the 'depth' option.

Include or ignore specific menus via a filter:

```
add_filter( 'submenu_3000', 'prefix_filter_submenu_3000', 10, 4 );

/**
 * Only add submenu items to a specific theme location.
 *
 * @param string   $item_output The menu item's starting HTML output.
 * @param WP_Post  $item        Menu item data object.
 * @param int      $depth       Depth of menu item. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 */
function prefix_filter_submenu_3000( $item_output, $item, $depth, $args ) {

	if ( 'primary' !== $args->theme_location ) {
		return false;
	}
}
```

[Blog Post](https://cobbledco.de/automatically-add-submenu-items/)
