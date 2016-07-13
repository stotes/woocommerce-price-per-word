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

        $('#_price_per_word').change(function () {
            if ($(this).is(':checked')) {
                $("label[for='_regular_price']").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Price Per ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".word_count_cap").show();
            } else {
                $("label[for='_regular_price']").text('Regular Price ' + wppw_product_type + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".word_count_cap").hide();
            }
        });
        $('.variations_tab a, .woocommerce_variation').click(function () {
            if ($('#_price_per_word').is(':checked')) {
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
            if ($('#_price_per_word').is(':checked')) {
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
            if ($('#_price_per_word').is(':checked')) {
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

        if ($('#_price_per_word').is(':checked')) {
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
});