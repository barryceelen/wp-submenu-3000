# WordPress automatic submenu plugin

[![Build Status](https://travis-ci.org/barryceelen/wp-submenu-3000.svg?branch=master)](https://travis-ci.org/barryceelen/wp-submenu-3000)

If no submenu item is explicitly set, this plugin automatically adds submenu items if the menu item points to a hierarchical post type.

The plugin acts on all menus and respects the 'depth' option.
To disable the plugin for a specific menu, add a filter:

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

## Filter css classes and link attributes

- Filter the CSS classes for a submenu &lt;ul&gt; element: `submenu_3000_css_class`
- Filter the CSS classes for a submenu item &lt;li&gt; element: `submenu_3000_item_css_class`
- Filter the HTML attributes for a submenu item link element: `submenu_3000_menu_link_attributes`

[Read More](https://cobbledco.de/automatically-add-submenu-items/)
