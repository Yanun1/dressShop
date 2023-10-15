$(document).ready(function() {
    var productId;
    var productInput;


    $('.productInput').click(function () {
        $('.select-product-widget').css('display', 'flex');
        productInput = $(this);
    });

    $('.black-background').click(function () {
            $('.select-product-widget').css('display', 'none');
    });

    $('.cancel-window').click(function () {
        $('.select-product-widget').css('display', 'none');
    });

    $('.choose-window').click(function () {
        productInput.val(productId).trigger("change");
        $('.select-product-widget').css('display', 'none');
    });

    $('.catalog a').click(function () {
        productId = $(this).parent().val() + ' ' + $(this).parent().attr('src');
        $('.product-info img').attr('src', "http://dress-shop/images/" + $(this).parent().attr('src'));
    });

    $('.catalog').dcAccordion();
});