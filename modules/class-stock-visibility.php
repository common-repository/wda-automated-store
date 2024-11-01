<?php


if ( ! class_exists( 'WDAAUTOS_Stock_Visibility' ) ) :


class WDAAUTOS_Stock_Visibility {

    private $visibility_switch;
    private $visibility_status;
    private $delay_hours;
    private $conversion_moment;

    private $terms;
    private $translation;

    public function __construct() {
        $this->visibility_switch = get_option('wdaautos_visibility_switch', 'yes');

        if ( $this->visibility_switch === 'yes' ) :

        $this->term_translation = [
            'exclude-from-search' => 'exclude_from_search_id',
            'exclude-from-catalog' => 'exclude_from_catalog_id'
        ];
        $this->translation      = [
            'catalog' => 'exclude-from-search',
            'search'  => 'exclude-from-catalog',
        ];
        $this->visibility_status = get_option('wdaautos_visibility_status', 'search');
        $this->delay_hours       = get_option('wdaautos_conversion_delay', 24);
        $this->conversion_moment = get_option('wdaautos_conversion_moment', '01:00');


        add_action( 'woocommerce_product_set_stock_status', [ $this, 'stock_status' ], 25, 3 );


        $initial_settings = json_decode( get_option('wdaautos_initial_settings', '{}'), true );

        $this->terms = count( $initial_settings ) ? $initial_settings['terms'] : ['terms' => [
            'exclude_from_catalog_id' => 0,
            'exclude_from_search_id' => 0
        ]];
        


        // Get current time as a DateTimeImmutable object
        $datetime_object = current_datetime();
        
        
        
        /*-----------------------------------------------------------------------
        * Get correctly formatted current time string
        -----------------------------------------------------------------------*/
        $current_time = $datetime_object->format('Y-m-d H:i:s');
        

        /*-----------------------------------------------------------------------
        * Convert current time string into DateTime object
        * Then deduct with delayed hours. Meaning how long a product should
        * wait (before changing visibility) after going out-of-stock.
        -----------------------------------------------------------------------*/
        $delayed_time_obj = new DateTime( $current_time );
        $delayed_time_obj->modify('-' . $this->delay_hours . ' hours');
        

        // Adding conversion moment
        $starting_point = $datetime_object->format( 'Y-m-d' ) . ' ' . $this->conversion_moment;
        $starting_point_obj = new DateTime( $starting_point ); // Convert specific time to a DateTime object


        $ending_point_obj = new DateTime( $starting_point );
        $ending_point_obj->modify('+1 hours');
        
        if ( $delayed_time_obj > $starting_point_obj && $delayed_time_obj < $ending_point_obj  ) {
            $this->check_validation();
        }

        endif;
    }


    public function stock_status( $product_id, $stock_status, $product ) {
        $product = wc_get_product( $product_id );

        $stock_out_date = $product->get_meta('wdaautos_outofstock_date');
        
        if ( 'instock' === $stock_status && $stock_out_date ) {
            
            // Product visibility : Possible values are
            // visible (Catalog & Search)
            // catalog (Catalog only)
            // search (Search only)
            // hidden (nowhere)

            $product->set_catalog_visibility( 'visible' );
            $product->delete_meta_data( 'wdaautos_outofstock_date' );
            
        } else if ( 'outofstock' === $stock_status && !$stock_out_date ) {
            $product->update_meta_data( 'wdaautos_outofstock_date', current_datetime()->format( 'Y-m-d H:i:s' ) );
        }

        $product->save(); 
    }


    public function check_validation () {
        global $wpdb;
        

        $outofstock_products = wp_cache_get( 'wdaautos_outofstock_products' );

        if ( $outofstock_products === false ) {

            $outofstock_products = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM %i WHERE meta_key = %s;",
                [ $wpdb->prefix . 'postmeta', 'wdaautos_outofstock_date' ]
            ));

            wp_cache_set( 'wdaautos_outofstock_products', $outofstock_products );
        }


        if ( count( $outofstock_products ) ) :
            $datetime_object = current_datetime();
            $current_time = $datetime_object->format('Y-m-d H:i:s');
    
            // Convert both to DateTime objects for comparison
            $current_time_obj = new DateTime($current_time);
    
            foreach ( $outofstock_products as $object ) {
                $specific_time_obj = new DateTime( $object->meta_value );
    
                // Compare the two DateTime objects
                if ( $current_time_obj > $specific_time_obj ) {
                    $this->update_visibility( $wpdb, 'hidden', $object->post_id );
                }
            }
        endif;
    }


    private function update_visibility ( $wpdb, $status_key, $product_id ) {
        switch ( $status_key ) {
            case 'visible':
                $this->remove_term($wpdb, $product_id, 'catalog');
                $this->remove_term($wpdb, $product_id, 'search');
                break;

            case 'catalog':
                $this->add_term($wpdb, $product_id, 'catalog');
                $this->remove_term($wpdb, $product_id, 'search');
                break;

            case 'search':
                $this->add_term($wpdb, $product_id, 'search');
                $this->remove_term($wpdb, $product_id, 'catalog');
                break;

            case 'hidden':
                $this->add_term($wpdb, $product_id, 'search');
                $this->add_term($wpdb, $product_id, 'catalog');
                break;
        }
    }


    private function add_term ($wpdb, $product_id, $key) {
        $wpdb->insert(
            $wpdb->prefix . 'term_relationships',
            [
                'object_id' => $product_id,
                'term_taxonomy_id' => $this->term_id( $key ),
            ],
            ['%d', '%d',]
        );
    }


    private function remove_term ($wpdb, $product_id, $key) {
        // Cache key for the term taxonomy ID
        $cache_key = 'term_taxonomy_id_' . $key;
        $term_taxonomy_id = wp_cache_get( $cache_key );

        if ( $term_taxonomy_id === false ) {
            // If the cache is empty, retrieve the term_taxonomy_id
            $term_taxonomy_id = $this->term_id( $key );

            // Set the retrieved value to cache
            wp_cache_set( $cache_key, $term_taxonomy_id );
        }

        // Proceed to delete the term relationship from the database
        $deleted = $wpdb->delete(
            $wpdb->prefix . 'term_relationships',
            [
                'object_id' => $product_id,
                'term_taxonomy_id' => $term_taxonomy_id
            ],
            ['%d', '%d']
        );

        if ( $deleted ) {
            // Clear the cache for the term taxonomy ID after deletion
            wp_cache_delete( $cache_key );
        }
    }

    private function term_id ( $status_key ) {
        return $this->terms[ $this->term_translation[$this->translation[ $status_key ]] ];
    }
    
}

new WDAAUTOS_Stock_Visibility();


endif;