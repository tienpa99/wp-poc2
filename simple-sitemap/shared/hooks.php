<?php

namespace WPGO_Plugins\Simple_Sitemap;

/**
 * Hooks class.
 */
class Hooks {

	/**
	 * Allows you to filter the plugin options defaults array.
	 *
	 * @param array $defaults Plugin options defaults.
	 * @return array Plugin defaults.
	 */
	public static function simple_sitemap_defaults( $defaults ) {
		return apply_filters( 'simple_sitemap_defaults', $defaults );
	}

	/**
	 * Allows you to filter the post title text.
	 *
	 * @param string $title Sitemap title.
	 * @param string $id Sitemap ID.
	 * @return array Sitemap title text.
	 */
	public static function simple_sitemap_title_text( $title, $id ) {
		return apply_filters( 'simple_sitemap_title_text', $title, $id );
	}

	/**
	 * Allows you to filter the post title text.
	 *
	 * @param string $title_link Sitemap title link.
	 * @param string $id Sitemap ID.
	 * @return array Sitemap title link.
	 */
	public static function simple_sitemap_title_link_text( $title_link, $id ) {
		return apply_filters( 'simple_sitemap_title_link_text', $title_link, $id );
	}

}
