$(document).ready(function() {
    let productId;
    let productInput;
    let productImage;


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
        productInput.attr('data-image', productImage);
        $('.select-product-widget').css('display', 'none');
        productInput.val(productId).trigger("change");
    });

    $('.catalog a').click(function () {
        productId = $(this).parent().val();
        productImage = $(this).parent().attr('src');
        $('.product-info img').attr('src', "http://dress-shop/images/" + $(this).parent().attr('src'));
    });

    $('.catalog').dcAccordion();
});