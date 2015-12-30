<?php

/**
 * @package    Woocommerce_Price_Per_Word
 * @subpackage Woocommerce_Price_Per_Word/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class Woocommerce_Price_Per_Word_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woocommerce-price-per-word-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woocommerce-price-per-word-admin.js', array('jquery'), $this->version, false);
        if (wp_script_is($this->plugin_name)) {
            wp_localize_script($this->plugin_name, 'woocommerce_price_per_word_params', apply_filters('woocommerce_price_per_word_params', array(
                'woocommerce_currency_symbol_js' => '(' . get_woocommerce_currency_symbol() . ')',
                'aewcppw_word_character' => $this->wppw_get_product_type()
            )));
        }
    }

    private function load_dependencies() {
        /**
         * The class responsible for defining all actions that occur in the Dashboard
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/woocommerce-price-per-word-admin-display.php';

        /**
         * The class responsible for defining function for display Html element
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/woocommerce-price-per-word-html-output.php';

        /**
         * The class responsible for defining function for display general setting tab
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/woocommerce-price-per-word-admin-setting.php';
    }

    /**
     * add enable/disable option for Price Per Word
     *
     * @since    1.0.0
     */
    public function product_type_options_own($product_type_options) {
        $wppw_get_product_type = $this->wppw_get_product_type();
        if ($wppw_get_product_type == 'word') {
            $type = "Word";
        } else {
            $type = "Character";
        }
        $product_type_options['price_per_word'] = array(
            'id' => '_price_per_word',
            'wrapper_class' => '',
            'label' => __("Enable Price Per $type", 'woocommerce'),
            'description' => __("Enable Price Per $type", 'woocommerce'),
            'default' => 'no'
        );
        return $product_type_options;
    }

    public function woocommerce_process_product_meta_save($post_id) {
        if (isset($_POST['_price_per_word'])) {
            if ($_POST['_price_per_word'] == "on") {
                update_post_meta($post_id, '_price_per_word', "yes");
            }
        } else {
            update_post_meta($post_id, '_price_per_word', "no");
        }

        if (isset($_POST['_minimum_product_price']) && !empty($_POST['_minimum_product_price']) && !is_array($_POST['_minimum_product_price'])) {
            update_post_meta($post_id, '_minimum_product_price', $_POST['_minimum_product_price']);
        }
    }

    public function woocommerce_before_add_to_cart_button_own() {
        if ($this->is_enable_price_per_word()) {
            $display_or_hide_ppw_file_container = (isset($_SESSION['attach_id']) && !empty($_SESSION['attach_id'])) ? '' : 'style="display: none"';
            $display_or_hide_ppw_file_upload_div = (isset($_SESSION['attach_id']) && !empty($_SESSION['attach_id'])) ? 'style="display: none"' : 'a';
            $aewcppw_product_page_message = get_option('aewcppw_product_page_message');
            if (empty($aewcppw_product_page_message)) {
                $aewcppw_product_page_message = 'Please upload your .doc, .docx, .pdf or .txt to get a price.';
            }
            ?>
            <span id="aewcppw_product_page_message" <?php echo $display_or_hide_ppw_file_upload_div; ?>><?php echo $aewcppw_product_page_message; ?></span>
            <div class="ppw_file_upload_div" <?php echo $display_or_hide_ppw_file_upload_div; ?>>
                <label for="file_upload">Select your file(s)</label><input type="file" name="ppw_file_upload" value="Add File" id="ppw_file_upload_id">
            </div>
            <div id="ppw_loader" style="display: none;"><div class="ppw-spinner-loader">Loading...</div></div>
            <div id="ppw_file_container" class="woocommerce-message" <?php echo $display_or_hide_ppw_file_container; ?>>
                <?php
                if (session_id()) {
                    if (isset($_SESSION['attach_id']) && !empty($_SESSION['attach_id'])) {
                        echo '<input type="hidden" name="file_uploaded" value="url">';
                        echo '<a id="ppw_remove_file" data_file="' . $_SESSION['attach_id'] . '" class="button wc-forward" title="' . esc_attr__('Remove file', 'woocommerce') . '" href="#">' . "Delete" . '</a>File successfully uploaded';
                    }
                }
                ?>
            </div>
            <?php
        }
    }

    public function woocommerce_get_price_html_own($price) {
        $wppw_get_product_type = $this->wppw_get_product_type();
        if ($wppw_get_product_type == 'word') {
            $type = "Word";
        } else {
            $type = "Character";
        }
        if ($this->is_enable_price_per_word()) {
            return "Price Per $type: " . $price;
        } else {
            return $price;
        }
    }

    public function ppw_file_upload_action() {
        add_filter('upload_dir', array($this, 'woocommerce_price_per_word_upload_dir'), 10, 1);
        $return_messge = array('total_word' => '', 'total_character' => '', 'aewcppw_word_character' => $this->wppw_get_product_type(), 'message' => 'File successfully uploaded', 'url' => '', 'message_content' => '');
        if (isset($_POST['security']) && !empty($_POST['security'])) {
            if (wp_verify_nonce($_POST['security'], 'woocommerce_price_per_word_params_nonce')) {
                if (!function_exists('wp_handle_upload')) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }
                $uploadedfile = $_FILES['file'];
                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
                if ($movefile && !isset($movefile['error'])) {
                    $fileArray = pathinfo($movefile['file']);
                    $file_ext = $fileArray['extension'];
                    $return_messge['url'] = $movefile['url'];
                    if ($file_ext == "doc" || $file_ext == "docx" || $file_ext == "pdf") {
                        $docObj = new Woocommerce_Price_Per_Word_String_Reader($movefile['file']);
                        $return_string = $docObj->convertToText();
                        if ($return_string != 'File Not exists' && $return_string != 'Invalid File Type' && !empty($return_string)) {
                            $total_words = count(str_word_count($return_string, 1));
                            $total_characters = strlen(utf8_decode($return_string));
                            $attach_id = $this->ppw_upload_file_to_media($movefile['file'], $total_words, $total_characters);
                            $attachment_page = wp_get_attachment_url($attach_id);
                            $return_messge['total_word'] = $total_words;
                            $return_messge['total_character'] = $total_characters;
                            $_SESSION['attach_id'] = $attach_id;
                            $_SESSION['total_words'] = $total_words;
                            $_SESSION['file_name'] = '<a href="' . esc_url($attachment_page) . '" target="_blank">' . esc_html($fileArray['basename']) . '</a>';
                            $return_messge['message_content'] = '<a id="ppw_remove_file" data_file="' . $attach_id . '" class="button wc-forward" title="' . esc_attr__('Remove file', 'woocommerce') . '" href="#">' . "Delete" . '</a>File successfully uploaded';
                            echo json_encode($return_messge, true);
                        } else {
                            $return_messge = array('total_word' => '', 'message' => 'Your pdf file is secured or empty.', 'url' => '');
                            $return_messge['message_content'] = 'The file upload failed, Please choose a valid file extension and try again.';
                            echo json_encode($return_messge, true);
                        }
                    } elseif ($file_ext == 'txt') {
                        $file_content = file_get_contents($movefile['file']);
                        $total_words = count(str_word_count($file_content, 1));
                        $total_characters = strlen(utf8_decode($file_content));
                        $attach_id = $this->ppw_upload_file_to_media($movefile['file'], $total_words, $total_characters);
                        $attachment_page = wp_get_attachment_url($attach_id);
                        $return_messge['total_word'] = $total_words;
                        $return_messge['total_character'] = $total_characters;
                        $_SESSION['attach_id'] = $attach_id;
                        $_SESSION['total_words'] = $total_words;
                        $_SESSION['file_name'] = '<a href="' . esc_url($attachment_page) . '" target="_blank">' . esc_html($fileArray['basename']) . '</a>';
                        $return_messge['message_content'] = '<a id="ppw_remove_file" data_file="' . $attach_id . '" class="button wc-forward" title="' . esc_attr__('Remove file', 'woocommerce') . '" href="#">' . "Delete" . '</a>File successfully uploaded';
                        echo json_encode($return_messge, true);
                    } else {
                        $return_messge = array('total_word' => '', 'message' => 'The file upload failed, Please choose a valid file extension and try again.', 'url' => '');
                        echo json_encode($return_messge, true);
                    }
                    exit();
                } else {
                    $return_messge = array('total_word' => '', 'message' => $movefile['error'], 'url' => '');
                    echo json_encode($return_messge, true);
                    exit();
                }
            } else {
                $return_messge = array('total_word' => '', 'message' => 'security problem, wordpress nonce is not verified', 'url' => '');
                echo json_encode($return_messge, true);
                exit();
            }
        } else {
            $return_messge = array('total_word' => '', 'message' => 'security problem, wordpress nonce is not verified', 'url' => '');
            echo json_encode($return_messge, true);
            exit();
        }
    }

    public function woocommerce_price_per_word_upload_dir($param) {
        $custom_dir = '/woocommerce-price-per-word';
        $param['path'] = $param['basedir'] . $custom_dir;
        $param['url'] = $param['basedir'] . $custom_dir;
        return $param;
    }

    public function woocommerce_price_per_word_extended_mime_types($mime_types) {
        $mime_types['doc-dot'] = 'doc|dot|word|w6w application/msword';
        return $mime_types;
    }

    public function woocommerce_add_to_cart_redirect_own($cart_url) {
        if (isset($cart_url) && !empty($cart_url)) {
            return $cart_url;
        }
    }

    public function ppw_upload_file_to_media($filename, $count_word, $total_characters) {
        $parent_post_id = '';
        $filetype = wp_check_filetype(basename($filename), null);
        $wp_upload_dir = wp_upload_dir();
        $attachment = array(
            'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
            'post_mime_type' => $filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
        wp_update_attachment_metadata($attach_id, $attach_data);
        update_post_meta($attach_id, 'total_word', $count_word);
        update_post_meta($attach_id, 'total_character', $total_characters);
        return $attach_id;
    }

    public function ppw_session_start() {
        if (!session_id()) {
            session_start();
        }
    }

    public function woocommerce_price_per_word_ppw_remove() {
        $return_messge = array('message' => 'File successfully deleted');
        if (isset($_POST['security']) && !empty($_POST['security'])) {
            if (wp_verify_nonce($_POST['security'], 'woocommerce_price_per_word_params_nonce')) {
                if (isset($_POST['value']) && !empty($_POST['value'])) {
                    wp_delete_attachment($_POST['value'], true);
                    unset($_SESSION['attachment_anchor_tag_url']);
                    unset($_SESSION['attach_id']);
                    unset($_SESSION['total_words']);
                    unset($_SESSION['total_characters']);
                    unset($_SESSION['file_name']);
                    echo json_encode($return_messge, true);
                    exit();
                }
            }
        }
    }

    public function woocommerce_add_cart_item_data_own($cart_item_data, $product_id) {
        global $woocommerce;
        $custom_cart_data = array();
        if (isset($_SESSION['attach_id']) && !empty($_SESSION['attach_id'])) {
            $custom_cart_data['attach_id'] = $_SESSION['attach_id'];
            unset($_SESSION['attach_id']);
        }
        if (isset($_SESSION['total_words']) && !empty($_SESSION['total_words'])) {
            $custom_cart_data['total_words'] = $_SESSION['total_words'];
            unset($_SESSION['total_words']);
        }
        if (isset($_SESSION['total_characters']) && !empty($_SESSION['total_characters'])) {
            $custom_cart_data['total_characters'] = $_SESSION['total_characters'];
            unset($_SESSION['total_characters']);
        }
        if (isset($_SESSION['file_name']) && !empty($_SESSION['file_name'])) {
            $custom_cart_data['file_name'] = $_SESSION['file_name'];
            unset($_SESSION['file_name']);
        }
        if (isset($_SESSION['attachment_anchor_tag_url']) && !empty($_SESSION['attachment_anchor_tag_url'])) {
            $custom_cart_data['attachment_anchor_tag_url'] = $_SESSION['attachment_anchor_tag_url'];
            unset($_SESSION['attachment_anchor_tag_url']);
        }
        $ppw_custom_cart_data_value = array('ppw_custom_cart_data' => $custom_cart_data);
        if (empty($custom_cart_data)) {
            return $cart_item_data;
        } else {
            if (empty($cart_item_data)) {
                return $ppw_custom_cart_data_value;
            } else {
                return array_merge($cart_item_data, $ppw_custom_cart_data_value);
            }
        }
    }

    public function woocommerce_get_cart_item_from_session_own($item, $values, $key) {
        if (array_key_exists('ppw_custom_cart_data', $values)) {
            $item['ppw_custom_cart_data'] = $values['ppw_custom_cart_data'];
        }
        return $item;
    }

    public function woocommerce_checkout_cart_item_quantity_own($product_name, $values, $cart_item_key) {
        if (isset($values['ppw_custom_cart_data']) && count($values['ppw_custom_cart_data']) > 0) {
            $return_string = $product_name . "</a>";

            if (isset($values['ppw_custom_cart_data']['file_name']) && !empty($values['ppw_custom_cart_data']['file_name'])) {

                $return_string .= '<div><span><b>File Name: </b></span>';
                $return_string .= "<span>" . $values['ppw_custom_cart_data']['file_name'] . "</span></div>";
            }

            $wppw_get_product_type = $this->wppw_get_product_type();

            if ($wppw_get_product_type == 'word') {
                if (isset($values['ppw_custom_cart_data']['total_words']) && !empty($values['ppw_custom_cart_data']['total_words'])) {
                    $return_string .= '<div><span><b>Total word: </b></span>';
                    $return_string .= "<span>" . $values['ppw_custom_cart_data']['total_words'] . "</span></div>";
                }
            } else {
                if (isset($values['ppw_custom_cart_data']['total_characters']) && !empty($values['ppw_custom_cart_data']['total_characters'])) {
                    $return_string .= '<div><span><b>Total character: </b></span>';
                    $return_string .= "<span>" . $values['ppw_custom_cart_data']['total_characters'] . "</span></div>";
                }
            }

            return $return_string;
        } else {
            return $product_name;
        }
    }

    public function woocommerce_add_order_item_meta_own($item_id, $values) {
        global $woocommerce, $wpdb;
        $user_custom_values = ( isset($values['ppw_custom_cart_data']) && !empty($values['ppw_custom_cart_data']) ) ? $values['ppw_custom_cart_data'] : '';
        if (!empty($user_custom_values)) {
            foreach ($user_custom_values as $key => $value) {
                if ($key != "attach_id") {
                    $key = ucwords(str_replace("_", " ", $key));
                    wc_add_order_item_meta($item_id, $key, $value);
                }
            }
            wc_add_order_item_meta($item_id, 'ppw_custom_cart_data', $user_custom_values);
        }
    }

    public function is_enable_price_per_word() {
        global $product;
        if (isset($product->id) && !empty($product->id)) {
            $enable = get_post_meta($product->id, '_price_per_word', true);
            if (!empty($enable) && $enable == "yes") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function woocommerce_single_product_summary_own() {
        if ($this->is_enable_price_per_word()) {
            global $product;
            $total_price = 0;
            $style = "style='display:none;'";
            echo "<div><p class='ppw_total_price price' $style>Total Price: <span class='ppw_total_amount'>$total_price</span></p></div>";
        }
    }

    public function wppw_paypal_standard_additional_parameters($paypal_args) {
        $paypal_args['bn'] = 'AngellEYE_SP_WooCommerce';
        return $paypal_args;
    }

    public function wppw_woocommerce_missing_notice() {
        echo '<div class="error"><p>' . sprintf(__('WooCommerce Price Per Word Plugin requires the %s to work!', 'woocommerce-price-per-word'), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">' . __('WooCommerce', 'woocommerce-price-per-word') . '</a>') . '</p></div>';
    }

    public function wppw_get_product_type() {
        $aewcppw_word_character = get_option('aewcppw_word_character');
        if (empty($aewcppw_word_character)) {
            $aewcppw_word_character = 'word';
        } elseif ($aewcppw_word_character == 'word') {
            $aewcppw_word_character = 'word';
        } elseif ($aewcppw_word_character == 'character') {
            $aewcppw_word_character = 'character';
        } else {
            $aewcppw_word_character = 'word';
        }
        return $aewcppw_word_character;
    }

    public function wppw_woocommerce_product_options_pricing() {
        woocommerce_wp_text_input(array('id' => '_minimum_product_price', 'label' => __('Minimum product price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')', 'data_type' => 'price'));
    }

    public function wppw_variation_panel($loop, $variation_data, $variation) {
        $_minimum_product_price = get_post_meta($variation->ID, '_minimum_product_price', true);
        ?>
        <div>
            <p class="form-row form-row-first">
                <label><?php _e('Minimum Price', 'min-max-quantities-for-woocommerce'); ?>
                    <input type="number" size="5" name="_minimum_product_price[<?php echo $loop; ?>]" value="<?php if ($_minimum_product_price) echo esc_attr($_minimum_product_price); ?>" /></label>
            </p>
        </div>
        <?php
    }

    public function wppw_add_minimum_product_price($cart_object) {
        foreach ($cart_object->cart_contents as $cart_key => $value) {
            if (isset($value['variation_id']) && !empty($value['variation_id'])) {
                $_minimum_product_price = $this->wppw_get_minimum_price($value['variation_id']);
            } elseif (isset($value['product_id']) && !empty($value['product_id'])) {
                $_minimum_product_price = $this->wppw_get_minimum_price($value['product_id']);
            }
            if ($_minimum_product_price) {
                $value['data']->price = $_minimum_product_price;
            }
        }
    }

    public function wppw_woocommerce_save_product_variation($variation_id, $i) {
        $minimum_allowed_quantity = isset($_POST['_minimum_product_price']) ? $_POST['_minimum_product_price'] : '';
        update_post_meta($variation_id, '_minimum_product_price', $minimum_allowed_quantity[$i]);
    }

    public function wppw_get_minimum_price($product_id = null) {
        $minimum_product_price = get_post_meta($product_id, '_minimum_product_price', true);
        if (isset($minimum_product_price) && !empty($minimum_product_price)) {
            return $minimum_product_price;
        } else {
            $minimum_product_price = get_option('_minimum_product_price');
            if (isset($minimum_product_price) && !empty($minimum_product_price)) {
                return $minimum_product_price;
            } else {
                return false;
            }
        }
        
    }

}
