jQuery(function ($) {
    'use strict';
    $(function () {
        $('#_price_per_word').change(function () {
            if ($(this).is(':checked')) {
                $("label[for='_regular_price']").text('Price Per Word');
                $(".variable_pricing p.form-row-first label").text('Price Per Word');
            } else {
                $("label[for='_regular_price']").text('Regular Price');
                $(".variable_pricing p.form-row-first label").text('Regular Price');
            }
        });
        $('.variations_tab a').click(function () {
            if ($('#_price_per_word').is(':checked')) {
                setTimeout(function () {
                    $(".variable_pricing p.form-row-first label").text('Price Per Word');
                }, 2000);

            } else {
                setTimeout(function () {
                    $(".variable_pricing p.form-row-first label").text('Regular Price');
                }, 2000);
            }
        });
        $("#product-type").change(function () {
            if ($('#_price_per_word').is(':checked')) {
                setTimeout(function () {
                    $("label[for='_regular_price']").text('Price Per Word');
                    $(".variable_pricing p.form-row-first label").text('Price Per Word');
                }, 2000);

            } else {
                setTimeout(function () {
                    $("label[for='_regular_price']").text('Regular Price');
                    $(".variable_pricing p.form-row-first label").text('Regular Price');
                }, 2000);
            }
        });

        if ($('#_price_per_word').is(':checked')) {
            setTimeout(function () {
                $("label[for='_regular_price']").text('Price Per Word');
                $(".variable_pricing p.form-row-first label").text('Price Per Word');
            }, 1000);

        } else {
            setTimeout(function () {
                $("label[for='_regular_price']").text('Regular Price');
                $(".variable_pricing p.form-row-first label").text('Regular Price');
            }, 1000);
        }
        
        
        


    });
});