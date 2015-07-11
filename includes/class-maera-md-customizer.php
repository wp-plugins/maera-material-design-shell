<?php

class Maera_MD_Customizer {

	function __construct() {

		add_action( 'customize_register', array( $this, 'customizer_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'fields' ) );

	}

	/*
	 * Create the sections
	 */
	function customizer_sections( $wp_customize ) {

		/**
		 * Add panels
		 */
		$wp_customize->add_panel( 'navigation', array(
			'priority'    => 10,
			'title'       => __( 'Navigation', 'kirki' ),
		) );

		// Remove the default navigation section
		$wp_customize->remove_section( 'nav' );

		$sections = array(
			'layout'       => array( 'title' => __( 'Layout', 'maera_md' ),             'priority' => 20, ),
			'typography'   => array( 'title' => __( 'Typography', 'maera_md' ),         'priority' => 30, ),
			'colors'       => array( 'title' => __( 'Colors', 'maera_md' ),             'priority' => 40, ),
			'blog'         => array( 'title' => __( 'Blog', 'maera_md' ),               'priority' => 50, ),
			'header_image' => array( 'title' => __( 'Header', 'maera_md' ),             'priority' => 10 ),
			'advanced'     => array( 'title' => __( 'Advanced', 'maera_md' ),           'priority' => 200 ),

			'nav'          => array( 'title' => __( 'Navigation Menus', 'maera_md' ),   'priority' => 10, 'panel' => 'navigation' ),
			'nav_options'  => array( 'title' => __( 'Navigation Options', 'maera_md' ), 'priority' => 20, 'panel' => 'navigation' ),
			'nav_bg'       => array( 'title' => __( 'Navbar Background', 'maera_md' ),  'priority' => 30, 'panel' => 'navigation' ),
			'offcanvas'    => array( 'title' => __( 'Off-Canvas Menu', 'maera_md' ),    'priority' => 40, 'panel' => 'navigation', 'description' => __( 'The off-canvas menu is only isible if you select the combined nav mode.', 'maera_md' ) ),
			'offcanvas_wa' => array( 'title' => __( 'Off-Canvas Sidebar', 'maera_md' ), 'priority' => 50, 'panel' => 'navigation', 'description' => __( 'The off-canvas widget area is only isible if you select the combined nav mode.', 'maera_md' ) ),

		);

		foreach ( $sections as $section => $args ) {

			$wp_customize->add_section( $section, array(
				'title'    => $args['title'],
				'priority' => $args['priority'],
				'panel'    => isset( $args['panel'] ) ? $args['panel'] : '',
			) );

		}

	}

	function fields( $fields ) {

		$colors = Maera_MD_Data::colors();
		$colors_array = array();
		foreach ( $colors as $color => $definitions ) {
			$colors_array[$color] = $definitions['label'];
		}

		$fields[] = array(
			'type'     => 'textarea',
			'settings' => 'header_content',
			'label'    => __( 'Header Content', 'textdomain' ),
			'section'  => 'header_image',
			'default'  => '',
			'priority' => 30,
		);

		$fields[] = array(
			'type'     => 'select',
			'settings' => 'header_color',
			'label'    => __( 'Header background shade', 'maera_md' ),
			'section'  => 'header_image',
			'default'  => 'darken-2',
			'priority' => 20,
			'choices'  => $colors_array,
		);

		$fields[] = array(
			'type'     => 'slider',
			'settings' => 'header_height',
			'label'    => __( 'Header height (percentage of screen height)', 'maera_md' ),
			'section'  => 'header_image',
			'priority' => 30,
			'default'  => 60,
			'choices'  => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			),
			'output'   => array(
				'element'  => '.header.hero',
				'property' => 'height',
				'units'    => 'vh',
			)
		);

		$fields[] = array(
			'type'     => 'checkbox',
			'settings' => 'header_front',
			'label'    => __( 'Show only on homepage', 'maera_md' ),
			'section'  => 'header_image',
			'default'  => 1,
			'priority' => 30,
		);

		$fields[] = array(
			'type'     => 'radio',
			'mode'     => 'image',
			'settings' => 'layout',
			'label'    => __( 'Default Layout', 'maera_md' ),
			'subtitle' => __( 'Select your main layout. Please note that if no widgets are present in a sidebar then that sidebar will not be displayed. ', 'maera_md' ),
			'section'  => 'layout',
			'priority' => 10,
			'default'  => 0,
			'choices'  => array(
				'0' => get_template_directory_uri() . '/assets/images/1c.png',
				'1' => get_template_directory_uri() . '/assets/images/2cr.png',
				'2' => get_template_directory_uri() . '/assets/images/2cl.png',
			),
		);

		$fields[] = array(
			'type'     => 'slider',
			'settings' => 'site_width',
			'label'    => __( 'Maximum container width', 'maera_md' ),
			'subtitle' => __( 'Select the maximum container width for your site.', 'maera_md' ),
			'section'  => 'layout',
			'priority' => 20,
			'default'  => 1280,
			'choices'  => array(
				'min'  => 640,
				'max'  => 2600,
				'step' => 1,
			),
			'output'   => array(
				'element'  => '.container',
				'property' => 'max-width',
				'units'    => 'px !important'
			)
		);

		$fields[] = array(
			'type'     => 'slider',
			'settings' => 'sidebar_width',
			'label'    => __( 'Sidebar Width', 'maera_md' ),
			'description' => '',
			'section'  => 'layout',
			'priority' => 30,
			'default'  => 4,
			'choices'  => array(
				'min'  => 1,
				'max'  => 5,
				'step' => 1,
			),
		);

		$post_types = get_post_types( array( 'public' => true ), 'names' );
		$layout = get_theme_mod( 'layout', 1 );

		foreach ( $post_types as $post_type ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => $post_type . '_layout',
				'label'    => __( 'Layout for post-type: ', 'maera_md' ) . $post_type,
				'description' => null,
				'section'  => 'layout',
				'priority' => 92,
				'default'  => $layout,
				'choices'  => array(
					'0' => get_template_directory_uri() . '/assets/images/1c.png',
					'1' => get_template_directory_uri() . '/assets/images/2cr.png',
					'2' => get_template_directory_uri() . '/assets/images/2cl.png',
				),
			);
		}

		$fields[] = array(
			'type'     => 'radio',
			'mode'     => 'buttonset',
			'settings' => 'background_mode',
			'label'    => __( 'Background Mode', 'maera_md' ),
			'section'  => 'colors',
			'default'  => 'light',
			'priority' => 20,
			'choices'  => array(
				'light' => __( 'Light', 'maera_md' ),
				'dark'  => __( 'Dark', 'maera_md' ),
			),
		);

		$fields[] = array(
			'type'     => 'radio',
			'mode'     => 'image',
			'settings' => 'accent_color',
			'subtitle' => __( 'Please select a color. This will change the color of the navbar, links and the footer.', 'maera_md' ),
			'label'    => __( 'Accent Color', 'maera_md' ),
			'section'  => 'colors',
			'default'  => '',
			'priority' => 30,
			'choices'  => array(
				'red'         => MAERA_MATERIAL_SHELL_URL . '/assets/img/red.png',
				'pink'        => MAERA_MATERIAL_SHELL_URL . '/assets/img/pink.png',
				'purple'      => MAERA_MATERIAL_SHELL_URL . '/assets/img/purple.png',
				'deep-purple' => MAERA_MATERIAL_SHELL_URL . '/assets/img/deep-purple.png',
				'indigo'      => MAERA_MATERIAL_SHELL_URL . '/assets/img/indigo.png',
				'blue'        => MAERA_MATERIAL_SHELL_URL . '/assets/img/blue.png',
				'light-blue'  => MAERA_MATERIAL_SHELL_URL . '/assets/img/light-blue.png',
				'cyan'        => MAERA_MATERIAL_SHELL_URL . '/assets/img/cyan.png',
				'teal'        => MAERA_MATERIAL_SHELL_URL . '/assets/img/teal.png',
				'green'       => MAERA_MATERIAL_SHELL_URL . '/assets/img/green.png',
				'light-green' => MAERA_MATERIAL_SHELL_URL . '/assets/img/light-green.png',
				'lime'        => MAERA_MATERIAL_SHELL_URL . '/assets/img/lime.png',
				'yellow'      => MAERA_MATERIAL_SHELL_URL . '/assets/img/yellow.png',
				'amber'       => MAERA_MATERIAL_SHELL_URL . '/assets/img/amber.png',
				'orange'      => MAERA_MATERIAL_SHELL_URL . '/assets/img/orange.png',
				'deep-orange' => MAERA_MATERIAL_SHELL_URL . '/assets/img/deep-orange.png',
				'brown'       => MAERA_MATERIAL_SHELL_URL . '/assets/img/brown.png',
				'grey'        => MAERA_MATERIAL_SHELL_URL . '/assets/img/grey.png',
				'blue-grey'   => MAERA_MATERIAL_SHELL_URL . '/assets/img/blue-grey.png',
			),
		);

		$fields[] = array(
			'type'     => 'checkbox',
			'settings' => 'navbar_disable',
			'label'    => __( 'Disable Navigation', 'maera_md' ),
			'description' => __( 'Completely disable navigation', 'maera_md' ),
			'section'  => 'nav_options',
			'priority' => 10,
			'default'  => 0,
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'navbar_disable_separator',
			'section'  => 'nav_options',
			'priority' => 11,
			'default'  => '<hr>',
		);

		$fields[] = array(
			'type'     => 'radio-buttonset',
			'settings' => 'nav_mode',
			'label'    => __( 'Navigation mode', 'maera_md' ),
			// 'description' => __( 'When "combined nav" is turned on, your site will display a navbar that will contain the site logo, a searchbar, breadcrumbs and 2 menus: offcanvas and horizontal. Please note that the primary menu will no longer be used, and the new menus are non-hierarchical.', 'maera_md' ),
			'section'  => 'nav_options',
			'priority' => 20,
			'default'  => 'default',
			'choices'  => array(
				'static-left' => __( 'Left', 'maera_md' ),
				'default'     => __( 'Default', 'maera_md' ),
				'combo'       => __( 'Combo', 'maera_md' ),
			),
		);

		$fields[] = array(
			'type'     => 'toggle',
			'settings' => 'feat_img_post_only',
			'label'    => __( 'Show featured images on posts only', 'maera_md' ),
			'description' => __( 'Featured images by default are only enabled for posts. If you want to enable them for other post types as well then please de-select this option.', 'maera_md' ),
			'section'  => 'blog',
			'default'  => 1,
			'priority' => 2,
		);

		$fields[] = array(
			'type'     => 'slider',
			'settings' => 'feat_img_height',
			'label'    => __( 'Featured Image Height on Archives', 'maera_md' ),
			'subtitle' => __( 'Set to 0 if you want to completely disable featured images on archives', 'maera_md' ),
			'section'  => 'blog',
			'priority' => 4,
			'default'  => 60,
			'choices'  => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			),
		);

