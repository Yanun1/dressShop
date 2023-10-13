$(document).ready(function() {
    $('#orders-id_product').click(function () {
        $('.select-product-widget').css('display', 'flex');
    });

    $('.black-background').click(function () {
        if(confirm('do you really want to exit?')) {
            $('.select-product-widget').css('display', 'none');
        }
    });

    $('.catalog').dcAccordion();
});