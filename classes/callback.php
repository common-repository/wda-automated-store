<?php

/*-------------------------------------------
*  Exit if accessed directly
*-------------------------------------------*/
defined( 'ABSPATH' ) || exit;




if ( ! class_exists('WDAAUTOS_Callback') && class_exists('WDAAUTOS_Field_Templates') ) :

class WDAAUTOS_Callback extends WDAAUTOS_Field_Templates {

    public function callback_admin_menu () {
        $this->settings_template (
            get_admin_page_title(),
            'wdaautos_settings',
            'wdaautos-automated-actions',
            [
                'label' => 'Save Settings',
                'id'	=> 'wdaautos-settings'
            ]
        );
    }
        
    // public function callback_license () {
    //     $this->settings_template (
    //         get_admin_page_title(),
    //         'wdaautos_plugin_license',
    //         'wdaautos-plugin-license',
    //         [
    //             'label' => 'Save Settings',
    //             'id'	=> 'wdaautos-license'
    //         ]
    //     );
    // }

    // SETTINGS PAGE

    public function callback_section () {}

    public function callback_field ( $args ) {
        $this->render_form( $args );
    }

}


endif;