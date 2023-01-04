<?php

defined('ABSPATH') ?: exit();

if (!trait_exists('SS_Save_Data')) :

    trait SS_Save_Data {

        /**
         * Save shoe size data to DB via AJAX
         *
         * @return void
         */
        public static function ss_save_data() {

            check_ajax_referer('ss save shoe sizes');

            // debug
            // wp_send_json($_POST);
            // wp_die();

            // grab subbed values
            $prod_id = isset($_POST['prod_id']) ? $_POST['prod_id'] : false;
            $eu_data = isset($_POST['eu_data']) ? $_POST['eu_data'] : false;
            $us_data = isset($_POST['us_data']) ? $_POST['us_data'] : false;
            $uk_data = isset($_POST['gb_data']) ? $_POST['gb_data'] : false;
            $jp_data = isset($_POST['jp_data']) ? $_POST['jp_data'] : false;
            $enabled = $_POST['enable_disable'];

            // save subbed values to product
            $eu_uppded = update_post_meta($prod_id, 'ss_eu_data', maybe_serialize($eu_data));
            $us_uppded = update_post_meta($prod_id, 'ss_us_data', maybe_serialize($us_data));
            $uk_uppded = update_post_meta($prod_id, 'ss_uk_data', maybe_serialize($uk_data));
            $jp_uppded = update_post_meta($prod_id, 'ss_jp_data', maybe_serialize($jp_data));

            // enabled/disabled
            update_post_meta($prod_id, 'ss_enabled', $enabled);

            // get linked product ids
            $linked_prod_data = get_option('plgfymao_all_rulesplgfyplv');

            // holds linked product ids
            $linked_ids = '';

            // loop to check for matching grouped products
            if ($linked_prod_data && !empty($linked_prod_data)) :

                foreach ($linked_prod_data as $index => $data) :
                    if (in_array($prod_id, $data['apllied_on_ids'])) :
                        $linked_ids = $data['apllied_on_ids'];
                    endif;
                endforeach;

            endif;

            // if linked ids present, loop to update linked products as well
            if (is_array($linked_ids) && !empty($linked_ids)) :

                foreach ($linked_ids as $l_id) :

                    // don't re-update current product
                    if ((int)$l_id === (int)$prod_id) :
                        continue;
                    endif;

                    $eu_linked_uppded[] = update_post_meta($l_id, 'ss_eu_data', maybe_serialize($eu_data));
                    $us_linked_uppded[] = update_post_meta($l_id, 'ss_us_data', maybe_serialize($us_data));
                    $uk_linked_uppded[] = update_post_meta($l_id, 'ss_uk_data', maybe_serialize($uk_data));
                    $jp_linked_uppded[] = update_post_meta($l_id, 'ss_jp_data', maybe_serialize($jp_data));

                endforeach;

            endif;

            // send error/success message
            if (!empty($us_linked_uppded) || !empty($uk_linked_uppded) || !empty($jp_linked_uppded) || !empty($eu_linked_uppded)) :
                wp_send_json_success([$us_linked_uppded, $uk_linked_uppded, $jp_linked_uppded, $eu_linked_uppded]);
            else :
                wp_send_json_error([$us_linked_uppded, $uk_linked_uppded, $jp_linked_uppded, $eu_linked_uppded]);
            endif;

            wp_die();
        }
    }

endif;
