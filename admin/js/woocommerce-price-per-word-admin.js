jQuery(function ($) {
    'use strict';
    $(function () {
        $('#_price_per_word').change(function () {
            if ($(this).is(':checked')) {
                $("label[for='_regular_price']").text('Price Per Word ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Price Per Word ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            } else {
                $("label[for='_regular_price']").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            }
        });
        $('.variations_tab a').click(function () {
            if ($('#_price_per_word').is(':checked')) {
                setTimeout(function () {
                    $(".variable_pricing p.form-row-first label").text('Price Per Word ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 2000);

            } else {
                setTimeout(function () {
                    $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 2000);
            }
        });
        $("#product-type").change(function () {
            if ($('#_price_per_word').is(':checked')) {
                setTimeout(function () {
                    $("label[for='_regular_price']").text('Price Per Word ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                    $(".variable_pricing p.form-row-first label").text('Price Per Word ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 2000);

            } else {
                setTimeout(function () {
                    $("label[for='_regular_price']").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                    $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                }, 2000);
            }
        });

        if ($('#_price_per_word').is(':checked')) {
            setTimeout(function () {
                $("label[for='_regular_price']").text('Price Per Word ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Price Per Word ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            }, 1000);

        } else {
            setTimeout(function () {
                $("label[for='_regular_price']").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
                $(".variable_pricing p.form-row-first label").text('Regular Price ' + woocommerce_price_per_word_params.woocommerce_currency_symbol_js);
            }, 1000);
        }
    });
});