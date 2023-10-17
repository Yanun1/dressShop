$(document).ready(function() {
    var productList;
    $.ajax({
        url: '/ajax/base',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            productList =  Object.values(res);
        },
        error: function () {
            alert('Error!');
        }
    });
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
        console.log(productList);
        $('.owl-carousel').trigger('destroy.owl.carousel');
        $('.owl-carousel').trigger('refresh.owl.carousel');

        $('.owl-carousel').owlCarousel({
            items:1,
        });

        for (let product of productList)
        {
            console.log($(this).parent().find('.product-column').val());
            if(product['product'] == $(this).parent().find('.product-column').val())
            {
                console.log('true'); return
            }
            $('.owl-carousel').trigger('add.owl.carousel', ["<img src='" + $(this).find('img').attr('src') + "' alt='photo'> </img>", 0]);
        }
        $('.owl-carousel').trigger('refresh.owl.carousel');

        $('.select-product-widget').css('display', 'flex');
    });

    $(".close-block img").click(function () {
        $('.select-product-widget').css('display', 'none');
    });

    $('.owl-carousel').owlCarousel({
        items:1,
    });

    $('.left-button').on('click', function() {
        $('.owl-carousel').trigger('prev.owl.carousel');
    });
    $('.right-button').on('click', function() {
        $('.owl-carousel').trigger('next.owl.carousel');
    });

});