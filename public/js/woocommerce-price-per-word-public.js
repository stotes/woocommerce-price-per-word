jQuery(function ($) {
    'use strict';
    $(function () {
        $( ".cart" ).addClass( "wppw_cart" );
        if (woocommerce_price_per_word_params.total_word.length != 0) {
            $(".single_variation_wrap").show();
            $(".woocommerce .quantity input[name='quantity']").val(woocommerce_price_per_word_params.total_word);
            $(".woocommerce .quantity input[name='quantity']").prop("readonly", true);
        }
        $(".variations select").change(function (event) {
            if (!$("#ppw_remove_file").length) {
                setTimeout(function () {
                    $(".single_variation_wrap").hide();
                    $(".ppw_total_price").hide();
                    $("#aewcppw_product_page_message").show();
                    $("#ppw_file_upload_div").show();
                }, 2);
            } else {
                var variations_select = $(".woocommerce div.product form.cart .variations select option:selected").attr("value");
                if (typeof (woocommerce_price_per_word_params.total_word) != "undefined" && woocommerce_price_per_word_params.total_word !== null && woocommerce_price_per_word_params.total_word > 0) {
                    var quantity = woocommerce_price_per_word_params.total_word;
                } else {
                    var quantity = $('input[name="quantity"]').val();
                }
                setTimeout(function () {
                    $('input[name="quantity"]').val(quantity);
                    var product_price = $(".single_variation > span.price > .amount").html().replace(/[^0-9\.]+/g, '');
                    var total_amount = product_price * quantity;
                    $(".ppw_total_amount").html(woocommerce_price_per_word_params.woocommerce_currency_symbol_js + total_amount.toFixed(2));
                    if (variations_select.length > 0) {
                        $(".ppw_total_price").show();
                    } else {
                        $(".ppw_total_price").hide();
                    }
                  $("#ppw_file_upload_div").hide();

                }, 2);
            }
        });
        $(document).ready(function () {
            if (woocommerce_price_per_word_params.is_product_type_variable === 'true') {
                var variations_select = $(".woocommerce div.product form.cart .variations select option:selected").attr("value");
                if (variations_select.length > 0) {
                    $("#aewcppw_product_page_message").show();
                    $(".ppw_file_upload_div").show();
                } else {
                    $("#aewcppw_product_page_message").hide();
                    $(".ppw_file_upload_div").hide();
                    $(".ppw_total_price").hide();
                }
                $('.variations select').on('change', function (e) {
                    if (this.value.length === 0) {
                        $("#aewcppw_product_page_message").hide();
                        $(".ppw_file_upload_div").hide();
                        $(".ppw_total_price").hide();
                    } else {
                        if (!$("#ppw_remove_file").length) {
                            $("#aewcppw_product_page_message").show();
                            $(".ppw_file_upload_div").show();

                        } else {
                            $(".ppw_total_price").show();
                        }
                    }
                });
            } else {

                if ($("#ppw_remove_file").length > 0) {
                    $("#aewcppw_product_page_message").hide();
                    $(".ppw_file_upload_div").hide();
                    $(".ppw_total_price").show();
                } else {
                    $("#aewcppw_product_page_message").show();
                    $(".ppw_file_upload_div").show();
                }
                setTimeout(function () {
                    var quantity = $('input[name="quantity"]').val();
                    $('input[name="quantity"]').val(quantity);
                    var single_variation_price = $(".single_variation > span.price > .amount").html();
                    if (typeof (single_variation_price) != "undefined" && single_variation_price !== null && single_variation_price.length > 0) {
                        var product_price = $(".single_variation > span.price > .amount").html().replace(/[^0-9\.]+/g, '');
                    } else {
                        var product_price = $(".price .amount").html().replace(/[^0-9\.]+/g, '');
                    }
                    var total_amount = product_price * quantity;
                    $(".ppw_total_amount").html(woocommerce_price_per_word_params.woocommerce_currency_symbol_js + total_amount.toFixed(2));
                    $(".ppw_total_price").show();
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
                $(".wppw_cart").submit();
            }, 2 );
        });

        $(".wppw_cart").submit(function (event) {
            if ($('input[name="submit_by_ajax"]').length) {
                $("input[name='submit_by_ajax']").remove();
            } else {
                return true;
            }
            event.preventDefault();
            var formData = new FormData();
            formData.append("action", "ppw_uploads");
            var fileInputElement = document.getElementById("ppw_file_upload_id");
            formData.append("file", fileInputElement.files[0]);
            formData.append("name", fileInputElement.files[0].name);
            formData.append("security", woocommerce_price_per_word_params.woocommerce_price_per_word_params_nonce);
            $.ajax({
                url: woocommerce_price_per_word_params.ajax_url,
                type: 'POST',
                data: formData,
                async: false,
                success: function (data) {
                    var obj = $.parseJSON(data);
                    $("#ppw_loader").hide();
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
                        $(".woocommerce .quantity input[name='quantity']").val(obj.total_word);
                        $(".woocommerce .quantity input[name='quantity']").prop("readonly", true);

                        var quantity = $('input[name="quantity"]').val();

                        $('input[name="quantity"]').val(quantity);
                        var single_variation_price = $(".single_variation > span.price > .amount").html();
                        if (typeof (single_variation_price) != "undefined" && single_variation_price !== null && single_variation_price.length > 0) {
                            var product_price = $(".single_variation > span.price > .amount").html().replace(/[^0-9\.]+/g, '');
                        } else {
                            var product_price = $(".price .amount").html().replace(/[^0-9\.]+/g, '');
                        }

                        var total_amount = product_price * quantity;
                        $(".ppw_total_amount").html(woocommerce_price_per_word_params.woocommerce_currency_symbol_js + total_amount.toFixed(2));
                        $(".ppw_total_price").show();


                    } else {
                        $("#ppw_file_container").addClass("woocommerce-error");
                        if (typeof (obj.message) != "undefined" && obj.message !== null) {
                            $("#ppw_file_container").html(obj.message);
                        } else {
                            $("#ppw_file_container").html(obj.message_content);
                        }
                        $("#ppw_file_container").show();
                    }
                    $("input[name=ppw_file_upload]").val("");

                },
                cache: false,
                contentType: false,
                processData: false
            });
            console.log(false);
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
                }
            });
            return false;
        });
    });
});