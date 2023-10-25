$(document).ready(function() {
    let productList;
    sessionStorage.getItem('productList', productList);
    if (isNaN(productList)) {
        $.ajax({
            url: '/ajax/base',
            data: {orderProduct:$('.image-header').attr('value')},
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                productList =  Object.values(res);
                sessionStorage.setItem('productList', productList);
            },
            error: function () {
                alert('Error!');
            }
        });
    }

    $('.image-column').click(function () {
        let owl = $('.owl-carousel');
        owl.owlCarousel('destroy');
        owl.html('');
        owl.owlCarousel({
            items:1,
        });

        owl.trigger('remove.owl.carousel', [0, true]);
        for (let product of productList)
        {
            if(product['id'] == $(this).parent().find('.id-column').text())
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
                    break;
                }
            }
        }
        owl.trigger('add.owl.carousel', ["<img src='" + $(this).find('img').attr('src') + "' alt='photo'> </img>", 0]);

        owl.trigger('refresh.owl.carousel');

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