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
                'woocommerce_currency_symbol_js' => '('.get_woocommerce_currency_symbol().')'
            )));
        }
    }

    /**
     * add enable/disable option for Price Per Word
     *
     * @since    1.0.0
     */
    public function product_type_options_own($product_type_options) {
        $product_type_options['price_per_word'] = array(
            'id' => '_price_per_word',
            'wrapper_class' => '',
            'label' => __('Enable Price Per Word', 'woocommerce'),
            'description' => __('Enable Price Per Word', 'woocommerce'),
            'default' => 'no'
        );
        return $product_type_options;
    }

    public function woocommerce_process_product_meta_save($post_id) {
        if (isset($_POST['_price_per_word'])) {
            if ($_POST['_price_per_word'] == "on") {
                update_post_meta($post_id, '_price_per_word', "yes");
            } else {
                update_post_meta($post_id, '_price_per_word', "no");
            }
        }
    }

    public function woocommerce_before_add_to_cart_button_own() {
        if ($this->is_enable_price_per_word()) {
            $display_or_hide_ppw_file_container = (isset($_SESSION['attach_id']) && !empty($_SESSION['attach_id'])) ? '' : 'style="display: none"';
            $display_or_hide_ppw_file_upload_div = (isset($_SESSION['attach_id']) && !empty($_SESSION['attach_id'])) ? 'style="display: none"' : '';
            ?>
            <div class="ppw_file_upload_div" <?php echo $display_or_hide_ppw_file_upload_div; ?>>
                <label for="file_upload">Select your file(s)</label><input type="file" name="ppw_file_upload" value="Add File" id="ppw_file_upload_id">
            </div>
            <div id="ppw_loader" style="display: none"><img src="<?php echo PPW_PLUGIN_URL . 'public/image/ajax-loader.gif'; ?>" alt="Loading"/></div>
            <button style="display: none" type="submit" id="upload-button">Upload</button>
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
        if ($this->is_enable_price_per_word()) {
            return 'Price Per Word: ' . $price;
        } else {
            return $price;
        }
    }

    public function ppw_file_upload_action() {
        add_filter('upload_dir', array($this, 'woocommerce_price_per_word_upload_dir'), 10, 1);
        $return_messge = array('total_word' => '', 'message' => 'File successfully uploaded', 'url' => '', 'message_content' => '');
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
                        if ($return_string != 'File Not exists' && $return_string != 'Invalid File Type') {
                            $total_words = count(str_word_count($return_string, 1));
                            $attach_id = $this->ppw_upload_file_to_media($movefile['file'], $total_words);
                            $attachment_page = wp_get_attachment_url($attach_id);
                            $return_messge['total_word'] = $total_words;
                            $_SESSION['attach_id'] = $attach_id;
                            $_SESSION['total_words'] = $total_words;
                            $_SESSION['file_url'] = $attachment_page;
                            $_SESSION['file_name'] = $fileArray['basename'];
                            $return_messge['message_content'] = '<a id="ppw_remove_file" data_file="' . $attach_id . '" class="button wc-forward" title="' . esc_attr__('Remove file', 'woocommerce') . '" href="#">' . "Delete" . '</a>File successfully uploaded';
                            echo json_encode($return_messge, true);
                        }
                    } elseif ($file_ext == 'txt') {
                        $file_content = file_get_contents($movefile['file']);
                        $total_words = count(str_word_count($file_content, 1));
                        $attach_id = $this->ppw_upload_file_to_media($movefile['file'], $total_words);
                        $attachment_page = wp_get_attachment_url($attach_id);
                        $return_messge['total_word'] = $total_words;
                        $_SESSION['attach_id'] = $attach_id;
                        $_SESSION['total_words'] = $total_words;
                        $_SESSION['file_url'] = $attachment_page;
                        $_SESSION['file_name'] = $fileArray['basename'];
                        $return_messge['message_content'] = '<a id="ppw_remove_file" data_file="' . $attach_id . '" class="button wc-forward" title="' . esc_attr__('Remove file', 'woocommerce') . '" href="#">' . "Delete" . '</a>File successfully uploaded';
                        echo json_encode($return_messge, true);
                    } else {
                        $return_messge = array('total_word' => '', 'message' => 'The file upload failed, Please choose a valid file extension and try again.', 'url' => '');
                        $return_messge['message_content'] = 'The file upload failed, Please choose a valid file extension and try again.';
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
        $custome_dir = '/woocommerce-price-per-word';
        $param['path'] = $param['basedir'] . $custome_dir;
        $param['url'] = $param['basedir'] . $custome_dir;
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

    public function ppw_upload_file_to_media($filename, $count_word) {
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
                    wp_delete_attachment($_POST['value']);
                    unset($_SESSION['attachment_anchor_tag_url']);
                    unset($_SESSION['attach_id']);
                    unset($_SESSION['total_words']);
                    unset($_SESSION['file_url']);
                    unset($_SESSION['file_name']);
                    echo json_encode($return_messge, true);
                    exit();
                }
            }
        }
    }

    public function woocommerce_add_cart_item_data_own($cart_item_data, $product_id) {
        global $woocommerce;
        $custome_cart_data = array();
        if (isset($_SESSION['attach_id']) && !empty($_SESSION['attach_id'])) {
            $custome_cart_data['attach_id'] = $_SESSION['attach_id'];
            unset($_SESSION['attach_id']);
        }
        if (isset($_SESSION['total_words']) && !empty($_SESSION['total_words'])) {
            $custome_cart_data['total_words'] = $_SESSION['total_words'];
            unset($_SESSION['total_words']);
        }
        if (isset($_SESSION['file_url']) && !empty($_SESSION['file_url'])) {
            $custome_cart_data['file_url'] = $_SESSION['file_url'];
            unset($_SESSION['file_url']);
        }
        if (isset($_SESSION['file_name']) && !empty($_SESSION['file_name'])) {
            $custome_cart_data['file_name'] = $_SESSION['file_name'];
            unset($_SESSION['file_name']);
        }
        if (isset($_SESSION['attachment_anchor_tag_url']) && !empty($_SESSION['attachment_anchor_tag_url'])) {
            $custome_cart_data['attachment_anchor_tag_url'] = $_SESSION['attachment_anchor_tag_url'];
            unset($_SESSION['attachment_anchor_tag_url']);
        }
        $ppw_custome_cart_data_value = array('ppw_custome_cart_data' => $custome_cart_data);
        if (empty($custome_cart_data)) {
            return $cart_item_data;
        } else {
            if (empty($cart_item_data)) {
                return $ppw_custome_cart_data_value;
            } else {
                return array_merge($cart_item_data, $ppw_custome_cart_data_value);
            }
        }
    }

    public function woocommerce_get_cart_item_from_session_own($item, $values, $key) {
        if (array_key_exists('ppw_custome_cart_data', $values)) {
            $item['ppw_custome_cart_data'] = $values['ppw_custome_cart_data'];
        }
        return $item;
    }

    public function woocommerce_checkout_cart_item_quantity_own($product_name, $values, $cart_item_key) {
        if (count($values['ppw_custome_cart_data']) > 0) {
            $return_string = $product_name . "</a>";

            if (isset($values['ppw_custome_cart_data']['file_name']) && !empty($values['ppw_custome_cart_data']['file_name'])) {

                $return_string .= '<div><span><b>File Name: </b></span>';
                $return_string .= "<span>" . $values['ppw_custome_cart_data']['file_name'] . "</span></div>";
            }
            if (isset($values['ppw_custome_cart_data']['total_words']) && !empty($values['ppw_custome_cart_data']['total_words'])) {

                $return_string .= '<div><span><b>Total word: </b></span>';
                $return_string .= "<span>" . $values['ppw_custome_cart_data']['total_words'] . "</span></div>";
            }

            return $return_string;
        } else {
            return $product_name;
        }
    }

    public function woocommerce_add_order_item_meta_own($item_id, $values) {
        global $woocommerce, $wpdb;
        $user_custom_values = $values['ppw_custome_cart_data'];
        if (!empty($user_custom_values)) {
            foreach ($user_custom_values as $key => $value) {
                if ($key != "attach_id" && $key != "file_name") {
                    $key = ucwords(str_replace("_", " ", $key));
                    wc_add_order_item_meta($item_id, $key, $value);
                }
            }
            wc_add_order_item_meta($item_id, 'ppw_custome_cart_data', $user_custom_values);
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

}
