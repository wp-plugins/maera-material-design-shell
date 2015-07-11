<?php

class Maera_MD_Styles {

	function __construct() {
		add_filter( 'maera/styles', array( $this, 'custom_header_css' ) );
		add_filter( 'maera/styles', array( $this, 'featured_image_height' ) );
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		add_filter( 'maera_md/header_classes', array( $this, 'hero_classes' ) );

		add_filter( 'maera/nav/class', array( $this, 'color' ) );

		// Add the custom CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'custom_css' ), 105 );
	}

	function color() {

		if ( is_singular() ) {
			global $post;
			$color = get_post_meta( $post->ID, 'maera_md_color', true );
		}

		$custom_color = ( isset( $color ) && ! empty( $color ) && 'default' != $color ) ? true : false;
		return $custom_color ? $color : kirki_get_option( 'accent_color' );

	}

	/**
	 * Add background color classes to the body
	 */
	function body_classes( $classes ) {

		if ( ! function_exists( 'kirki_get_option' ) ) {
			return $classes;
		}

		$background_mode = kirki_get_option( 'background_mode' );
		$nav_mode        = kirki_get_option( 'nav_mode' );
		$navbar_disable  = kirki_get_option( 'navbar_disable' );

		if ( 'dark' == $background_mode ) {
			$classes[] = 'blue-grey';
			$classes[] = 'darken-4';
			$classes[] = 'dark-mode';
		} else {
			$classes[] = 'light-mode';
		}

		$classes[] = 'accent-' . $this->color();
		$classes[] = 'layout-' . Maera()->shell->instance->layout;

		if ( 1 == kirki_get_option( 'flow_text' ) ) {
			$classes[] = ' flow-text';
		}

		if ( 'static-left' == $nav_mode && ! $navbar_disable ) {
			$classes[] = 'static-left-nav';
		}

		return $classes;

	}

	function custom_header_css( $styles ) {

		$custom_header = get_header_image();

		if ( $custom_header ) {
			$styles .= '.header.hero .parallax-layer-back{background-image:url("' . $custom_header . '");}';
		}
		// $styles .= '.header.hero{color:#' . kirki_get_option( 'header_textcolor' ) . '}';

		return $styles;

	}

	function featured_image_height( $styles ) {
		return $styles . '.parallax-container.featured-image{height:' . kirki_get_option( 'feat_img_height' ) . 'vh;}';
	}

	function hero_classes() {

		if ( ! function_exists( 'kirki_get_option' ) ) {
			return;
		}

		$colors  = Maera_MD_Data::colors();
		$color   = kirki_get_option( 'header_color' );
		$classes = $colors[$color]['classes'];

		return $color . ' ' . $classes;

	}

	/**
	* Include the custom CSS
	*/
	function custom_css() {
		$css = kirki_get_option( 'css' );

		if ( ! empty( $css ) ) {
			wp_add_inline_style( 'maera', $css );
		}
	}

}
