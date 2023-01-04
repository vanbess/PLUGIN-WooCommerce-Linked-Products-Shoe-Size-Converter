<?php

defined('ABSPATH') ?: exit();

if (!trait_exists('SS_Conv_Tab')) :

    trait SS_Conv_Tab {

        /**
         * Render shoe size conversion tab HTML (product edit screen)
         *
         * @return void
         */
        public static function ss_conv_tab() {

            global $post;

            // retrieve product and available variations
            $product = wc_get_product($post->ID);
            $av_vars = $product->get_available_variations();

            // retrieve variation attributes and push to array
            $attribs = [];

            foreach ($av_vars as $var_data) :
                $attribs[] = $var_data['attributes'];
            endforeach;

            // if attribute_pa_size present in $attribs, extract sizes and push to array
            $sizes = [];

            foreach ($attribs as $attrib_arr => $attrib_data) :
                if (key_exists('attribute_pa_size', $attrib_data)) :
                    $sizes[] = $attrib_data['attribute_pa_size'];
                endif;
            endforeach;

            // retrieve saved sizes
            $us_s = maybe_unserialize(get_post_meta($post->ID, 'ss_us_data', true));
            $uk_s = maybe_unserialize(get_post_meta($post->ID, 'ss_uk_data', true));
            $jp_s = maybe_unserialize(get_post_meta($post->ID, 'ss_jp_data', true));

            // display related sizes inputs
?>

            <div id="shoe_size_conv_tab" class="panel woocommerce_options_panel hidden">
                
                <div id="show_size_conv_tab_inner">

                    <!-- enable/disable conversion -->
                    <div class="options-group" style="border-bottom: 1px solid #e1e1e1;">
                        <p class="form-field shoe-size-conv-enable">
                            <label for="ss_enable_disable"><b><i><?php _e('Enable shoe size conversion?'); ?></i></b></label>
                            <input type="checkbox" name="ss_enable_disable" id="ss_enable_disable">
                        </p>
                    </div>

                    <!-- matching sizes -->
                    <div class="options-group" style="border-bottom: 1px solid #e1e1e1;">
                    
                        <p class="shoe-size-matching-sizes">
                            <b><i><?php _e('Enter matching shoe sizes for this product below. <u>NOTE:</u> shoe size conversion will be updated for all products linked to this one.'); ?></i></b>
                        </p>

                        <?php foreach ($sizes as $index => $size) : ?>

                            <p>
                                <input type="text" class="ss_matching_sizes_eu" value="<?php echo $size; ?>" readonly style="width:15%; margin-right:20px;">
                                <input type="text" class="ss_matching_sizes_us" value="<?php echo $us_s[$index]; ?>" placeholder="<?php _e('US size'); ?>" style="width:15%; margin-right:20px;">
                                <input type="text" class="ss_matching_sizes_gb" value="<?php echo $uk_s[$index]; ?>" placeholder="<?php _e('GB size'); ?>" style="width:15%; margin-right:20px;">
                                <input type="text" class="ss_matching_sizes_jp" value="<?php echo $jp_s[$index]; ?>" placeholder="<?php _e('JP size'); ?>" style="width:15%; margin-right:20px;">
                            </p>

                        <?php endforeach; ?>

                    </div>

                    <!-- save/update matching sizes -->
                    <div class="options-group" style="border-bottom: 1px solid #e1e1e1;">
                        <p class="shoe-size-save">
                            <button class="button button-primary button-medium" id="ss_save_shoe_sizes"><?php _e('Save shoe sizes'); ?></button>
                        </p>
                    </div>

                </div>
            </div>
<?php }
    }

endif;
