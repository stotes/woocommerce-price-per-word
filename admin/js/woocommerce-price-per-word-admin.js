jQuery(function ($) {
    'use strict';
    $(function () {

        if (typeof (woocommerce_price_per_word_params.aewcppw_word_character) != "undefined" && woocommerce_price_per_word_params.aewcppw_word_character !== null && woocommerce_price_per_word_params.aewcppw_word_character == 'word') {
            var wppw_product_type = 'Word ';
        } else if (typeof (woocommerce_price_per_word_params.aewcppw_word_character) != "undefined" && woocommerce_price_per_word_params.aewcppw_word_character !== null && woocommerce_price_per_word_params.aewcppw_word_character == 'character') {
            var wppw_product_type = 'Character ';
        } else {
            var wppw_product_type = 'Word ';
        }

        $('#_price_per_word_character_enable').change(function () {
            if ($('input[name="_price_per_word_character"][value="word"]').is(":checked")) {
                var wppw_product_type = 'Word ';
            }
            else {
                var wppw_product_type = 'Character ';
            }
            if ($(this).is(':checked')) {
                $("label[for='_regular_price']").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".custom_tab_woocommerce_price_word_character_tab").removeClass("custom_tab_woocommerce_price_word_character_tab_hidden");
                if ($(".custom_tab_woocommerce_price_word_character_tab ").hasClass("active")) {
                    $("#custom_tab_data_woocommerce_price_word_character_tab").show();
                }
            } else {
                var wppw_product_type = '';
                $("label[for='_regular_price']").text('Regular Price ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".custom_tab_woocommerce_price_word_character_tab").addClass("custom_tab_woocommerce_price_word_character_tab_hidden");
                $("#custom_tab_data_woocommerce_price_word_character_tab").hide();
            }
        });

        $('input[name="_price_per_word_character"]').click(function () {
            if ($(this).val() == 'word') {
                var wppw_product_type = 'Word ';
                $("label[for='_regular_price']").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            } else if ($(this).val() == 'character') {
                var wppw_product_type = 'Character ';
                $("label[for='_regular_price']").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            }
            else {
                var wppw_product_type = ' ';
                $("label[for='_regular_price']").text('Regular Price ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            }
        });

        $('.variations_tab a, .woocommerce_variation').click(function () {
            if ($('#_price_per_word_character_enable').is(':checked')) {
                setTimeout(function () {
                    $(".variable_pricing p.form-row-first label").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 5000);

            } else {
                setTimeout(function () {
                    $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 5000);
            }
        });

        $(document).on('change', '#variable_product_options', function () {
            if ($('#_price_per_word_character_enable').is(':checked')) {
                setTimeout(function () {
                    $("label[for='_regular_price']").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                    $(".variable_pricing p.form-row-first label").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 10000);

            } else {
                setTimeout(function () {
                    $("label[for='_regular_price']").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                    $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 10000);
            }
        });


        $("#product-type, #variable_product_options .woocommerce_variations").change(function () {
            if ($('#_price_per_word_character_enable').is(':checked')) {
                setTimeout(function () {
                    $("label[for='_regular_price']").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                    $(".variable_pricing p.form-row-first label").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 5000);

            } else {
                setTimeout(function () {
                    $("label[for='_regular_price']").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                    $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 5000);
            }
        });

        if ($('#_price_per_word_character_enable').is(':checked')) {
            setTimeout(function () {
                if ($('input[name="_price_per_word_character"][value="word"]').is(":checked")) {
                    var wppw_product_type = 'Word ';
                }
                else {
                    var wppw_product_type = 'Character ';
                }
                $("label[for='_regular_price']").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            }, 5000);

        } else {
            setTimeout(function () {
                $("label[for='_regular_price']").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            }, 3000);
        }
    });
});