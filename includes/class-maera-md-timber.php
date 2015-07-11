<?php

class Maera_MD_Timber extends Maera_Material {

	function __construct() {
		add_filter( 'maera/timber/context', array( $this, 'timber_global_context' ) );
	}

	/**
	 * Modify Timber global context
	 */
	function timber_global_context( $data ) {

		$data['header_img']           = Maera_Material::custom_header_url();
		$data['sidebar']['header']    = Timber::get_widgets( 'sidebar_header' );
		$data['sidebar']['footer']    = Timber::get_widgets( 'sidebar_footer' );
		$data['sidebar']['offcanvas'] = Timber::get_widgets( 'offcanvas' );
		$data['layout']               = Maera()->shell->instance->layout;
		$data['menu']['offcanvas']    = has_nav_menu( 'offcanvas' )  ? new TimberMenu( 'offcanvas' )  : null;
		$data['menu']['horizontal']   = has_nav_menu( 'horizontal' ) ? new TimberMenu( 'horizontal' ) : null;

		return $data;

	}

}
