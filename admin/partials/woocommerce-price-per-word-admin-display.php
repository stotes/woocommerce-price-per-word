<?php

/**
 * @package    Woocommerce_Price_Per_Word
 * @subpackage Woocommerce_Price_Per_Word/admin
 * @author     Angell EYE <service@angelleye.com>
 */

class Woocommerce_Price_Per_Word_Admin_Display {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'wppw_add_settings_menu'));
    }

    /**
     * @since    1.0.0
     * @access   public
     */
    public static function wppw_add_settings_menu() {
        add_options_page('Woocommerce Price Per Word Options', 'Woocommerce Price Per Word', 'manage_options', 'woocommerce-price-per-word-option', array(__CLASS__, 'woocommerce_price_per_word_option'));
    }

    /**
     * @since    1.0.0
     * @access   public
     */
    public static function woocommerce_price_per_word_option() {
        $setting_tabs = apply_filters('woocommerce_price_per_word_setting_tab', array('general' => 'General'));
        $current_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
        ?>
        <h2 class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs as $name => $label)
                echo '<a href="' . admin_url('admin.php?page=woocommerce-price-per-word-option&tab=' . $name) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            ?>
        </h2>
        <?php
        foreach ($setting_tabs as $setting_tabkey => $setting_tabvalue) {
            switch ($setting_tabkey) {
                case $current_tab:
                    do_action('woocommerce_price_per_word_' . $setting_tabkey . '_setting_save_field');
                    do_action('woocommerce_price_per_word_' . $setting_tabkey . '_setting');
                    break;
            }
        }
    }

}

Woocommerce_Price_Per_Word_Admin_Display::init();