		$fields[] = array(
			'type'     => 'select',
			'settings' => 'blog_mode',
			'label'    => __( 'Archive display mode', 'maera_md' ),
			'section'  => 'blog',
			'default'  => 'excerpt',
			'priority' => 6,
			'choices'  => array(
				'excerpt' => __( 'Excerpt', 'maera_md' ),
				'full'    => __( 'Full Content', 'maera_md' ),
			),
		);

		$fields[] = array(
			'type'     => 'slider',
			'settings' => 'excerpt_length',
			'label'    => __( 'Excerpt Length', 'maera_md' ),
			'subtitle' => __( 'Set to 0 if you want to completely disable featured images on archives', 'maera_md' ),
			'section'  => 'blog',
			'priority' => 8,
			'default'  => 40,
			'choices'  => array(
				'min'  => 0,
				'max'  => 200,
				'step' => 1,
			),
		);

		$fields[] = array(
			'type'     => 'text',
			'settings' => 'read_more',
			'label'    => __( 'Read More label', 'maera_md' ),
			'section'  => 'blog',
			'priority' => 10,
			'default'  => __( 'Read More', 'maera_md' ),
		);

		$fields[] = array(
			'type'     => 'textarea',
			'settings' => 'css',
			'label'    => __( 'Custom CSS', 'maera_md' ),
			'subtitle' => __( 'You can write your custom CSS here. This code will appear in a style tag appended in the header section of the page.', 'maera_md' ),
			'section'  => 'advanced',
			'priority' => 4,
			'default'  => '',
		);

