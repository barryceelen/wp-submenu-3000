<?php
/**
 * Submenu_3000_Walker class
 *
 * @package    Submenu_3000
 * @author     Barry Ceelen <b@rryceelen.com>
 * @license    GPL-3.0+
 * @link       https://github.com/barryceelen/wp-submenu-3000
 * @copyright  2017 Barry Ceelen
 */

/**
 * Walker class used to create an HTML list of menu items.
 *
 * Based on the core page walker class, slightly adapted to get the
 * proper class names for the menu items.
 *
 * @since 1.0.0
 *
 * @see Walker_Page
 */
class Submenu_3000_Walker extends Walker_Page {

	/**
	 * What the class handles.
	 *
	 * @since 2.1.0
	 * @access public
	 * @var string
	 *
	 * @see Walker::$tree_type
	 */
	public $tree_type = 'post_type';

	/**
	 * Nav menu ID.
	 *
	 * @since 2.1.0
	 * @access public
	 * @var string
	 *
	 * @see Walker::$tree_type
	 */
	private $menu_id;

	/**
	 * Initialize class.
	 *
	 * @since 1.0.0
	 * @param string $menu_id Navigation menu ID.
	 */
	public function __construct( $menu_id = '' ) {
		$this->menu_id = $menu_id;
	}

	/**
	 * Outputs the beginning of the current level in the tree before elements are output.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Optional. Depth of page. Used for padding. Default 0.
	 * @param array  $args   Optional. Arguments for outputting the next level.
	 *                       Default empty array.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		/**
		 * Filters the CSS classes for a submenu <ul> element.
		 *
		 * @since 1.0.0
		 *
		 * @param array    $classes  The CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args     An object of `wp_nav_menu()` arguments.
		 * @param int      $depth    Depth of menu item. Used for padding.
		 * @param string   $menu_id  Menu ID.
		 */
		$class_names = join( ' ', apply_filters( 'submenu_3000_submenu_css_class', $classes, $args, $depth, $this->menu_id ) ); // WPCS: prefix ok.
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$atts = array();

		/**
		 * Filters he HTML attributes for a submenu <ul> element.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the submenu `<ul>` element, empty strings are ignored.
		 * }
		 * @param array  $args    An array of arguments.
		 * @param int    $depth   Depth of item, used for padding.
		 * @param string $menu_id Menu ID.
		 */
		$atts = apply_filters( 'submenu_3000_submenu_attributes', $atts, $args, $depth, $this->menu_id );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$output .= "{$n}{$indent}<ul {$class_names}{$attributes}>{$n}";
	}

	/**
	 * Outputs the beginning of the current element in the tree.
	 *
	 * @see Walker::start_el()
	 * @since 2.1.0
	 * @access public
	 *
	 * @param string  $output       Used to append additional content. Passed by reference.
	 * @param WP_Post $post         Post data object.
	 * @param int     $depth        Optional. Depth of post. Used for padding. Default 0.
	 * @param array   $args         Optional. Array of arguments. Default empty array.
	 * @param int     $current_page Optional. Post ID. Default 0.
	 */
	public function start_el( &$output, $post, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		if ( $depth ) {
			$indent = str_repeat( $t, $depth );
		} else {
			$indent = '';
		}

		$css_class = array(
			'menu-item',
			'menu-item-type-post_type',
			'menu-item-object-' . esc_attr( $post->post_type ),
			'menu-item-' . (int) $post->ID,
		);

		if ( 'page' === $post->post_type ) {
			$css_class[] = 'page_item';
			$css_class[] = 'page-item-' . (int) $post->ID;
		}

		if ( isset( $args['pages_with_children'][ $post->ID ] ) ) {
			$css_class[] = 'menu-item-has-children';
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( $_current_page && in_array( $post->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current-page-ancestor';
			}
			if ( $post->ID == $current_page ) {
				$css_class[] = 'current-menu-item';
				if ( 'page' === $post->post_type ) {
					$css_class[] = 'current_page_item';
				}
			} elseif ( $_current_page && $post->ID == $_current_page->post_parent ) {
				$css_class[] = 'current-page-parent';
			}
		} elseif ( (int) get_option( 'page_for_posts' ) === (int) $post->ID ) {
			$css_class[] = 'current-page-parent';
		}

		/**
		 * Filters the list of CSS classes to include with each item in the list.
		 *
		 * @since 1.0.0
		 *
		 * @see wp_list_pages()
		 *
		 * @param array   $css_class    An array of CSS classes to be applied
		 *                              to each list item.
		 * @param WP_Post $post         Post data object.
		 * @param int     $depth        Depth of post, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current post.
		 * @param string  $menu_id      Menu ID.
		 */
		$css_classes = implode( ' ', apply_filters( 'submenu_3000_item_css_class', $css_class, $post, $depth, $args, $current_page, $this->menu_id ) );

		if ( '' === $post->post_title ) {
			/* translators: %d: ID of a post */
			$post->post_title = sprintf( __( '#%d (no title)', 'default' ), $post->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

		$atts = array();
		$atts['href'] = get_permalink( $post->ID );

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $href The href attribute.
		 * }
		 * @param WP_Post $post         Post data object.
		 * @param int     $depth        Depth of post, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current post.
		 * @param string  $menu_id      Menu ID.
		 */
		$atts = apply_filters( 'submenu_3000_menu_link_attributes', $atts, $post, $depth, $args, $current_page, $this->menu_id );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$output .= $indent . sprintf(
			'<li class="%s"><a%s>%s%s%s</a>',
			$css_classes,
			$attributes,
			$args['link_before'],
			/** This filter is documented in wp-includes/post-template.php */
			apply_filters( 'the_title', $post->post_title, $post->ID ), // WPCS: Prefix ok.
			$args['link_after']
		);
	}
}
