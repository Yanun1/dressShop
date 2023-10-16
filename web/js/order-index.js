$(document).ready(function() {
    $('.status-column').each(function () {
        switch ($(this).text()) {
            case 'waiting':
                $(this).css('background-color', '#CCE5FF');
                break;
            case 'on the way':
                $(this).css('background-color', 'rgb(203, 249, 254)');
                break;
            case 'delivered':
                $(this).css('background-color', 'rgb(245, 270, 111)');
                break;
            case 'received':
                $(this).css('background-color', 'rgb(183, 239, 168)');
                break;
        }
    });

    $("button[type='reset']").click(function () {
        $('.search-inputs input').each(function () {
            $(this).attr('value', '');
        });
        $('.form-group').trigger('submit');
    });

    $('.image-column').click(function () {
        $('.select-product-widget .select-window img').attr('src', $(this).find('img').attr('src'));
        $('.select-product-widget').css('display', 'flex');
    });

    $(".close-block img").click(function () {
        $('.select-product-widget').css('display', 'none');
    });


});