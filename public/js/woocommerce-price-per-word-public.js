jQuery(function ($) {
    'use strict';
    $(function () {
        if (woocommerce_price_per_word_params.total_word.length != 0) {
            $(".single_variation_wrap").show();
            $(".woocommerce .quantity input[name='quantity']").val(woocommerce_price_per_word_params.total_word);
            $(".woocommerce .quantity input[name='quantity']").prop("readonly", true);
        }
        $(".variations select").change(function (event) {
            if (!$('input[name="file_uploaded"]').length) {
                setTimeout(function () {
                    $(".single_variation_wrap").hide();
                }, 2);
            } else {
                var quantity = $('input[name="quantity"]').val();
                setTimeout(function () {
                    $('input[name="quantity"]').val(quantity);
                }, 2);
            }
        });
        $("input[name=ppw_file_upload]").change(function (event) {
            if (!$('input[name="file_uploaded"]').length) {
                var input = $("<input>").attr("type", "hidden").attr("name", "submit_by_ajax").val("true");
                $(".variations_form").append($(input));
            }

            $(".variations_form").submit();
        });
        $(".variations_form").submit(function (event) {
            if ($('input[name="submit_by_ajax"]').length) {
                $("input[name='submit_by_ajax']").remove();
            } else {
                return true;
            }
            $("#ppw_loader").show();
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
                        $(".variations_form").append($(input_two));
                        $(".ppw_file_upload_div").hide();
                        $("#aewcppw_product_page_message").hide();
                        $(".single_variation_wrap").show();
                        if($( "#ppw_file_container" ).hasClass( "woocommerce-error" )) {
                            $('#ppw_file_container').removeClass('woocommerce-error');
                        }
                        $("#ppw_file_container").html(obj.message_content);
                        $("#ppw_file_container").show();
                        $(".woocommerce .quantity input[name='quantity']").val(obj.total_word);
                        $(".woocommerce .quantity input[name='quantity']").prop("readonly", true);
                    } else {
                        $("#ppw_file_container").addClass("woocommerce-error");
                        $("#ppw_file_container").html(obj.message_content);
                        $("#ppw_file_container").show();
                    }

                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });

        $("#ppw_remove_file").live("click", function () {
            var data = {
                action: 'ppw_remove',
                security: woocommerce_price_per_word_params.woocommerce_price_per_word_params_nonce,
                value: $("#ppw_remove_file").attr('data_file')
            };
            $.post(woocommerce_price_per_word_params.ajax_url, data, function (response) {
                var obj = $.parseJSON(response);
                if (obj.message == 'File successfully deleted') {
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