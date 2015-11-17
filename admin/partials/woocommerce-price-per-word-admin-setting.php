<?php

/**
 * @class       Woocommerce_Price_Per_Word_Setting
 * @version	1.0
 * @package	woocommerce-price-per-word
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class Woocommerce_Price_Per_Word_Setting {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {

        add_action('woocommerce_price_per_word_general_setting', array(__CLASS__, 'woocommerce_price_per_word_general_setting'));
        add_action('woocommerce_price_per_word_general_setting_save_field', array(__CLASS__, 'woocommerce_price_per_word_general_setting_save_field'));
    }

    public static function woocommerce_price_per_word_general_setting_fields() {

        $fields[] = array('title' => __('WooCommerce Price Per Word Settings', 'woocommerce-price-per-word'), 'type' => 'title', 'desc' => '', 'id' => 'general_options');

        $fields[] = array(
		'title'    => __( 'Use "price per word" or "price per character"', 'woocommerce-price-per-word' ),
		'id'       => 'aewcppw_word_character',
		'default'  => 'word',
		'type'     => 'radio',
		'desc' =>  __( 'This option is important as it will affect base on "price per word" or "price per character".', 'woocommerce-price-per-word' ),
		'options'  => array(
			'word' => __( 'Yes, I want to count price base on total word.', 'woocommerce' ),
			'character'  => __( 'Yes, I want to count price base on total character.', 'woocommerce-price-per-word' )
		),
	);
        
        $fields[] = array(
		'title'   => __( 'Allow users to enter QTY', 'woocommerce' ),
		'desc'    => __( 'Allow users to enter QTY.', 'woocommerce-price-per-word' ),
		'id'      => 'aewcppw_allow_users_to_enter_qty',
		'default' => 'no',
		'type'    => 'checkbox',
	);

        $fields[] = array(
            'title' => __('Product Page Message', 'woocommerce-price-per-word'),
            'desc' => __('', 'woocommerce-price-per-word'),
            'id' => 'aewcppw_product_page_message',
            'type' => 'textarea',
            'css' => 'min-width:300px;',
            'default' => 'Please upload your .doc, .docx, .pdf or .txt to get a price.'
        );

        $fields[] = array('type' => 'sectionend', 'id' => 'general_options');

        return $fields;
    }

    public static function woocommerce_price_per_word_general_setting() {
        $wppw_setting_fields = self::woocommerce_price_per_word_general_setting_fields();
        $Html_output = new Woocommerce_Price_Per_Word_Html_output();
        ?>
        <form id="woocommerce_price_per_word_form" enctype="multipart/form-data" action="" method="post">
            <?php $Html_output->init($wppw_setting_fields); ?>
            <p class="submit">
                <input type="submit" name="woocommerce_price_per_word_integration" class="button-primary" value="<?php esc_attr_e('Save changes', 'Option'); ?>" />
            </p>
        </form>
        <?php
    }

   
    public static function woocommerce_price_per_word_general_setting_save_field() {
        $wppw_setting_fields = self::woocommerce_price_per_word_general_setting_fields();
        $Html_output = new Woocommerce_Price_Per_Word_Html_output();
        $Html_output->save_fields($wppw_setting_fields);
    }

}

Woocommerce_Price_Per_Word_Setting::init();
