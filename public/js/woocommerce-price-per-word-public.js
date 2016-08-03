jQuery(function ($) {
    'use strict';
    $(function () {
        $(".cart").addClass("wppw_cart");

        if (typeof (woocommerce_price_per_word_params.aewcppw_word_character) != "undefined" && woocommerce_price_per_word_params.aewcppw_word_character !== null && woocommerce_price_per_word_params.aewcppw_word_character == 'word') {
            if (typeof (woocommerce_price_per_word_params.total_word) != "undefined" && woocommerce_price_per_word_params.total_word !== null && woocommerce_price_per_word_params.total_word > 0) {
                $(".woocommerce .quantity input[name='quantity']").val(woocommerce_price_per_word_params.total_word);
                $(".woocommerce .quantity input[name='quantity']").prop("readonly", true);
                $(".single_variation_wrap").show();
                $(".single_add_to_cart_button").parent('div').show();
            } else {
                if (typeof (woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty) != "undefined" && woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty !== null && woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty == 'no') {
                    $(".single_variation_wrap").hide();
                    $(".single_add_to_cart_button").parent('div').hide();
                }
            }
        } else {
            if (typeof (woocommerce_price_per_word_params.total_character) != "undefined" && woocommerce_price_per_word_params.total_character !== null && woocommerce_price_per_word_params.total_character > 0) {
                $(".woocommerce .quantity input[name='quantity']").val(woocommerce_price_per_word_params.total_character);
                $(".woocommerce .quantity input[name='quantity']").prop("readonly", true);
                $(".single_variation_wrap").show();
                $(".single_add_to_cart_button").parent('div').show();
            } else {
                if (typeof (woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty) != "undefined" && woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty !== null && woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty == 'no') {
                    $(".single_variation_wrap").hide();
                    $(".single_add_to_cart_button").parent('div').hide();
                }
            }
        }
        if (typeof (woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty) != "undefined" && woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty !== null && woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty == 'no') {
            $(".single_variation_wrap").hide();
            $(".single_add_to_cart_button").parent('div').hide();
        } else {
            $(".single_variation_wrap").show();
            $(".single_add_to_cart_button").parent('div').show();
        }
        $(".variations select").change(function (event) {
            if (!$("#ppw_remove_file").length) {
                setTimeout(function () {
                    if (typeof (woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty) != "undefined" && woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty !== null && woocommerce_price_per_word_params.aewcppw_allow_users_to_enter_qty == 'no') {
                        $(".single_variation_wrap").hide();
                        $(".ppw_total_price").hide();
                    } else {
                        $(".single_variation_wrap").show();
                        $(".ppw_total_price").show();
                    }

                    if ($(event.currentTarget).val() != '') {
                        $(event.currentTarget).parents("form").find(".ppw_total_price").hide();
                        $(event.currentTarget).parents("form").find("#aewcppw_product_page_message").show();
                        $(event.currentTarget).parents("form").find(".ppw_file_upload_div").show();
                    }
                    else {
                        $(event.currentTarget).parents("form").find("#aewcppw_product_page_message").hide();
                        $(event.currentTarget).parents("form").find(".ppw_file_upload_div").hide();

                    }
                }, 2);
            } else {
                var variations_select = $(".woocommerce div.product form.cart .variations select option:selected").attr("value");
                if (typeof (woocommerce_price_per_word_params.aewcppw_word_character) != "undefined" && woocommerce_price_per_word_params.aewcppw_word_character !== null && woocommerce_price_per_word_params.aewcppw_word_character == 'word') {
                    if (typeof (woocommerce_price_per_word_params.total_word) != "undefined" && woocommerce_price_per_word_params.total_word !== null && woocommerce_price_per_word_params.total_word > 0) {
                        var quantity = woocommerce_price_per_word_params.total_word;
                    } else {
                        var quantity = $('input[name="quantity"]').val();
                    }
                } else {
                    if (typeof (woocommerce_price_per_word_params.total_character) != "undefined" && woocommerce_price_per_word_params.total_character !== null && woocommerce_price_per_word_params.total_character > 0) {
                        var quantity = woocommerce_price_per_word_params.total_character;
                    } else {
                        var quantity = $('input[name="quantity"]').val();
                    }
                }
                setTimeout(function () {
                    $('input[name="quantity"]').val(quantity);
                    var product_price = $(".single_variation > span.price > .amount").html().replace(/[^0-9\.]+/g, '');
                    var total_amount = product_price * quantity;
                    var decimals_point = 2;
                    if (typeof (woocommerce_price_per_word_params.woocommerce_price_num_decimals) != "undefined" && woocommerce_price_per_word_params.woocommerce_price_num_decimals !== null && woocommerce_price_per_word_params.woocommerce_price_num_decimals > 0) {
                        decimals_point = woocommerce_price_per_word_params.woocommerce_price_num_decimals;
                    }
                    $(".ppw_total_amount").html(woocommerce_price_per_word_params.woocommerce_currency_symbol_js + total_amount.toFixed(decimals_point));
                    if (variations_select.length > 0) {
                        $(".ppw_total_price").show();
                    } else {
                        $(".ppw_total_price").hide();
                    }
                    $(".ppw_file_upload_div").hide();

                }, 2);
            }
        });

        $("input[name=ppw_file_upload]").change(function (event) {
            $("#ppw_loader").show();
            if (!$('input[name="file_uploaded"]').length) {
                var input = $("<input>").attr("type", "hidden").attr("name", "submit_by_ajax").val("true");
                $(".wppw_cart").append(input);
            }
            setTimeout(function () {
                $(event.currentTarget).parents("form.wppw_cart").submit();
            }, 1500);
        });

        $(".wppw_cart").submit(function (event) {
            if ($('input[name="submit_by_ajax"]').length) {
                $("input[name='submit_by_ajax']").remove();
                event.preventDefault();
            } else {
                return true;
            }
            var formData = new FormData();
            formData.append("action", "ppw_uploads");
            var fileInputElement = $(this).find("#ppw_file_upload_id")[0];
            formData.append("file", fileInputElement.files[0]);
            formData.append("name", fileInputElement.files[0].name);
            formData.append("security", woocommerce_price_per_word_params.woocommerce_price_per_word_params_nonce);
            formData.append("product_id", $("input[name='add-to-cart']").val());
            formData.append("variation_id", $("input[name='variation_id']").val());
            $.ajax({
                url: woocommerce_price_per_word_params.ajax_url,
                type: 'POST',
                data: formData,
                async: false,
                success: function (data) {
                    var obj = $.parseJSON(data);
                    if (obj.message == "File successfully uploaded") {
                        var input_two = $("<input>").attr("type", "hidden").attr("name", "file_uploaded").val(obj.url);
                        $(".wppw_cart").append(input_two);
                        $(".ppw_file_upload_div").hide();
                        $("#aewcppw_product_page_message").hide();
                        $(".single_variation_wrap").show();
                        if ($("#ppw_file_container").hasClass("woocommerce-error")) {
                            $('#ppw_file_container').removeClass('woocommerce-error');
                        }
                        $("#ppw_file_container").html(obj.message_content);
                        $("#ppw_file_container").show();

                        if (typeof (obj.aewcppw_word_character) != "undefined" && obj.aewcppw_word_character !== null && obj.aewcppw_word_character == 'word') {
                            $(".woocommerce .quantity input[name='quantity']").val(obj.total_word);
                        } else {
                            $(".woocommerce .quantity input[name='quantity']").val(obj.total_character);
                        }

                        $(".woocommerce .quantity input[name='quantity']").prop("readonly", true);
                        $(".ppw_total_amount").html(obj.product_price);
                        $(".ppw_total_price").show();
                        $(".single_variation_wrap").show();
                        $(".single_add_to_cart_button").parent('div').show();

                        //show quantity and cart button for the simple product
                        $(".woocommerce .quantity").show();
                        $(".woocommerce .single_add_to_cart_button").show();

                    } else {
                        $("#ppw_file_container").addClass("woocommerce-error");
                        if (typeof (obj.message) != "undefined" && obj.message !== null) {
                            $("#ppw_file_container").html(obj.message);
                        } else {
                            $("#ppw_file_container").html(obj.message_content);
                        }
                        $("#ppw_file_container").show();
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
            $("#ppw_loader").hide();
            return false;
        });

        $("#ppw_remove_file").live("click", function (event) {
            event.preventDefault();
            var data = {
                action: 'ppw_remove',
                security: woocommerce_price_per_word_params.woocommerce_price_per_word_params_nonce,
                value: $("#ppw_remove_file").attr('data_file')
            };
            $.post(woocommerce_price_per_word_params.ajax_url, data, function (response) {
                var obj = $.parseJSON(response);
                if (obj.message == 'File successfully deleted') {
                    $(".ppw_total_price").hide();
                    $(".woocommerce .quantity input[name='quantity']").val(0);
                    $(".woocommerce .quantity input[name='quantity']").prop("readonly", true);
                    $("input[name='file_uploaded']").remove();
                    $("#ppw_file_container").html('');
                    $("#ppw_file_container").hide();
                    $(".ppw_file_upload_div").show();
                    $("#aewcppw_product_page_message").show();
                    $(".single_variation_wrap").hide();
                    location.reload();
                }
            });
            return false;
        });

        $("form.wppw_cart").each(function () {
            if ($(this).hasClass("variations_form")) {
                $(this).find("#aewcppw_product_page_message").hide();
                $(this).find(".ppw_file_upload_div").hide();
            }
            else if ($(this).find("#aewcppw_product_page_message").length) {

                if ($.trim($(this).find("#ppw_file_container").text()) != '') {
                    $(this).find("#aewcppw_product_page_message").hide();
                    $(this).find(".ppw_file_upload_div").hide();
                    $(this).find(".quantity").show();
                    $(this).find(".single_add_to_cart_button").show();
                }
                else {
                    $(this).find("#aewcppw_product_page_message").show();
                    $(this).find(".ppw_file_upload_div").show();
                    $(this).find(".quantity").hide();
                    $(this).find(".single_add_to_cart_button").hide();
                }
            }
        })
    });
});