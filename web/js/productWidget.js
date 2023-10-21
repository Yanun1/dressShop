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
        $('.owl-item img').attr('src', "http://dress-shop/images/" + $(this).parent().attr('src'));
    });

    $('.catalog').dcAccordion();

    $('.owl-carousel').owlCarousel({
        items:1,
    });
    $('.slider-buttons').hide();

    $('.left-button').on('click', function() {
        $('.owl-carousel').trigger('prev.owl.carousel');
    });
    $('.right-button').on('click', function() {
        $('.owl-carousel').trigger('next.owl.carousel');
    });

    let productList;
    sessionStorage.getItem('productList', productList);
    if (isNaN(productList)) {
        $.ajax({
            url: '/ajax/base',
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

    $('.item').click(function () {
        let owl = $('.owl-carousel');
        owl.owlCarousel('destroy');
        owl.html('');
        owl.owlCarousel({
            items:1,
        });
        owl.trigger('remove.owl.carousel', [0, true]);
        let productMain;
        for(let product of productList)
        {
            if(product['id'] == $(this).parent().val())
            {
                productMain = product;
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
        console.log(productList);
        owl.trigger('add.owl.carousel', ["<img src='" + 'http://dress-shop/images/' + productMain['image'] + "' alt='photo'> </img>", 0]);
        owl.trigger('refresh.owl.carousel');

    });

});


