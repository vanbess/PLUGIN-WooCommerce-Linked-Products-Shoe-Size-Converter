<?php

defined('ABSPATH') ?: exit();

if (!trait_exists('SS_Product_Single')) :

    trait SS_Product_Single {

        /**
         * Add auto/manual shoe size conversion to product single
         * Auto loads best case size by IP/Country
         * Adds abilitity to load correct sizes
         *
         * @return void
         */
        public static function ss_product_single() {

            global $post;

            // retrieve saved sizes
            $us_s = maybe_unserialize(get_post_meta($post->ID, 'ss_us_data', true));
            $uk_s = maybe_unserialize(get_post_meta($post->ID, 'ss_uk_data', true));
            $jp_s = maybe_unserialize(get_post_meta($post->ID, 'ss_jp_data', true));

            // setup shoe size => country codes array
            $country_ssizes = [
                'EU' => ['BE', 'BG', 'CZ', 'DK', 'DE', 'EE', 'IE', 'EL', 'ES', 'FR', 'HR', 'IT', 'CY', 'LV', 'LT', 'LU', 'HU', 'MT', 'NL', 'AT', 'PL', 'PT', 'RO', 'SI', 'SK', 'FI', 'SE'],
                'US' => ['US', 'CA', 'AU', 'NZ'],
                'UK' => ['GB', 'ZA'],
                'JP' => ['JP', 'RU', 'KP', 'CN', 'TW']
            ];

            // retrieve user's current country
            $user_ip = (!empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

            echo $user_ip;


            // bail if no sizes defined
            if (!$us_s || !$uk_s || $jp_s) :
                return;
            endif;
        }

        /**
         * Shoe size JS and CSS
         *
         * @return void
         */
        public static function ss_product_single_js_css() { ?>

            <?php if (is_product()) : ?>

                <script>
                    jQuery(document).ready(function($) {

                        var to_append = '<span id="ss_btns_cont">';
                        to_append += '<button id="ss_eu">EU</button>';
                        to_append += '<button id="ss_us">US</button>';
                        to_append += '<button id="ss_gb">UK</button>';
                        to_append += '<button id="ss_jp">JP</button>';
                        to_append += '</span>';

                        $('.product-variations.list-type.pa_size').append(to_append);

                        console.log('wtf');



                    });
                </script>

                <style>
                    span#ss_btns_cont {
                        display: inline;
                        border: none;
                        padding: 0;
                    }

                    span#ss_btns_cont>button {
                        margin-right: 12.8px;
                        border: 1px solid #313438;
                        background: #313438;
                        color: white;
                        transition: 0.2s;
                        cursor: pointer;
                    }

                    span#ss_btns_cont:hover {
                        border: none;
                        box-shadow: none;
                    }

                    span#ss_btns_cont>button:hover {
                        transition: 0.2s;
                        background: var(--rio-primary-color, #27c);
                        border-color: var(--rio-primary-color, #27c);
                    }

                    .ss_active {
                        background: var(--rio-primary-color, #27c);
                        border-color: var(--rio-primary-color, #27c);
                    }
                </style>

            <?php endif; ?>

<?php }
    }

endif;
