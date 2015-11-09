<?php

/**
 * @package    Woocommerce_Price_Per_Word
 * @subpackage Woocommerce_Price_Per_Word/public
 * @author     Angell EYE <service@angelleye.com>
 */
class Woocommerce_Price_Per_Word_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woocommerce-price-per-word-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        global $post;
        $is_product_type_variable = 'false';
        if (function_exists('get_product')) {
            $product = get_product($post->ID);
            if ($product->is_type('variable') && is_single()) {
                $is_product_type_variable = 'true';
            }
        }

        $attach_id = (isset($_SESSION['attach_id']) && !empty($_SESSION['attach_id'])) ? $_SESSION['attach_id'] : '';
        if (!empty($attach_id)) {
            $total_word = get_post_meta($attach_id, 'total_word', true);
        } else {
            $total_word = '';
        }
        if ($this->is_enable_price_per_word_public()) {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woocommerce-price-per-word-public.js', array('jquery'), $this->version, false);
            if (wp_script_is($this->plugin_name)) {
                wp_localize_script($this->plugin_name, 'woocommerce_price_per_word_params', apply_filters('woocommerce_price_per_word_params', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'woocommerce_price_per_word_params_nonce' => wp_create_nonce("woocommerce_price_per_word_params_nonce"),
                    'total_word' => $total_word,
                    'is_product_type_variable' => $is_product_type_variable,
                    'woocommerce_currency_symbol_js' => get_woocommerce_currency_symbol(),
                    'woocommerce_price_num_decimals' => wc_get_price_decimals()
                )));
            }
        }
        wp_enqueue_script($this->plugin_name .'-bn', plugin_dir_url(__FILE__) . 'js/woocommerce-price-per-word-bn.js', array('jquery'), $this->version, false);
    }

    public function is_enable_price_per_word_public() {
        global $post;
        if (isset($post->ID) && !empty($post->ID)) {
            $enable = get_post_meta($post->ID, '_price_per_word', true);
            if (!empty($enable) && $enable == "yes") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
