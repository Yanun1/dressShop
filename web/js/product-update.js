// document.getElementsByClassName('checkbox')[0].addEventListener('click', function () {
//     let checkbox = document.getElementsByClassName('checkbox')[0];
//     if (checkbox.checked) {
//         document.getElementById('products-id_product').setAttribute("disabled", "disabled");
//     } else {
//         document.getElementById('products-id_product').removeAttribute('disabled');
//     }
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