		$fields[] = array(
			'type'     => 'textarea',
			'settings' => 'js',
			'label'    => __( 'Custom JS', 'maera_md' ),
			'subtitle' => __( 'You can write your custom JavaScript/jQuery here. The code will be included in a script tag appended to the bottom of the page.', 'maera_md' ),
			'section'  => 'advanced',
			'priority' => 6,
			'default'  => '',
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'flow_text_explanation',
			'section'  => 'typography',
			'default'  => __( 'Flow Text will make the font-sizes responsive and dependand on the visitor\'s screen-size.', 'maera_md' ),
			'priority' => 1,
		);

		$fields[] = array(
			'type'     => 'checkbox',
			'settings' => 'flow_text',
			'label'    => __( 'Enable flow-text everywhere', 'maera_md' ),
			'section'  => 'typography',
			'default'  => 0,
			'priority' => 2,
		);

		$fields[] = array(
			'type'     => 'checkbox',
			'settings' => 'flow_text_content',
			'label'    => __( 'Enable flow-text on content', 'maera_md' ),
			'section'  => 'typography',
			'default'  => 1,
			'priority' => 3,
		);

		$fields[] = array(
            'type'     => 'select',
            'settings' => 'font_base_family',
            'label'    => __( 'Base font', 'maera_md' ),
            'section'  => 'typography',
            'default'  => '"Roboto"',
            'priority' => 5,
            'choices'  => Kirki_Fonts::get_font_choices(),
            'output' => array(
                'element'  => 'html',
                'property' => 'font-family',
            ),
        );

