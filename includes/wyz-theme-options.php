<?php
/**
 * Initialize the custom Theme Options.
 *
 * @package wyz
 */

add_action( 'init', 'wyz_theme_options' );

/**
 * Build the custom settings & update OptionTree.
 *
 * @since     2.0
 */
function wyz_theme_options() {

	// OptionTree is not loaded yet, or this is not an admin request.
	if ( ! function_exists( 'ot_settings_id' ) || ! is_admin() ) {
		return false;
	}

	/**
	* Get a copy of the saved settings array.
	*/
	$saved_settings = get_option( ot_settings_id(), array() );

	/**
	* Custom settings array that will eventually be
	* passes to the OptionTree Settings API Class.
	*/
	$custom_settings = array(
		'sections' => array(
			array(
				'id' => 'general',
				'title' => esc_html__( 'General', 'koopo-online' ),
			),
			array(
				'id'          => 'header',
				'title'       => esc_html__( 'Header', 'koopo-online' ),
			),
			array(
				'id'          => 'typography',
				'title'       => esc_html__( 'Typography', 'koopo-online' ),
			),
			array(
				'id'          => 'footer',
				'title'       => esc_html__( 'Footer', 'koopo-online' ),
			),
			array(
				'id'          => 'colors',
				'title'       => esc_html__( 'Site Colors', 'koopo-online' ),
			),
			array(
				'id'          => 'css-custom',
				'title'       => esc_html__( 'Custom CSS', 'koopo-online' ),
			),
			array(
				'id'          => 'script-custom',
				'title'       => esc_html__( 'Custom Script', 'koopo-online' ),
			),
			array(
				'id'          => 'accessories',
				'title'       => esc_html__( 'Accessories', 'koopo-online' ),
			),
			array(
				'id'          => 'social_links',
				'title'       => esc_html__( 'Social Links', 'koopo-online' ),
			),
			array(
				'id'          => 'contact',
				'title'       => esc_html__( 'Contact', 'koopo-online' ),
			),
			array(
				'id'          => 'default-images',
				'title'       => esc_html__( 'Default Images', 'koopo-online' ),
			),
			array(
			'id'          => '404',
			'title'       => esc_html__( '404 Page', 'koopo-online' ),
			),
		),
		'settings'        => array(

		// ---------------------------------------------------------
		// GENERAL OPTIONS .
		// Section: general.
		// ---------------------------------------------------------
			array(
				'id'          => 'general-customize',
				'label'       => esc_html__( 'General Options', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'general',
			),
			array(
				'id'          => 'header-logo-upload',
				'label'       => esc_html__( 'Site Logo', 'koopo-online' ),
				'desc'        => esc_html__( 'Choose Logo', 'koopo-online' ),
				'type'        => 'upload',
				'section'     => 'general',
			),
			array(
				'id'          => 'header-logo-dimensions',
				'label'       => esc_html__( 'Logo Dimensions', 'koopo-online' ),
				'desc'        => esc_html__( 'If logo is available, set its width and height', 'koopo-online' ),
				'std'         => '',
				'type'        => 'dimension',
				'section'     => 'general',
			),
			array(
				'id'          => 'header-logo-spacing',
				'label'       => esc_html__( 'Logo Spacing', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'spacing',
				'section'     => 'general',
				'min_max_step'=> array(
									'min' => 0,
									'max' => 100,
									'step' => 1,
								),
			),
			array(
				'id'          => 'logo-font',
				'label'       => esc_html__( 'Title Logo Font', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the font properties of the title when logo image is not set.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'general',
			),
			array(
				'id'          => 'resp',
				'label'       => esc_html__( 'Responsive', 'koopo-online' ),
				'desc'        => esc_html__( 'Enable/Disable site responsiveness', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'general',
			),
			array(
				'id'          => 'content-width',
				'label'       => esc_html__( 'Content Width', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the site\'s content width.', 'koopo-online' ),
				'std'         => '',
				'section'     => 'general',
				'type'        => 'select',
				'condition'   => 'resp:is(off)',
				'choices'     => array(
					array(
						'value'       => '970',
						'label'       => '970px',
					),
					array(
						'value'       => '1140',
						'label'       => '1140px',
					),
					array(
						'value'       => '1260',
						'label'       => '1260px',
					),
					array(
						'value'       => '1400',
						'label'       => '1400px',
					),
				),
			),
			array(
				'id'          => 'blog-title',
				'label'       => esc_html__( 'Blog Title', 'koopo-online' ),
				'desc'        => esc_html__( 'Title for front page in case front page displays your latest posts.', 'koopo-online' ),
				'std'         => 'Blog',
				'type'        => 'text',
				'section'     => 'general',
			),
			array(
				'id'          => 'sidebar-layout',
				'label'       => esc_html__( 'Default Page Layout', 'koopo-online' ),
				'std'         => 'right-sidebar',
				'type'        => 'radio-image',
				'section'     => 'general',
				'choices'      => array(
					array(
						'value' => 'left-sidebar',
						'label' => esc_html__( 'Left Sidebar', 'koopo-online' ),
						'src'   => 'left-sidebar.png',
					),
					array(
						'value' => 'right-sidebar',
						'label' => esc_html__( 'Right Sidebar', 'koopo-online' ),
						'src'   => 'right-sidebar.png',
					),
					array(
						'value' => 'full-width',
						'label' => esc_html__( 'Full Width', 'koopo-online' ),
						'src'   => 'full-width.png',
					),
				),
			),
			array(
				'id'          => 'mobile-home-page',
				'label'       => __( 'Mobile Home Page', 'koopo-online' ),
				'desc'        => __( 'Home page for mobile agents', 'koopo-online' ),
				'type'        => 'page-select',
				'section'     => 'general',
			),
			array(
				'id'          => 'shop-sidebar-layout',
				'label'       => esc_html__( 'Shop Page Layout', 'koopo-online' ),
				'std'         => 'right-sidebar',
				'type'        => 'radio-image',
				'section'     => 'general',
				'choices'      => array(
					array(
						'value' => 'left-sidebar',
						'label' => esc_html__( 'Left Sidebar', 'koopo-online' ),
						'src'   => 'left-sidebar.png',
					),
					array(
						'value' => 'right-sidebar',
						'label' => esc_html__( 'Right Sidebar', 'koopo-online' ),
						'src'   => 'right-sidebar.png',
					),
					array(
						'value' => 'full-width',
						'label' => esc_html__( 'Full Width', 'koopo-online' ),
						'src'   => 'full-width.png',
					),
				),
			),
			array(
				'id'          => 'resp',
				'label'       => esc_html__( 'Responsive', 'koopo-online' ),
				'desc'        => esc_html__( 'Enable/Disable site responsiveness', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'general',
			),
			array(
				'id'          => 'one_page_template',
				'label'       => esc_html__( 'One Page layout', 'koopo-online' ),
				'type'        => 'on-off',
				'section'     => 'general',
				'desc'		  => esc_html__( 'Enable/disable one page layout', 'koopo-online' ),
				'std'         => 'off',
				
			),
			array(
				'id'          => 'one-page-business-cpt',
				'label'       => esc_html__( 'Which Business to display as your site\'s landing page', 'koopo-online' ),
				'std'         => '',
				'type'        => 'custom-post-type-select',
				'section'     => 'general',
				'rows'        => '',
				'post_type'   => 'wyz_business',
				'condition'   => 'one_page_template:is(on)',
			),
			array(
				'id'          => 'wyz_template_type',
				'label'       => esc_html__( 'Site Template', 'koopo-online' ),
				'type'        => 'select',
				'section'     => 'general',
				'desc'		  => esc_html__( 'Choose which template you want for your site.', 'koopo-online' ),
				'choices'     => array( 
					array(
						'value'       => '1',
						'label'       => esc_html__( 'Template 1', 'koopo-online' ),
						'src'         => ''
					),
					array(
						'value'       => '2',
						'label'       => esc_html__( 'Template 2', 'koopo-online' ),
						'src'         => ''
					),
				),
			),
			array(
				'id'          => 'wyz_single_bus_template_type',
				'label'       => esc_html__( 'Single Business Template', 'koopo-online' ),
				'type'        => 'select',
				'section'     => 'general',
				'std'         => '2',
				'condition'   => 'wyz_template_type:is(2)',
				'desc'		  => esc_html__( 'Choose which template you want for your Single Business Page.', 'koopo-online' ),
				'choices'     => array( 
					array(
						'value'       => '1',
						'label'       => esc_html__( 'Template 1', 'koopo-online' ),
						'src'         => ''
					),
					array(
						'value'       => '2',
						'label'       => esc_html__( 'Template 2', 'koopo-online' ),
						'src'         => ''
					),
				),
			),
			array(
				'id'          => 'listing_archives_ess_grid',
				'label'       => esc_html__( 'Listing Archives Essential Grid Alias', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'general',
				'condition'   => 'wyz_template_type:is(2)',
			),
			array(
				'id'          => 'listing_search_ess_grid',
				'label'       => esc_html__( 'Listing Search Essential Grid Alias', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'general',
				'condition'   => 'wyz_template_type:is(2)',
			),
			array(
				'id'          => 'terms-and-cond-on-off',
				'label'       => esc_html__( 'Terms and Conditions', 'koopo-online' ),
				'desc'        => esc_html__( 'Display terms and conditions link in sign up page', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'general',
			),
			array(
				'id'          => 'terms-and-conditions',
				'label'       => esc_html( 'Terms and Conditions Text', 'koopo-online' ),
				'desc'        => esc_html__( 'The text to display in the terms and coditions notice.', 'koopo-online' ) . '<br/>' . esc_html__( 'Place the word you want to become a link in between %%.', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'general',
				'condition'   => 'terms-and-cond-on-off:is(on)',
				'std'         => 'By signing in, you agree to the %Terms and Conditions%.',
				'class'		  => 'fullwidth',
			),

		// ---------------------------------------------------------
		// HEADER OPTIONS .
		// Section: header.
		// ---------------------------------------------------------
			array(
				'id'          => 'menu-customize',
				'label'       => esc_html__( 'Header Options', 'koopo-online' ),
				'desc'        => esc_html__( 'Here are the options for customising the menu', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'header',
			),
			array(
				'id'          => 'header-layout',
				'label'       => esc_html__( 'Header Layout', 'koopo-online' ),
				'desc'        => esc_html__( 'Note that the login Menu to appear, you need to create a Menu and assign it as Login menu Location', 'koopo-online' ),
				'std'         => 'header1',
				'type'        => 'radio-image',
				'section'     => 'header',
				'condition'   => 'wyz_template_type:not(2)',
				'choices'      => array(
					array(
						'value' => 'header1',
						'label' => esc_html__( 'Header 1', 'koopo-online' ),
						'src'   => 'header1.jpg',
					),
					array(
						'value' => 'header2',
						'label' => esc_html__( 'Header 2', 'koopo-online' ),
						'src'   => 'header2.jpg',
					),
					array(
						'value' => 'header3',
						'label' => esc_html__( 'Header 3', 'koopo-online' ),
						'src'   => 'header3.jpg',
					),
					array(
						'value' => 'header4',
						'label' => esc_html__( 'Header 4', 'koopo-online' ),
						'src'   => 'header4.jpg',
					),
				),
			),
			array(
				'id'          => 'header-layout2',
				'label'       => esc_html__( 'Header Layout', 'koopo-online' ),
				'std'         => 'header1',
				'type'        => 'radio-image',
				'section'     => 'header',
				'condition'   => 'wyz_template_type:is(2)',
				'choices'      => array(
					array(
						'value' => 'header1',
						'label' => esc_html__( 'Header 1', 'koopo-online' ),
						'src'   => 'header21.jpg',
					),
					array(
						'value' => 'header2',
						'label' => esc_html__( 'Header 2', 'koopo-online' ),
						'src'   => 'header22.jpg',
					),
					array(
						'value' => 'header3',
						'label' => esc_html__( 'Header 3', 'koopo-online' ),
						'src'   => 'header23.jpg',
					),
				),
			),
			array(
				'id'          => 'acgbtb_right_content',
				'label'       => esc_html( 'Header Right Content', 'koopo-online' ),
				'desc'        => esc_html__( 'Note that this tab is used to fill Ad Spaces that can be found in some Header Types', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'header',
				'class'		  => 'fullwidth',
				'condition'   => 'header-layout:not(header1),header-layout:not(header2),wyz_template_type:is(1)',
				'operator'   => 'and',
			),
			array(
				'id'          => 'acgbtb_right_content2',
				'label'       => esc_html( 'Header Right Content', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'header',
				'class'		  => 'fullwidth',
				'condition'   => 'header-layout2:is(header2),wyz_template_type:is(2)',
				'operator'   => 'and',
			),
			array(
				'id'          => 'header-login-menu',
				'label'       => esc_html__( 'Header Login Menu', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'header',
				'condition'   => 'wyz_template_type:is(2)',
			),
			array(
				'id'          => 'mobile-menu-layout',
				'label'       => esc_html__( 'Mobile Menu Layout', 'koopo-online' ),
				'std'         => 'menu-1',
				'type'        => 'radio-image',
				'section'     => 'header',
				'choices'      => array(
					array(
						'value' => 'menu-1',
						'label' => esc_html__( 'Menu 1', 'koopo-online' ),
						'src'   => 'mobilemenu1.jpg',
					),
					array(
						'value' => 'menu-2',
						'label' => esc_html__( 'Menu 2', 'koopo-online' ),
						'src'   => 'mobilemenu2.jpg',
					),
				),
			),
			array(
				'id'          => 'wyz-sep-mobile-menu',
				'label'       => esc_html__( 'Separate Mobile menu', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'header',
				'desc'		  => 'Use a different menu for mobile agents. You can set this mobile menu in Appearance>menus>Manage Locations  and then set a menu for the "Mobie Menu" dropdown',
			),
			array(
				'id'          => 'wyz-hide-sub-header-in-bus-arch',
				'label'       => esc_html__( 'Hide Sub Header', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'header',
				'desc'		  => 'Hides Sub Header in Business Archives Pages',
			),
			array(
				'id'          => 'utility-bar-onoff',
				'label'       => esc_html__( 'Display Utility Bar', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'header',
			),
			array(
				'id'          => 'utility-bar-bg-color',
				'label'       => esc_html__( 'Utility Bar BG color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the background color for the Utility bar.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'header',
				'condition'   => 'utility-bar-onoff:is(on)',
			),
			array(
				'id'          => 'utility-bar-txt-color',
				'label'       => esc_html__( 'Utility Bar Text color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the text color for the Utility bar.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'header',
				'condition'   => 'utility-bar-onoff:is(on)'
			),
			array(
				'id'          => 'support-text',
				'label'       => esc_html__( 'Support Label', 'koopo-online' ),
				'desc'        => esc_html__( 'Label for the link that users can click on to get support. Displays in the utility bar', 'koopo-online' ),
				'std'         => 'Support',
				'type'        => 'text',
				'section'     => 'header',
				'condition'   => 'utility-bar-onoff:is(on)',
			),
			array(
				'id'          => 'support-link',
				'label'       => esc_html__( 'Support Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link that users are taken to when they click on \'Support\'.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'header',
				'condition'   => 'utility-bar-onoff:is(on)',
			),
			array(
				'id'          => 'subheader-bg-upload',
				'label'       => esc_html__( 'Subheader BG Image', 'koopo-online' ),
				'desc'        => '',
				'type'        => 'upload',
				'section'     => 'header',
				'condition'   => 'wyz_template_type:is(2)',
			),
			array(
				'id'          => 'login-btn-content-type',
				'label'       => esc_html__( 'My Account Button Content', 'koopo-online' ),
				'desc'        => esc_html__( 'What should be displayed inside the \'My Account\' button in the login menuwhen user is logged in', 'koopo-online' ),
				'std'         => '',
				'type'        => 'select',
				'section'     => 'header',
				'choices'     => array( 
					array(
						'value'       => '',
						'label'       => esc_html__( '-- Choose One --', 'koopo-online' ),
						'src'         => ''
					),
					array(
						'value'       => 'firstname',
						'label'       => esc_html__( 'User\'s First Name', 'koopo-online' ),
						'src'         => ''
					),
					array(
						'value'       => 'lastname',
						'label'       => esc_html__( 'User\'s Last Name', 'koopo-online' ),
						'src'         => ''
					),
					array(
						'value'       => 'username',
						'label'       => esc_html__( 'User\'s UserName', 'koopo-online' ),
						'src'         => ''
					),
					array(
						'value'       => 'custom-text',
						'label'       => esc_html__( 'Custom text', 'koopo-online' ),
						'src'         => ''
					),
				)
			),
			array(
				'id'          => 'login-btn-custom-text',
				'label'       => esc_html__( '\'My Account\' Button Custom Text Content', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'header',
				'condition'   => 'login-btn-content-type:is(custom-text)',
			),
			array(
				'id'          => 'menu-bg-color',
				'label'       => esc_html__( 'Menu BG color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the background color for the menu', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'header',
			),
			array(
				'id'          => 'menu-link-default-color',
				'label'       => esc_html__( 'Menu default link color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the default text color', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'header',
			),
			array(
				'id'          => 'menu-item-current-color',
				'label'       => esc_html__( 'Current page menu item color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the text color to current page menu item', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'header',
			),
			array(
				'id'          => 'menu-item-current-bg-color',
				'label'       => esc_html__( 'Current page menu item background color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the background color to current page menu item', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'header',
			),
			array(
				'id'          => 'menu-item-hover-color',
				'label'       => esc_html__( 'Menu item on-hover color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the text color to menu items on hover', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'header',
			),
			array(
				'id'          => 'menu-item-bg-hover-color',
				'label'       => esc_html__( 'Menu item on-hover background color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the background color to menu items on hover', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'header',
			),
			array(
				'id'          => 'menu-font',
				'label'       => esc_html__( 'Menu text font', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the font size, style and weight for the menu links', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'header',
			),
			array(
				'id'          => 'breadcrumbs',
				'label'       => esc_html__( 'Display BreadCrumbs', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'header',
			),
			array(
				'id'          => 'header_search_form',
				'label'       => esc_html__( 'Header Search Form', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'header',
			),

			array(
				'id'          => 'logged-menu-right-link',
				'label'       => esc_html__( 'Right Menu Link For Logged in users', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'header',
			),
			array(
				'id'          => 'logged-menu-right-link-label',
				'label'       => esc_html__( 'Right Menu link Title', 'koopo-online' ),
				'type'        => 'text',
				'section'     => 'header',
				'condition'   => 'logged-menu-right-link:is(on)',
				'operation'   => 'and'
			),
			array(
				'id'          => 'logged-menu-right-link-to',
				'label'       => esc_html__( 'Link to', 'koopo-online' ),
				'desc'        => esc_html__( 'What does the right menu item link to', 'koopo-online' ),
				'std'         => '',
				'type'        => 'select',
				'section'     => 'header',

				'condition'   => 'logged-menu-right-link:is(on)',
				'choices'     => array(
					array(
					'value'       => '',
					'label'       => '',
					'src'         => ''
					),
					array(
					'value'       => 'add-business',
					'label'       => esc_html__( 'Add New Listing', 'koopo-online' ),
					'src'         => ''
					),
					array(
					'value'       => 'page',
					'label'       => esc_html__( 'Page', 'koopo-online' ),
					'src'         => ''
					),
					array(
					'value'       => 'link',
					'label'       => esc_html__( 'Custom Link', 'koopo-online' ),
					'src'         => ''
					),
				)
			),
			array(
				'id'          => 'logged-menu-right-link-page',
				'section'     => 'header',
				'label'       => esc_html__( 'Select Page', 'koopo-online' ),
				'std'         => '',
				'type'        => 'page-select',
				'condition'   => 'logged-menu-right-link-to:is(page)',
			),
			array(
				'id'          => 'logged-menu-right-link-link',
				'label'       => esc_html__( 'Set Link', 'koopo-online' ),
				'type'        => 'text',
				'section'     => 'header',
				'condition'   => 'logged-menu-right-link-to:is(link),logged-menu-right-link:is(on)',
			),
			/*non logged in*/
			array(
				'id'          => 'non-logged-menu-right-link',
				'label'       => esc_html__( 'Right Menu Link For Non-Logged in users', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'header',
			),
			array(
				'id'          => 'non-logged-menu-right-link-label',
				'label'       => esc_html__( 'Right Menu link Title', 'koopo-online' ),
				'type'        => 'text',
				'section'     => 'header',
				'condition'   => 'non-logged-menu-right-link:is(on)',
				'operation'   => 'and'
			),
			array(
				'id'          => 'non-logged-menu-right-link-to',
				'label'       => esc_html__( 'Link to', 'koopo-online' ),
				'desc'        => esc_html__( 'What does the right menu item link to for non logged in users', 'koopo-online' ),
				'std'         => '',
				'type'        => 'select',
				'section'     => 'header',
				'condition'   => 'non-logged-menu-right-link:is(on)',
				'choices'     => array(
					array(
					'value'       => '',
					'label'       => '',
					'src'         => ''
					),
					array(
					'value'       => 'page',
					'label'       => esc_html__( 'Page', 'koopo-online' ),
					'src'         => ''
					),
					array(
					'value'       => 'link',
					'label'       => esc_html__( 'Custom Link', 'koopo-online' ),
					'src'         => ''
					),
				)
			),
			array(
				'id'          => 'non-logged-menu-right-link-page',
				'section'     => 'header',
				'label'       => esc_html__( 'Select Page', 'koopo-online' ),
				'std'         => '',
				'type'        => 'page-select',
				'condition'   => 'non-logged-menu-right-link-to:is(page)',
			),
			array(
				'id'          => 'non-logged-menu-right-link-link',
				'label'       => esc_html__( 'Set Link', 'koopo-online' ),
				'type'        => 'text',
				'section'     => 'header',
				'condition'   => 'non-logged-menu-right-link-to:is(link),non-logged-menu-right-link:is(on)',
			),
		// ---------------------------------------------------------
		// TYPOGRAPHY OPTIONS .
		// Section: typography.
		// ---------------------------------------------------------
			array(
				'id'          => 'theme-typography-customize',
				'label'       => esc_html__( 'Typography Options', 'koopo-online' ),
				'desc'        => esc_html__( 'Here you can customise the theme\'s typography', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'typography',
			),
			array(
				'id'          => 'wyz-typography',
				'label'       => esc_html__( 'WYZI Typography', 'koopo-online' ),
				'desc'        => esc_html__( 'The Typography of your site', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'typography',
			),
			array(
				'id'          => 'body_google_fonts',
				'label'       => esc_html__( 'WYZI Google Fonts', 'koopo-online' ),
				'desc'        => esc_html__( 'Main font family for the site (Make sure to have a "Google Font API Key" inserted below)', 'koopo-online' ),
				'std'         => '',
				'type'        => 'google-fonts',
				'section'     => 'typography',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => '',
				'condition'   => '',
				'operator'    => 'and'
			),
			array(
				'id'          => 'google_fonts_api_key',
				'label'       => esc_html__( 'Google Font API Key', 'koopo-online' ),
				'desc'        => esc_html__( 'Google API key needed to activate google fonts', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'typography',
			),
			array(
				'id'          => 'h1-typography',
				'label'       => esc_html__( 'H1', 'koopo-online' ),
				'desc'        => esc_html__( 'Header H1 font', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'typography',
			),
			array(
				'id'          => 'h2-typography',
				'label'       => esc_html__( 'H2', 'koopo-online' ),
				'desc'        => esc_html__( 'Header H2 font', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'typography',
			),
			array(
				'id'          => 'h3-typography',
				'label'       => esc_html__( 'H3', 'koopo-online' ),
				'desc'        => esc_html__( 'Header H3 font', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'typography',
			),
			array(
				'id'          => 'h4-typography',
				'label'       => esc_html__( 'H4', 'koopo-online' ),
				'desc'        => esc_html__( 'Header H4 font', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'typography',
			),
			array(
				'id'          => 'h5-typography',
				'label'       => esc_html__( 'H5', 'koopo-online' ),
				'desc'        => esc_html__( 'Header H5 font', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'typography',
			),
			array(
				'id'          => 'h6-typography',
				'label'       => esc_html__( 'H6', 'koopo-online' ),
				'desc'        => esc_html__( 'Header H6 font', 'koopo-online' ),
				'std'         => '',
				'type'        => 'typography',
				'section'     => 'typography',
			),
		// ---------------------------------------------------------
		// FOOTER OPTIONS .
		// Section: footer.
		// ---------------------------------------------------------
			array(
				'id'          => 'footer-customize',
				'label'       => esc_html__( 'Footer Options', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'footer',
			),
			array(
				'id'          => 'footer-widgets-onoff',
				'label'       => esc_html__( 'Enable footer widget area', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'footer',
			),
			array(
				'id'          => 'footer-layout',
				'label'       => esc_html__( 'Number of Footer Columns', 'koopo-online' ),
				'std'         => 'four-columns',
				'type'        => 'radio-image',
				'section'     => 'footer',
				'condition'   => 'footer-widgets-onoff:is(on)',
				'choices'      => array(
					array(
						'value' => 'one-column',
						'label' => esc_html__( 'One Column', 'koopo-online' ),
						'src'   => 'one-column.jpg',
					),
					array(
						'value' => 'two-columns',
						'label' => esc_html__( 'Two Columns', 'koopo-online' ),
						'src'   => 'two-columns.jpg',
					),
					array(
						'value' => 'three-columns',
						'label' => esc_html__( 'Three Columns', 'koopo-online' ),
						'src'   => 'three-columns.jpg',
					),
					array(
						'value' => 'four-columns',
						'label' => esc_html__( 'Four Columns', 'koopo-online' ),
						'src'   => 'four-columns.jpg',
					),
				),
			),
			array(
				'id'          => 'footer-sticky-menu',
				'label'       => esc_html__( 'Footer sticky menu on mobile', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'footer',
			),
			array(
				'id'          => 'footer-sticky-menu-extra-items',
				'label'       => __( 'Extra Items', 'koopo-online' ),
				'desc'        => __( 'Your description', 'koopo-online' ),
				'type'        => 'checkbox',
				'section'     => 'footer',
				'condition'   => 'footer-sticky-menu:is(on)',
				'choices'     => array( 
					array(
						'value'       => 'user-account',
						'label'       => __( 'User Account', 'koopo-online' ),
					),
					array(
						'value'       => 'search',
						'label'       => __( 'Search Field', 'koopo-online' ),
					),
					array(
						'value'       => 'cart',
						'label'       => __( 'Woocommerc Cart', 'koopo-online' ),
					)
				)
			),
			array(
				'id'          => 'footer-copyrights-onoff',
				'label'       => esc_html__( 'Show Copyrights', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'footer',
			),
			array(
				'id'          => 'copyrights-text',
				'label'       => esc_html__( 'Copyright', 'koopo-online' ),
				'desc'        => esc_html__( 'Copyright text. Displays at the left side of the footer.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'footer',
				'condition'   => 'footer-copyrights-onoff:is(on)',
			),
		// ---------------------------------------------------------
		// COLOR OPTIONS .
		// Section: colors.
		// ---------------------------------------------------------
			array(
				'id'          => 'color-customize',
				'label'       => esc_html__( 'Color Options', 'koopo-online' ),
				'desc'        => esc_html__( 'Customise your site\'s color options', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'colors',
			),
			array(
				'id'          => 'primary-color',
				'label'       => esc_html__( 'Site Primary Color', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'colors',
			),
			array(
				'id'          => 'secondary-color',
				'label'       => esc_html__( 'Site Secondary Color', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'colors',
			),
			array(
				'id'          => 'wyz-background',
				'label'       => esc_html__( 'Site Background', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the background color/image of your site', 'koopo-online' ),
				'std'         => '',
				'type'        => 'background',
				'section'     => 'colors',
			),
			array(
				'id'          => 'business-wall-bg-color',
				'label'       => esc_html__( 'Business wall background color', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'colors',
			),
			array(
			'id'          => 'footer-color',
			'label'       => esc_html__( 'Footer background color', 'koopo-online' ),
			'desc'        => '',
			'std'         => '',
			'type'        => 'colorpicker',
			'section'     => 'colors',
			),
		// ---------------------------------------------------------
		// CUSTOM CSS .
		// Section: css-custom.
		// ---------------------------------------------------------
			array(
				'id'          => 'css-customize',
				'label'       => esc_html__( 'Custom CSS', 'koopo-online' ),
				'desc'        => esc_html__( 'Here you can add all your custom css code. This will override the theme\'s current css.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'css-custom',
			),
			array(
				'id'          => 'custom-css',
				'label'       => '',
				'desc'        => '',
				'std'         => '',
				'type'        => 'css',
				'section'     => 'css-custom',
			),
		// ---------------------------------------------------------
		// CUSTOM SCRIPT .
		// Section: script-custom.
		// ---------------------------------------------------------
			array(
				'id'          => 'script-customize',
				'label'       => esc_html__( 'Custom Script', 'koopo-online' ),
				'desc'        => esc_html__( 'Here you can add all your custom javascript code (Dont place your code inside script tags)', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'script-custom',
			),
			array(
				'id'          => 'custom-script',
				'label'       => '',
				'desc'        => '',
				'std'         => '',
				'type'        => 'javascript',
				'section'     => 'script-custom',
				'rows'        => '20',
			),
		// ---------------------------------------------------------
		// ACCESSORIES.
		// Section: accessories.
		// ---------------------------------------------------------
			array(
				'id'          => 'accessories-customize',
				'label'       => esc_html__( 'Accessories Options', 'koopo-online' ),
				'desc'        => esc_html__( 'Here are the options for all the theme\'s accessory features', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'accessories',
			),
			/*array(
				'id'          => 'user-dashboard',
				'label'       => esc_html__( 'User Dashboard', 'koopo-online' ),
				'desc'        => esc_html__( 'Replace current user account page with a user dashboard.', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'accessories',
			),*/
			array(
				'id'          => 'geolocation',
				'label'       => esc_html__( 'Geolocation', 'koopo-online' ),
				'desc'        => esc_html__( 'Enable/Disable tracking user\'s location. This allows users to search for businesses within a specific distance.', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'accessories',
			),
			array(
				'id'          => 'sticky-menu',
				'label'       => esc_html__( 'Sticky Menu', 'koopo-online' ),
				'desc'        => esc_html__( 'Allow menu to stick to top of the page', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'accessories',
			),
			array(
				'id'          => 'page-loader',
				'label'       => esc_html__( 'Page Loader', 'koopo-online' ),
				'desc'        => esc_html__( 'Display an overlay above the screen that fades away when the page loads', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'accessories',
			),
			array(
				'id'          => 'page-loader-color',
				'label'       => esc_html__( 'Page Loader Color', 'koopo-online' ),
				'desc'        => esc_html__( 'Icon color of the page loader', 'koopo-online' ),
				'std'         => '#00aeff',
				'type'        => 'colorpicker',
				'section'     => 'accessories',
				'condition'   => 'page-loader:is(on)',
			),
			array(
				'id'          => 'page-loader-bg',
				'label'       => esc_html__( 'Page Loader BG Color', 'koopo-online' ),
				'desc'        => esc_html__( 'Background color of the page loader', 'koopo-online' ),
				'std'         => '#fff',
				'type'        => 'colorpicker',
				'section'     => 'accessories',
				'condition'   => 'page-loader:is(on)',
			),
			array(
				'id'          => 'scroll-to-top',
				'label'       => esc_html__( 'Scroll To Top', 'koopo-online' ),
				'desc'        => esc_html__( 'Enable/Disable button to scroll to top', 'koopo-online' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'section'     => 'accessories',
			),
			array(
				'id'          => 'scroll-to-top-color',
				'label'       => esc_html__( 'Scroll Icon color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the color of the scroll to top icon', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'accessories',
				'condition'   => 'scroll-to-top:is(on)',
			),
			array(
				'id'          => 'scroll-to-top-bg-color',
				'label'       => esc_html__( 'Scroll Background color', 'koopo-online' ),
				'desc'        => esc_html__( 'Set the background color of the scroll to top icon', 'koopo-online' ),
				'std'         => '',
				'type'        => 'colorpicker',
				'section'     => 'accessories',
				'condition'   => 'scroll-to-top:is(on)',
			),
			array(
				'id'          => 'scroll-to-top-float',
				'label'       => esc_html__( 'Scroll Icon position', 'koopo-online' ),
				'desc'        => esc_html__( 'on which side you want the icon to be.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'select',
				'section'     => 'accessories',
				'condition'   => 'scroll-to-top:is(on)',
				'choices'     => array(
					array(
						'value'       => 'left',
						'label'       => esc_html__( 'Left', 'koopo-online' ),
						'src'         => '',
					),
					array(
						'value'       => 'right',
						'label'       => esc_html__( 'Right', 'koopo-online' ),
						'src'         => '',
					),
				),
			),
			array(
				'id'          => 'disable_pg_metabox',
				'label'       => esc_html__( 'Hide EG/Rev Metaboxes', 'koopo-online' ),
				'desc'        => esc_html__( 'Hide Essential Grid and Rev Slider metaboxes from Screen options in page editor', 'koopo-online' ),
				'type'        => 'on-off',
				'def'		  => 'on',
				'section'     => 'accessories',
				'condition'   => '',
			),
		// ---------------------------------------------------------
		// Social Links OPTIONS.
		// Section: social_links.
		// ---------------------------------------------------------
			array(
				'id'          => 'social-customize',
				'label'       => esc_html__( 'Social Links Options', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'social_twitter',
				'label'       => esc_html__( 'Twitter Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link to your twitter account.<br/>Make sure to have https:// at the beginning of your link.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'social_google-plus',
				'label'       => esc_html__( 'Google+ Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link to your google plus account.<br/>Make sure to have https:// at the beginning of your link.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'social_linkedin',
				'label'       => esc_html__( 'Linkedin Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link to your linkedin account.<br/>Make sure to have https:// at the beginning of your link.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'social_facebook',
				'label'       => esc_html__( 'Facebook Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link to your facebook account.<br/>Make sure to have https:// at the beginning of your link.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'social_youtube-play',
				'label'       => esc_html__( 'Youtube Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link to your Youtube account.<br/>Make sure to have https:// at the beginning of your link.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'social_instagram',
				'label'       => esc_html__( 'Instagram Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link to your Instagram account.<br/>Make sure to have https:// at the beginning of your link.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'social_flickr',
				'label'       => esc_html__( 'Flickr Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link to your Flickr account.<br/>Make sure to have https:// at the beginning of your link.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'social_pinterest-p',
				'label'       => esc_html__( 'Pinterest Link', 'koopo-online' ),
				'desc'        => esc_html__( 'The link to your Pinterest account.<br/>Make sure to have https:// at the beginning of your link.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
			array(
				'id'          => 'businesses_fb_app_ID',
				'label'       => esc_html__( 'FaceBook App ID', 'koopo-online' ),
				'desc'        => esc_html__( 'Your FaceBook App ID, it is required if you want Facebook Like Box functionality.<br/><a target="_blank" href="https://developers.facebook.com/docs/apps/register">How to get a facebook App ID</a>', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'social_links',
			),
		// ---------------------------------------------------------
		// Contact OPTIONS.
		// Section: contact.
		// ---------------------------------------------------------
			array(
				'id'          => 'contact-customize',
				'label'       => esc_html__( 'Contact Options', 'koopo-online' ),
				'desc'        => '',
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'contact',
			),
			array(
				'id'          => 'contact-email',
				'label'       => esc_html__( 'Contact Email', 'koopo-online' ),
				'desc'        => esc_html__( 'The email to send contact messages to, admin email is default', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'contact-number',
				'label'       => esc_html__( 'Contact Phone Number', 'koopo-online' ),
				'desc'        => esc_html__( 'The phone number that displays in the top social-links bar', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'user-greeting-mail-subject',
				'label'       => esc_html__( 'Client Signup Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to users upon signup.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'user-greeting-mail',
				'label'       => esc_html__( 'Client Signup Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to users upon signup.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %USERNAME%,%EMAIL%,%FIRSTNAME%,%LASTNAME%,%SUBSCRIBTION%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => 'Hello %FIRSTNAME%%LASTNAME%,<br/>You are successfully registered.<br/>Your Email: %EMAIL%<br/>Your Subscription Status: %SUBSCRIBTION%<br/>Thank you<br/>',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'verify-mail-subject',
				'label'       => esc_html__( 'Client Verification Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email sent to users for email verification.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'verify-mail',
				'label'       => esc_html__( 'Client Verification Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email sent to users for email verification.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %LINK%, , %LASTNAME%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => '%FIRSTNAME%, Follow the following link to complete your registration: %LINK%',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'password-reset-subject',
				'label'       => esc_html__( 'Password Reset Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email sent to users  when they forget their password.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'password-reset-mail',
				'label'       => esc_html__( 'Password Reset Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email sent to users when they forget their password.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %USERNAME%, %FIRSTNAME%, %LASTNAME%, %SITE_NAME%, %LOGIN_LINK%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => 'Follow the following link to complete your registration: %LOGIN_LINK%',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'business-owner-greeting-mail-subject',
				'label'       => esc_html__( 'Business Owner Signup Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to business owners upon business registration.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'business-owner-greeting-mail',
				'label'       => esc_html__( 'Business Owner Signup Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to business owners upon business registration.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %USERNAME%, %FIRSTNAME%, %LASTNAME%, %EMAIL%, %SUBSCRIBTION%,', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => 'Hello %FIRSTNAME%%LASTNAME%,<br/>You are successfully registered.<br/>Your Email: %EMAIL%<br/>Your Subscription Status: %SUBSCRIBTION%<br/>Thank you<br/>',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'admin-noice-new-business-email-subject',
				'label'       => esc_html__( 'New Business Submission Admin Notice Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to the admin upon new business registration.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'admin-noice-new-business-email',
				'label'       => esc_html__( 'New Business Submission Admin Notice Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to the admin upon new business registration.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %USERNAME%, %BUSINESSNAME%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => 'New Business submission from user: %USERNAME%, titled: %BUSINESSNAME%.<br/>',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'business-contact-mail-subject',
				'label'       => esc_html__( 'Business Contact Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to business owners upon Contact.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'business-contact-mail',
				'label'       => esc_html__( 'Business Contact Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to business owners upon Contact.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %NAME%,%EMAIL%,%PHONE%,%MESSAGE%, %BUSINESSNAME%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'		  => 'From %BUSINESSNAME%<br/>New contact email from %NAME%, %EMAIL%:<br/><br/>%MESSAGE%',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'business-publish-confirmation-email-subject',
				'label'       => esc_html__( 'Business Publish Confirmation Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to business owners upon their businesses being approved and published.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'business-publish-confirmation-email',
				'label'       => esc_html__( 'Business Publish Confirmation Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to business owners upon their businesses being approved and published.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %FIRST_NAME%, %LAST_NAME%, %BUSINESSNAME%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'		  => 'Dear %FIRST_NAME%, your business: %BUSINESSNAME% has been approved and published<br/>Thank you',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'claim-mail-subject',
				'label'       => esc_html__( 'Claim Notice Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to the admin once a new business claim request has been made.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'claim-mail',
				'label'       => esc_html__( 'Claim Notice Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to the admin once a new business claim request has been made.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %USERNAME%,%BUSINESS_NAME%,%BUSINESS_LINK%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'		  => 'You have a new claim request from user: %USERNAME%.',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'private-messaging-mail-subject',
				'label'       => esc_html__( 'Private Messaging Notice Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to private message receiver <br> Keywords: %SENDERUSERNAME% - Sender User Name <br> %RECEIVERUSERNAME% - Receiver User Name', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'private-messaging-mail',
				'label'       => esc_html__( 'Private Messaging Notice Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to private messaging receiver', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %reciever_user_name%,%sender_user_name%, %business_url%, %message_link%, %message_content%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'		  => 'Hello %reciever_user_name% <br> You have a new Private Message from  %sender_user_name% <br> Click here to view the message %message_link%',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'rate-reminder-mail-onoff',
				'label'       => esc_html__( 'Rate Reminder Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'This email will be sent after the approved appointment date by 2 days.', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'contact',
			),
			array(
				'id'          => 'rate-reminder-mail-subject',
				'label'       => esc_html__( 'Rate Reminder Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to users reminding them to rate a business that they\'ve booked before.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
				'condition'   => 'rate-reminder-mail-onoff:is(on)'
			),
			array(
				'id'          => 'rate-reminder-mail',
				'label'       => esc_html__( 'Rate Reminder Mail Format', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to users reminding them to rate a business that they\'ve booked before.', 'koopo-online' ) . '<br/>' . 'Keywords: %BUSINESSNAME%, %URL%',
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'		  => 'Please consider rating %BUSINESSNAME% by following the link: %URL%.',
				'class'		  => 'fullwidth',
				'condition'   => 'rate-reminder-mail-onoff:is(on)'
			),
			array(
				'id'          => 'new-wall-post-mail-onoff',
				'label'       => esc_html__( 'Favourite business wall post Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'This email will be sent to the users whenever their favourite business posts a new wall post.', 'koopo-online' ),
				'std'         => 'off',
				'type'        => 'on-off',
				'section'     => 'contact',
			),
			array(
				'id'          => 'new-wall-post-email-subject',
				'label'       => esc_html__( 'New Favourite Business Wall Post Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to the users whenever their favourite business posts a new wall post.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
				'condition'   => 'new-wall-post-mail-onoff:is(on)'
			),
			array(
				'id'          => 'new-wall-post-email',
				'label'       => esc_html__( 'New Favourite Business Wall Post Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to the users whenever their favourite business posts a new wall post.', 'koopo-online' ) . '<br/>' . esc_html__( 'Keywords: %USERNAME%, %NEW_WALL_POSTS%', 'koopo-online' ),
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => 'Dear %USERNAME%, here are the latest posts from your favourite businesses:<br> %NEW_WALL_POSTS%',
				'class'		  => 'fullwidth',
				'condition'   => 'new-wall-post-mail-onoff:is(on)'
			),


			array(
				'id'          => 'new-chat-service-started-email-subject',
				'label'       => esc_html__( 'Chat service started Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to both parties (vendor and client) once a service was started on the private messaging system', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'new-chat-service-started-email',
				'label'       => esc_html__( 'Chat service started Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to both parties (vendor and client) once a service was started on the private messaging system', 'koopo-online' ) . '<br/>Keywords: %FIRST_NAME%, %LAST_NAME%, %SERVICE_INFO%',
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => 'Service started %SERVICE_INFO%',
				'class'		  => 'fullwidth',
			),

			/*array(
				'id'          => 'new-chat-service-expired-email-subject',
				'label'       => esc_html__( 'Chat service expired Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to both parties (vendor and client) once a service period is over.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'new-chat-service-expired-email',
				'label'       => esc_html__( 'Chat service expired Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to both parties (vendor and client) once the period of a service on the private messaging system is over.', 'koopo-online' ) . '<br/>Keywords: %FIRST_NAME%, %LAST_NAME%, %SERVICE_INFO%',
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => 'Service expired %SERVICE_INFO%',
				'class'		  => 'fullwidth',
			),*/

			array(
				'id'          => 'new-chat-service-approved-email-subject',
				'label'       => esc_html__( 'Chat service approved Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to both parties (vendor and client) once a service is approved.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'new-chat-service-approved-email',
				'label'       => esc_html__( 'Chat service approved Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to both parties (vendor and client) once a service on the private messaging system is approved.', 'koopo-online' ) . '<br/>Keywords: %FIRST_NAME%, %LAST_NAME%, %SERVICE_INFO%',
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => 'Service approved %SERVICE_INFO%',
				'class'		  => 'fullwidth',
			),
			array(
				'id'          => 'business-owner-appointment-cancel-email-subject',
				'label'       => esc_html__( 'Appointment Cancelation Mail Subject', 'koopo-online' ),
				'desc'        => esc_html__( 'The subject of the email to send to the business owner when a client cancels an email', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => 'contact',
			),
			array(
				'id'          => 'business-owner-appointment-cancel-email',
				'label'       => esc_html__( 'Appointment Cancelation Mail', 'koopo-online' ),
				'desc'        => esc_html__( 'The format of the email to send to the business owner when a client cancels an email', 'koopo-online' ) . '<br/>Keywords: %FIRST_NAME%, %LAST_NAME%, %CLIENT_FIRST_NAME%, %CLIENT_LAST_NAME%, %BUSINESS_NAME%, %APPOINTMENT_DATE%',
				'type'        => 'textarea',
				'section'     => 'contact',
				'std'         => '',
				'class'		  => 'fullwidth',
			),
		// ---------------------------------------------------------
		// Default Images OPTIONS.
		// Section: default-images.
		// ---------------------------------------------------------
			array(
				'id'          => 'def-imgs-lbl',
				'label'       => esc_html__( 'Default Images', 'koopo-online' ),
				'desc'        => esc_html__( 'Customise your Default Images', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => 'default-images',
			),
			array(
				'id'          => 'default-business-logo',
				'label'       => esc_html__( 'Default Business Logo', 'koopo-online' ),
				'desc'        => esc_html__( 'Image to display in case a business doesn\'t have a logo', 'koopo-online'),
				'type'        => 'upload',
				'section'     => 'default-images',
			),
			array(
				'id'          => 'default-business-banner',
				'label'       => esc_html__( 'Default Business Banner', 'koopo-online' ),
				'desc'        => esc_html__( 'Image to display in case a business doesn\'t have a banner', 'koopo-online'),
				'type'        => 'upload',
				'section'     => 'default-images',
			),
			array(
				'id'          => 'default-offer-logo',
				'label'       => esc_html__( 'Default Offer Image', 'koopo-online' ),
				'desc'        => esc_html__( 'Image to display in case an Offer doesn\'t have a featured image', 'koopo-online'),
				'type'        => 'upload',
				'section'     => 'default-images',
			),
			array(
				'id'          => 'default-location-logo',
				'label'       => esc_html__( 'Default Location Image', 'koopo-online' ),
				'desc'        => esc_html__( 'Image to display in case a location doesn\'t have a featured Image', 'koopo-online'),
				'type'        => 'upload',
				'section'     => 'default-images',
			),
		// ---------------------------------------------------------
		// 404 OPTIONS.
		// Section: 404.
		// ---------------------------------------------------------
			array(
				'id'          => '404-customize',
				'label'       => esc_html__( '404 Page Options', 'koopo-online' ),
				'desc'        => esc_html__( 'Customise your 404 page', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textblock-titled',
				'section'     => '404',
			),
			array(
				'id'          => '404_title',
				'label'       => esc_html__( 'Title', 'koopo-online' ),
				'desc'        => esc_html__( 'The title of the 404 page.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'text',
				'section'     => '404',
			),
			array(
				'id'          => '404_textarea',
				'label'       => esc_html__( 'Page Content', 'koopo-online' ),
				'desc'        => esc_html__( 'The content to display in the 404 page.', 'koopo-online' ),
				'std'         => '',
				'type'        => 'textarea',
				'section'     => '404',
				'rows'        => '15',
			),
		),
	);

	if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
		array_push( $custom_settings['settings'],
			array(
				'id'          => 'favicon-upload',
				'label'       => esc_html__( 'Upload Favicon', 'koopo-online' ),
				'desc'        => sprintf( esc_html__( 'Upload the favicon of the site, recommended dimensions 16x16 and .ico extension', 'koopo-online' ), apply_filters( 'ot_upload_text', esc_html__( 'Choose Favicon', 'koopo-online' ) ), 'FTP' ),
				'std'         => '',
				'type'        => 'upload',
				'section'     => 'general',
			)
		);
	}
	// Allow settings to be filtered before saving.
	$custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );

	// Settings are not the same update the DB.
	if ( $saved_settings !== $custom_settings ) {
		update_option( ot_settings_id(), $custom_settings );
	}

	// Lets OptionTree know the UI Builder is being overridden.
	global $ot_has_custom_theme_options;
	$ot_has_custom_theme_options = true;
}

/**
 * Register our meta boxes using the ot_register_meta_box() function.
 */
if ( function_exists( 'ot_register_meta_box' ) ) {
	ot_register_meta_box( $custom_settings );
}

/**
 * Style the theme options admin page.
 */
function wyz_theme_options_js() {
	if ( function_exists( 'ot_get_option' ) ) {
		if ( ! wp_script_is( 'wyz-options-js', 'registered' ) || ! wp_style_is( 'wyz-options-style', 'registered' ) ) {
			wp_register_script( 'wyz-options-js',  '/wp-content/plugins/koopo-custom-blocks/assets/js/candy-admin.min.js' );
			wp_register_style( 'wyz-options-style', '/wp-content/plugins/koopo-custom-blocks/assets/css/candy-admin.css' );
		}
		global $pagenow;

		if ( filter_input( INPUT_GET , 'page' ) && 'ot-theme-options' === filter_input( INPUT_GET , 'page' ) && ( ! wp_script_is( 'wyz-options-js', 'enqueued' ) || ! wp_style_is( 'wyz-options-style', 'enqueued' ) ) ) {
			wp_enqueue_script( 'wyz-options-js' );
			wp_enqueue_style( 'wyz-options-style' );
		}
	}
}
add_action( 'wp_print_scripts', 'wyz_theme_options_js' ,5 );
?>
