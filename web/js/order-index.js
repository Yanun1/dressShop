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

    $(".reset-button").click(function () {
        $('.search-inputs input').each(function () {
            $(this).attr('value', '');
        });
        localStorage.clear();
    });

    $('.image-column').click(function () {
        let owl = $('.owl-carousel');
        owl.owlCarousel('destroy');
        owl.html('');
        owl.owlCarousel({
            items:1,
        });

        owl.trigger('remove.owl.carousel', [0, true]).trigger('refresh.owl.carousel');
        for (let product of productList)
        {
            if(product['product'] == $(this).parent().find('.product-column').text())
            {
                if(product['images'].length == 0) {
                    $('.slider-buttons').hide();
                    break;
                }
                else {
                    $('.slider-buttons').show();
                    for (let image of product['images']) {
                        owl.trigger('add.owl.carousel', ["<img src='http://dress-shop/images/" + image['image'] + "' alt='photo'> </img>", 0]);
                    }
                }
            }
        }
        owl.trigger('add.owl.carousel', ["<img src='" + $(this).find('img').attr('src') + "' alt='photo'> </img>", 0]);


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