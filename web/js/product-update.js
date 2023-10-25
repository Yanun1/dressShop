// document.getElementsByClassName('checkbox')[0].addEventListener('click', function () {
//     let checkbox = document.getElementsByClassName('checkbox')[0];
//     if (checkbox.checked) {
//         document.getElementById('products-id_product').setAttribute("disabled", "disabled");
//     } else {
//         document.getElementById('products-id_product').removeAttribute('disabled');
//     }
// });


// $('.productInput').on('change', function () {
//     console.log($(this).val());
// });

$('.checkbox').on('click', function () {
    let checkbox = $('.checkbox')[0];
    if(checkbox.checked)
    {
        $('#products-id_product').attr('disabled', 'disabled');
    }
    else {
        $('#products-id_product').removeAttr('disabled')
    }
});

$('.remove-image').click(function () {
    $(this).parent().parent().remove();
    $('#imagesform-images').trigger('change');
});

$('#imagesform-images').on('change', function() {
    if(this.files.length + $('.current-photo > .images-span').length > 5) {
        $('.images-error').show();
        $('#imagesform-images').parent().addClass('no-margin');
        $('#imagesform-images').addClass('no-valid');
    }
    else {
        $('.images-error').hide();
        $('#imagesform-images').removeClass('no-valid');
    }
});


$('.push-update').on('click', function (event) {
    event.preventDefault();
    if($('.images-error').css('display') == 'none') {
        $(this).submit();
    }
});

$('.form-group > button').on('click', function (event) {
    event.preventDefault();
    $('.productInput').val($('.productInput').attr('value'));
    $(this).submit();
});