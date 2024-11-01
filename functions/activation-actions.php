<?php


/*-------------------------------------------
*  Exit if accessed directly
*-------------------------------------------*/
defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'wdaautos_activation_actions' ) ) {
    function wdaautos_activation_actions() {
        $exclude_from_catalog_id = wp_cache_get( 'wdaautos_catalog_cache_key' );
        $exclude_from_search_id = wp_cache_get( 'wdaautos_search_cache_key' );


        if ( $exclude_from_catalog_id == false && $exclude_from_search_id == false ) {


            global $wpdb;
            $table_terms    = $wpdb->prefix . 'terms';

            if ( $exclude_from_catalog_id == false ) {
                $exclude_from_catalog_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT term_id FROM %i WHERE slug = %s;",
                    [ $table_terms, 'exclude-from-catalog' ]
                ));

                wp_cache_set( 'wdaautos_catalog_cache_key', $exclude_from_catalog_id );
            }

            if ( $exclude_from_search_id == false ) {
                $exclude_from_search_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT term_id FROM %i WHERE slug = %s;",
                    [ $table_terms, 'exclude-from-search' ]
                ));

                wp_cache_set( 'wdaautos_search_cache_key', $exclude_from_search_id );
            }


        }
        
        add_option('wdaautos_initial_settings', wp_json_encode([
            'terms' => [
                'exclude_from_catalog_id' => $exclude_from_catalog_id,
                'exclude_from_search_id' => $exclude_from_search_id
            ]
        ]));
    }
}