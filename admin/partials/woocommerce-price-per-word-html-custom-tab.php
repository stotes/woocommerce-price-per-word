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

        </div>
        <?php

    }
}

Woocommerce_Price_Per_Word_Html_Custom_tab::init();
