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

            // check if enabled; bail if false/off
            if (get_post_meta($post->ID, 'ss_enabled', true) === 'off') :
                return;
            endif;

            // retrieve saved sizes
            $eu_s = maybe_unserialize(get_post_meta($post->ID, 'ss_eu_data', true));
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
            $user_ip      = (!empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $user_loc     = new WC_Geolocation();
            $user_country = $user_loc->geolocate_ip($user_ip)['country'];

            // set default size designation (always EU, in case country not in $country_ssizes list)
            $def_size_desig = 'EU';

            // check country against $country_ssizes to get default/active display sizes
            foreach ($country_ssizes as $size_designation => $countries) :
                if (in_array($user_country, $countries)) :
                    $def_size_desig = $size_designation;
                endif;
            endforeach;

            // hidden inputs to ref default size setting etc
?>
            <input type="hidden" id="ss_size_desig" value="<?php echo $def_size_desig; ?>">
            <input type="hidden" id="ss_eu_i" value="<?php echo base64_encode(json_encode($eu_s)) ?>">
            <input type="hidden" id="ss_us_i" value="<?php echo base64_encode(json_encode($us_s)) ?>">
            <input type="hidden" id="ss_uk_i" value="<?php echo base64_encode(json_encode($uk_s)) ?>">
            <input type="hidden" id="ss_jp_i" value="<?php echo base64_encode(json_encode($jp_s)) ?>">

        <?php
        }

        /**
         * Shoe size JS and CSS
         *
         * @return void
         */
        public static function ss_product_single_js_css() { ?>

            <?php if (is_product()) :

                global $post;

                // check if enabled; bail if false/off
                if (get_post_meta($post->ID, 'ss_enabled', true) === 'off') :
                    return;
                endif;

            ?>

                <script>
                    jQuery(document).ready(function($) {

                        // insert size buttons
                        var to_append = '<span id="ss_btns_cont">';
                        to_append += '<button class="ss" id="ss_eu">EU</button>';
                        to_append += '<button class="ss" id="ss_us">US</button>';
                        to_append += '<button class="ss" id="ss_gb">UK</button>';
                        to_append += '<button class="ss" id="ss_jp">JP</button>';
                        to_append += '</span>';

                        $('.product-variations.list-type.pa_size').prepend(to_append);

                        // retrieve all size settings
                        var eu_s = JSON.parse(atob($('#ss_eu_i').val()));
                        var us_s = JSON.parse(atob($('#ss_us_i').val()));
                        var uk_s = JSON.parse(atob($('#ss_uk_i').val()));
                        var jp_s = JSON.parse(atob($('#ss_jp_i').val()));

                        // set default/selected sizes
                        var def_setting = $('#ss_size_desig').val();

                        $('.ss').each(function(i, el) {
                            if ($(el).text() === def_setting) {
                                $(el).addClass('ss_active');
                            }
                        });

                        // set EU sizes
                        if (def_setting === 'EU') {
                            $('.product-variations.list-type.pa_size > button').each(function(i, el) {
                                $(el).text(eu_s[i]);
                            })
                        }

                        // set UK sizes
                        if (def_setting === 'UK') {
                            $('.product-variations.list-type.pa_size > button').each(function(i, el) {
                                $(el).text(uk_s[i]);
                            })
                        }

                        // set US sizes
                        if (def_setting === 'US') {
                            $('.product-variations.list-type.pa_size > button').each(function(i, el) {
                                $(el).text(us_s[i]);
                            })
                        }

                        // set JP sizes
                        if (def_setting === 'JP') {
                            $('.product-variations.list-type.pa_size > button').each(function(i, el) {
                                $(el).text(jp_s[i]);
                            })
                        }

                        /**
                         * Set sizes on button click
                         */
                        $('.ss').click(function(e) {
                            
                            e.preventDefault();

                            $('.ss').removeClass('ss_active');
                            $(this).addClass('ss_active');
                            var selected = $(this).text();

                            console.log(selected);
                            

                            // set EU sizes
                            if (selected === 'EU') {

console.log(eu_s);


                                $('.product-variations.list-type.pa_size > button').each(function(i, el) {
                                    $(el).text(eu_s[i]);
                                })
                            }

                            // set UK sizes
                            if (selected === 'UK') {
                                $('.product-variations.list-type.pa_size > button').each(function(i, el) {
                                    $(el).text(uk_s[i]);
                                })
                            }

                            // set US sizes
                            if (selected === 'US') {
                                $('.product-variations.list-type.pa_size > button').each(function(i, el) {
                                    $(el).text(us_s[i]);
                                })
                            }

                            // set JP sizes
                            if (selected === 'JP') {
                                $('.product-variations.list-type.pa_size > button').each(function(i, el) {
                                    $(el).text(jp_s[i]);
                                })
                            }

                        });

                    });
                </script>

                <style>
                    span#ss_btns_cont {
                        display: block;
                        border: none;
                        padding: 0;
                        height: 28px;
                    }

                    span#ss_btns_cont>button {
                        margin-right: 12.8px;
                        border: 1px solid #313438;
                        background: #313438;
                        color: white;
                        transition: 0.2s;
                        cursor: pointer;
                        float: left;
                        height: 22px;
                        border-radius: 12px;
                    }

                    span#ss_btns_cont:hover {
                        border: none;
                        box-shadow: none;
                    }

                    span#ss_btns_cont>button:hover {
                        transition: 0.2s;
                        background: #e4eaec;
                        border-color: #e4eaec;
                        color: black;
                        font-weight: 500;
                    }

                    .ss_active {
                        background: #e4eaec !important;
                        border-color: #e4eaec !important;
                        color: black !important;
                        font-weight: 500;
                    }
                </style>

            <?php endif; ?>

<?php }
    }

endif;