		$fields[] = array(
            'type'     => 'select',
            'settings' => 'font_headers_family',
            'label'    => __( 'Headers font', 'maera_md' ),
            'section'  => 'typography',
            'default'  => "Roboto Slab",
            'priority' => 10,
            'choices'  => Kirki_Fonts::get_font_choices(),
            'output' => array(
                'element'  => 'h1, h2, h3, h4, h5, h6',
                'property' => 'font-family',
            ),
        );

		$fields[] = array(
            'type'     => 'multicheck',
            'settings' => 'font_subsets',
            'label'    => __( 'Google-Font subsets', 'maera_md' ),
            'description' => __( 'The subsets used from Google\'s API.', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 'all',
            'priority' => 15,
            'choices'  => Kirki_Fonts::get_google_font_subsets(),
            'output' => array(
                'element'  => 'body',
                'property' => 'font-subset',
            ),
        );

		$fields[] = array(
			'type'     => 'slider',
			'settings' => 'base_font_size',
			'label'    => __( 'Base font-size', 'maera_md' ),
			'section'  => 'typography',
			'priority' => 20,
			'default'  => 14,
			'choices'  => array(
				'min'  => 4,
				'max'  => 32,
				'step' => 1,
			),
			'output'   => array(
				'property' => 'font-size',
				'units'    => 'px',
				'element'  => 'html',
			)
		);

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_base_weight',
            'label'    => __( 'Base Font Weight', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 300,
            'priority' => 25,
            'choices'  => array(
                'min'  => 100,
                'max'  => 900,
                'step' => 100,
            ),
            'output' => array(
                'element'  => 'html, body, .flow-text',
                'property' => 'font-weight',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_base_height',
            'label'    => __( 'Base Line Height', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 1.43,
            'priority' => 30,
            'choices'  => array(
                'min'  => 0,
                'max'  => 3,
                'step' => 0.01,
            ),
            'output' => array(
                'element'  => 'body',
                'property' => 'line-height',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_headers_weight_h1',
            'label'    => __( 'H1 Font Weight', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 900,
            'priority' => 35,
            'choices'  => array(
                'min'  => 100,
                'max'  => 900,
                'step' => 100,
            ),
            'output' => array(
                'element'  => 'h1',
                'property' => 'font-weight',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_headers_weight_h2',
            'label'    => __( 'H2 Font Weight', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 800,
            'priority' => 40,
            'choices'  => array(
                'min'  => 100,
                'max'  => 900,
                'step' => 100,
            ),
            'output' => array(
                'element'  => 'h2',
                'property' => 'font-weight',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_headers_weight_h3',
            'label'    => __( 'H2 Font Weight', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 600,
            'priority' => 45,
            'choices'  => array(
                'min'  => 100,
                'max'  => 900,
                'step' => 100,
            ),
            'output' => array(
                'element'  => 'h3',
                'property' => 'font-weight',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_headers_weight_h4',
            'label'    => __( 'H4 Font Weight', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 400,
            'priority' => 50,
            'choices'  => array(
                'min'  => 100,
                'max'  => 900,
                'step' => 100,
            ),
            'output' => array(
                'element'  => 'h4',
                'property' => 'font-weight',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_h1_size',
            'label'    => __( 'H1 Font Size', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 52,
            'priority' => 55,
            'choices'  => array(
                'min'  => 7,
                'max'  => 72,
                'step' => 1,
            ),
            'output' => array(
                'element'  => 'h1',
                'property' => 'font-size',
                'units'    => 'px',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_h2_size',
            'label'    => __( 'H2 Font Size', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 36,
            'priority' => 60,
            'choices'  => array(
                'min'  => 7,
                'max'  => 72,
                'step' => 1,
            ),
            'output' => array(
                'element'  => 'h2',
                'property' => 'font-size',
                'units'    => 'px',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_h3_size',
            'label'    => __( 'H3 Font Size', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 24,
            'priority' => 65,
            'choices'  => array(
                'min'  => 7,
                'max'  => 72,
                'step' => 1,
            ),
            'output' => array(
                'element'  => 'h3',
                'property' => 'font-size',
                'units'    => 'px',
            ),
        );

        $fields[] = array(
            'type'     => 'slider',
            'settings' => 'font_h4_size',
            'label'    => __( 'H4 Font Size', 'maera_md' ),
            'section'  => 'typography',
            'default'  => 18,
            'priority' => 70,
            'choices'  => array(
                'min'  => 7,
                'max'  => 72,
                'step' => 1,
            ),
            'output' => array(
                'element'  => 'h4',
                'property' => 'font-size',
                'units'    => 'px',
            ),
        );

		$fields[] = array(
		    'type'        => 'background',
		    'setting'     => 'nav_background',
		    'label'       => __( 'Navigation background', 'kirki' ),
		    'description' => __( 'Choose a background for your main navigation area', 'kirki' ),
		    'help'        => __( 'This is some extra help. You can use this to add some additional instructions for users. The main description should go in the "description" of the field, this is only to be used for help tips.', 'kirki' ),
		    'section'     => 'nav_bg',
		    'default'     => array(
		        'image'    => '',
		        'repeat'   => 'no-repeat',
		        'size'     => 'cover',
		        'opacity'  => 100
		    ),
		    'priority'    => 100,
		    'output'      => '#header-wrapper',
		);

		$fields[] = array(
		    'type'        => 'background',
		    'setting'     => 'offcanvas_background',
		    'label'       => __( 'Background for the off-canvas menu', 'kirki' ),
		    'section'     => 'offcanvas',
		    'default'     => array(
		        'color'    => 'rgba(38, 50, 56, .95)',
		        'image'    => '',
		        'repeat'   => 'no-repeat',
		        'size'     => 'cover',
		        'attach'   => 'fixed',
		        'position' => 'left-top',
		    ),
		    'priority'    => 10,
		    'output'      => '.left-offcanvas-menu',
		);

		$fields[] = array(
		    'type'        => 'color',
		    'setting'     => 'offcanvas_color',
		    'label'       => __( 'Text Color for the off-canvas menu', 'kirki' ),
		    'section'     => 'offcanvas',
		    'default'     => '#ffffff',
		    'priority'    => 10,
		    'output'      => array(
				'element'  => '.left-offcanvas-menu, .left-offcanvas-menu a, .left-offcanvas-menu a:hover, .left-offcanvas-menu a:visited, .left-offcanvas-menu a:active, .left-offcanvas-menu .dashicons',
				'property' => 'color',
			),
		);

		$fields[] = array(
		    'type'        => 'background',
		    'setting'     => 'offcanvas_wa_background',
		    'label'       => __( 'Background for the off-canvas widget area', 'kirki' ),
		    'section'     => 'offcanvas_wa',
		    'default'     => array(
		        'color'    => 'rgba(38, 50, 56, .95)',
		        'image'    => '',
		        'repeat'   => 'no-repeat',
		        'size'     => 'cover',
		        'attach'   => 'fixed',
		        'position' => 'left-top',
		    ),
		    'priority'    => 10,
		    'output'      => '.offcanvas-sidebar',
		);

		$fields[] = array(
		    'type'        => 'color',
		    'setting'     => 'offcanvas_wa_color',
		    'label'       => __( 'Text Color for the off-canvas widget area', 'kirki' ),
		    'section'     => 'offcanvas',
		    'default'     => '#ffffff',
		    'priority'    => 10,
		    'output'      => array(
				'element'  => '.offcanvas-sidebar, .offcanvas-sidebar a, .offcanvas-sidebar a:hover, .offcanvas-sidebar a:visited, .offcanvas-sidebar a:active, .offcanvas-sidebar .dashicons',
				'property' => 'color',
			),
		);

		return $fields;

	}

}
