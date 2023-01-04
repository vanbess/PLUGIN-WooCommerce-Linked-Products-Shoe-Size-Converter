<?php
defined('ABSPATH') ?: exit();

if (!trait_exists('SS_Save_Data_JS')) :

    trait SS_Save_Data_JS {

        /**
         * JS which handles saving of backend size data (product edit screen)
         *
         * @return void
         */
        public static function ss_save_data_js() {

            global $post;

?>

            <script>
                jQuery(document).ready(function($) {

                    $('#ss_save_shoe_sizes').click(function(e) {

                        e.preventDefault();

                        // vars
                        var eu_data = [],
                            us_data = [],
                            gb_data = [],
                            jp_data = [],
                            nonce = '<?php echo wp_create_nonce('ss save shoe sizes') ?>',
                            p_id = '<?php echo $post->ID; ?>',
                            eu_inputs = $('.ss_matching_sizes_eu'),
                            us_inputs = $('.ss_matching_sizes_us'),
                            gb_inputs = $('.ss_matching_sizes_gb'),
                            jp_inputs = $('.ss_matching_sizes_jp'),
                            eu_data_length;

                        // extract size data and add to associated objects
                        eu_inputs.each(function(i, e) {
                            eu_data.push($(e).val());
                        });

                        us_inputs.each(function(i, e) {
                            if ($(e).val() !== '') {
                                us_data.push($(e).val());
                            }
                        });

                        gb_inputs.each(function(i, e) {
                            if ($(e).val() !== '') {
                                gb_data.push($(e).val());
                            }
                        });

                        jp_inputs.each(function(i, e) {
                            if ($(e).val() !== '') {
                                jp_data.push($(e).val());
                            }
                        });

                        // get EU data length
                        eu_data_length = eu_data.length;

                        // throw error if any matching sizes missing and bail
                        // if (jp_data.length !== eu_data_length || gb_data.length !== eu_data_length || us_data.length !== eu_data_length) {
                        //     alert('<?php _e('Matching size data only partially complete! Please be sure to supply ALL matching measurements before saving.', 'woocommerce'); ?>');
                        //     return;
                        // }

                        // send ajax request to save data
                        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';

                        var data = {
                            'action': 'ss_save_data',
                            '_ajax_nonce': nonce,
                            'prod_id': p_id,
                            'eu_data': eu_data,
                            'us_data': us_data,
                            'gb_data': gb_data,
                            'jp_data': jp_data
                        };

                        $.post(ajaxurl, data, function(response) {
                            console.log(response);
                        });

                    });

                });
            </script>

<?php }
    }

endif;
