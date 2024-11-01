<?php

/*-------------------------------------------
*  Exit if accessed directly
*-------------------------------------------*/
defined( 'ABSPATH' ) || exit;




if ( ! class_exists( 'WDAAUTOS_Admin_Menu' ) ) {
	class WDAAUTOS_Admin_Menu extends WDAAUTOS_Callback {
        
		public function __construct () {
			add_action( 'admin_menu', [ $this, 'admin_menu_options' ] );
            add_action( 'admin_init', [ $this, 'field_settings' ] );
		}

		

		public function admin_menu_options () {
            add_menu_page(
                'Automated Settings Page',          // $page_title
                'Automated Woo',                    // $menu_title
                'manage_options',                   // $capability
                'wdaautos-automated-actions',         // $menu_slug
                [ $this, 'callback_admin_menu' ],   // $callback
                'dashicons-marker',                 // $dashicon
                30                                  // $position
            );
		}


        public function field_settings() {
            
            
            /*----- PAGE : Settings -----*/
            
            add_settings_section (
                'wdaautos_settings',                                          // $id
                __( 'Visibility Section', 'wda-automated-store' ),    // $title
                [ $this, 'callback_section' ],                              // $callback
                'wdaautos-automated-actions',                                 // $slug
                [                                                           // $custom_args
                    'before_section' => '',
                    'after_section' => '',
                    'section_class' => '',
                ]
            );


            // Visibility Switch
            
            register_setting ( 'wdaautos_settings', 'wdaautos_visibility_switch', [
                'type'				=> 'string',
                'sanitize_callback'	=> 'sanitize_text_field',
                'default'			=> NULL
            ]);
            
            add_settings_field (
                'wdaautos_visibility_switch',
                __( 'Enable/Disable', 'wda-automated-store' ),
                [ $this, 'callback_field' ],
                'wdaautos-automated-actions',
                'wdaautos_settings',
                array(
                    'type'			=> 'checkbox',
                    'option_group'	=> 'wdaautos_settings',
                    'option_name'	=> 'wdaautos_visibility_switch',
                    'label_for'		=> 'wdaautos_visibility_switch',
                    'class'			=> 'wdaautos_visibility_switch',
                    'options'       => [ 'wdaautos_visibility_switch' => 'Enable' ],
                    'description'	=> __( 'Turn on/off Visibility coversion.', 'wda-automated-store' ),
                )
            );


            // Visibility Status

            register_setting ( 'wdaautos_settings', 'wdaautos_visibility_status', [
                'type'				=> 'string',
                'sanitize_callback'	=> 'sanitize_text_field',
                'default'			=> NULL
            ]);
            
            add_settings_field (
                'wdaautos_visibility_status',
                __( 'Status', 'wda-automated-store' ),
                [ $this, 'callback_field' ],
                'wdaautos-automated-actions',
                'wdaautos_settings',
                array(
                    'type'			=> 'select',
                    'option_group'	=> 'wdaautos_settings',
                    'option_name'	=> 'wdaautos_visibility_status',
                    'label_for'		=> 'wdaautos_visibility_status',
                    'class'			=> 'wdaautos_visibility_status',
                    'options'       => [
                        'visible' => 'Catalog & Search',
                        'catalog' => 'Catalog Only',
                        'search'  => 'Search Only',
                        'hidden'  => 'Hidden'
                    ],
                    'description'	=> __( 'Select the visibility status that you want when products become out-of-stock.', 'wda-automated-store' ),
                )
            );


            // Conversion Delay
            
            register_setting ( 'wdaautos_settings', 'wdaautos_conversion_delay', [
                'type'				=> 'string',
                'sanitize_callback'	=> 'sanitize_text_field',
                'default'			=> NULL
            ]);
            
            add_settings_field (
                'wdaautos_conversion_delay',
                __( 'Conversion Delay (hours)', 'wda-automated-store' ),
                [ $this, 'callback_field' ],
                'wdaautos-automated-actions',
                'wdaautos_settings',
                array(
                    'type'			=> 'number',
                    'option_group'	=> 'wdaautos_settings',
                    'option_name'	=> 'wdaautos_conversion_delay',
                    'label_for'		=> 'wdaautos_conversion_delay',
                    'class'			=> 'wdaautos_conversion_delay',
                    'value'         => 72,
                    'description'	=> __( 'How long would you like to wait after a products become out-of-stock?', 'wda-automated-store' ),
                )
            );


            // Conversion Moment
            
            register_setting ( 'wdaautos_settings', 'wdaautos_conversion_moment', [
                'type'				=> 'string',
                'sanitize_callback'	=> 'sanitize_text_field',
                'default'			=> NULL
            ]);
            
            add_settings_field (
                'wdaautos_conversion_moment',
                __( 'Conversion Moment', 'wda-automated-store' ),
                [ $this, 'callback_field' ],
                'wdaautos-automated-actions',
                'wdaautos_settings',
                array(
                    'type'			=> 'time',
                    'option_group'	=> 'wdaautos_settings',
                    'option_name'	=> 'wdaautos_conversion_moment',
                    'label_for'		=> 'wdaautos_conversion_moment',
                    'class'			=> 'wdaautos_conversion_moment',
                    'description'	=> __( 'Set specific time in a day to perform conversions so that your website does not overload.', 'wda-automated-store' ),
                )
            );

            /*----- ENDS PAGE : Settings -----*/
        }
        

	}


    new WDAAUTOS_Admin_Menu();
}