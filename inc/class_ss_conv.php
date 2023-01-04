<?php

defined('ABSPATH') ?: exit();

if (!class_exists('SS_Conv')) :

    // Traits
    include __DIR__ . '/traits/ss_conv_tab.php';
    include __DIR__ . '/traits/ss_save_data.php';
    include __DIR__ . '/traits/ss_save_data_js.php';
    include __DIR__ . '/traits/ss_product_single.php';

    class SS_Conv {

        // traits
        use SS_Conv_Tab,
            SS_Save_Data,
            SS_Save_Data_JS,
            SS_Product_Single;

        /**
         * Construct
         */
        public function __construct() {

            // add backend product tab
            add_filter('woocommerce_product_data_tabs', function ($tabs) {

                $tabs['shoe_size_conv'] = [
                    'label'    => __('Shoe Size Conversion', 'woocommerce'),
                    'target'   => 'shoe_size_conv_tab',
                    'priority' => 99,
                    'class'    => 'show_if_variable'
                ];

                return $tabs;
            });

            // render backend product tab
            add_action('woocommerce_product_data_panels', [__CLASS__, 'ss_conv_tab']);

            // save backend size data via AJAX
            add_action('wp_ajax_nopriv_ss_save_data', [__CLASS__, 'ss_save_data']);
            add_action('wp_ajax_ss_save_data', [__CLASS__, 'ss_save_data']);

            // JS to save backend size data AJAX
            add_action('admin_footer', [__CLASS__, 'ss_save_data_js']);

            // insert shoe size functionality
            add_action('woocommerce_before_variations_form', [__CLASS__, 'ss_product_single']);

            // add shoe size CSS and JS to footer for product single
            add_action('wp_footer', [__CLASS__, 'ss_product_single_js_css']);
        }
    }

    new SS_Conv;

endif;
