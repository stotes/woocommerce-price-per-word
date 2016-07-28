<?php

/**
 * @package    Woocommerce_Price_Per_Word
 * @subpackage Woocommerce_Price_Per_Word/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class Woocommerce_Price_Per_Word_Html_Custom_tab {

    /**
     * Hook in methods
     * @since    1.2.0
     * @access   static
     */
    public static function init() {
        add_action('custom_tab_options_price_per_word_character_settings_display', array(__CLASS__, 'custom_tab_options_price_per_word_character_settings_display'));
    }

    public static function custom_tab_options_price_per_word_character_settings_display() {
        global $post, $pagenow;

        /**
         * offers_for_woocommerce_enabled
         */

        $_price_per_word_character = get_post_meta($post->ID, '_price_per_word_character', true);
        $field_value = $_price_per_word_character;
        $field_value = ($_price_per_word_character) ? $_price_per_word_character : 'word';

        /**
         * Enable Price breaks
         */
        $_price_breaks_enabled = '';
        $post_meta_price_breaks_enabled = get_post_meta($post->ID, '_is_enable_price_breaks', true);
        $field_value_price_breaks_enabled = 'yes';
        $field_callback_price_breaks_enabled = ($post_meta_price_breaks_enabled) ? $post_meta_price_breaks_enabled : 'no';
        if (isset($post_meta_price_breaks_enabled) && $post_meta_price_breaks_enabled == 'yes') {
            $_price_breaks_serialize = get_post_meta($post->ID, '_price_breaks_array', true);
            $_price_breaks_array = maybe_unserialize($_price_breaks_serialize);
        }
        ?>
        <div id="custom_tab_data_woocommerce_price_word_character_tab" class="panel woocommerce_options_panel"
             style="display: none;">

            <div class="options_group">
                <?php woocommerce_wp_radio(array(
                    'options' => array("word" => "Price per Word", "character" => "Price per Character"),
                    'name' => '_price_per_word_character',
                    'value' => $field_value,
                    'id' => '_price_per_word_character',
                    'label' => __('Set Price Per Word OR Price Per Character', 'woocommerce-price-per-word'),
                    'desc_tip' => 'true',
                    'description' => __('Choose whether to set pricing based on the number of words in a document or the number of characters', 'woocommerce-price-per-word'))); ?>
            </div>

            <div class="options_group word_count_cap">
                <div class="options_group word_count_cap_status">
                    <?php
                    woocommerce_wp_checkbox(array(
                        'id' => '_word_count_cap_status',
                        'label' => __('Enable ' . $_price_per_word_character . ' count cap?', 'woocommerce'),
                        'cbvalue' => 'open',
                        'value' => esc_attr($post->_word_count_cap_status),
                        'desc_tip' => 'true',
                        'description' => __('Enable to set an absolute cap on word count.', 'woocommerce')
                    ));
                    woocommerce_wp_text_input(array(
                        'id' => '_word_count_cap_word_limit',
                        'label' => __(ucfirst($_price_per_word_character) . ' limit', 'woocommerce'),
                        'desc_tip' => true,
                        'description' => __('Enter the maximum word limit to accept uploaded file words.', 'woocommerce'),
                        'type' => 'number',
                        'custom_attributes' => array(
                            'step' => '1',
                            'min' => '1'
                        )
                    ));
                    ?>
                </div>
            </div>

            <div class="options_group price-breaks-section">
                <?php
                woocommerce_wp_checkbox(array(
                    'name' => '_is_enable_price_breaks',
                    'value' => $field_value_price_breaks_enabled,
                    'cbvalue' => $field_callback_price_breaks_enabled,
                    'id' => '_is_enable_price_breaks',
                    'label' => __('Enable price breaks?', 'woocommerce-price-per-word'),
                    'desc_tip' => 'true',
                    'description' => __('Enable to set multiple prices for multiple levels.', 'woocommerce-price-per-word'))); ?>
                <div id="price-breaks-container">
                    <?php $show_price_breaks = $field_callback_price_breaks_enabled == "yes" ? "display:block" : "display:none"; ?>
                    <table id="price-breaks-list" style="<?php echo $show_price_breaks ?>">
                        <thead>
                        <tr>
                            <th class="min-title-head"><?php _e('Min Words', 'woocommerce-price-per-word'); ?></th>
                            <th class="max-title-head"><?php _e('Max Words', 'woocommerce-price-per-word'); ?></th>
                            <th class="price-title-head"><?php _e('Price (' . get_woocommerce_currency_symbol() . ')', 'woocommerce-price-per-word'); ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $first_min = (isset($_price_breaks_array) && isset($_price_breaks_array[0]["min"])) ? $_price_breaks_array[0]["min"] : "";
                        $first_max = (isset($_price_breaks_array) && isset($_price_breaks_array[0]["max"])) ? $_price_breaks_array[0]["max"] : "";
                        $first_price = (isset($_price_breaks_array) && isset($_price_breaks_array[0]["price"])) ? $_price_breaks_array[0]["price"] : "";
                        ?>
                        <tr class="prototype">
                            <td width="25%"><input type="text" name="price-breaks-min[]"
                                                   value="<?php echo !empty($first_min) ? $first_min : 0; ?>" class=""
                                                   readonly/></td>
                            <td width="25%"><input type="text" name="price-breaks-max[]"
                                                   value="<?php echo !empty($first_max) ? $first_max : '>'; ?>"
                                /></td>
                            <td width="25%"><input type="number" name="price-breaks-price[]"
                                                   value="<?php echo $first_price ?>" min="0"/></td>
                            <td width="25%">
                                <a href="javascript:void(0);" class="remove">Remove</a>
                            </td>
                        </tr>

                        <?php
                        if (isset($_price_breaks_array) && !empty($_price_breaks_array)) {
                            for ($row = 1; $row < count($_price_breaks_array); $row++) {
                                ?>
                                <tr class="">
                                    <td width="25%"><input type="text" name="price-breaks-min[]"
                                                           value="<?php echo $_price_breaks_array[$row]["min"]; ?>"
                                                           class=""
                                                           readonly/></td>
                                    <td width="25%"><input type="text" name="price-breaks-max[]"
                                                           value="<?php echo $_price_breaks_array[$row]["max"]; ?>"
                                        />
                                    </td>
                                    <td width="25%"><input type="number" name="price-breaks-price[]"
                                                           value="<?php echo $_price_breaks_array[$row]["price"]; ?>"
                                                           min="0"/>
                                    </td>
                                    <td width="25%">
                                        <a href="javascript:void(0);" class="remove">Remove</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td><a href="javascript:void(0);" class="add">Add row</a></td>
                            <td colspan="3"><span style="color: #FF0000">Note: <strong>'>'</strong> sign indicate greater than minimum value.</span>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
        <?php

    }
}

Woocommerce_Price_Per_Word_Html_Custom_tab::init();
