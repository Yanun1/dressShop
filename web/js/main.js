$(document).ready(function() {
var $formCounter = 1;
    function removeForm() {
        if($(".OrdersForm").length === 1) {
            return;
        }
        $(this).parent().remove();
    }
    function duplicateForm() {
        let newDiv = $(".OrdersForm:first-child").clone();
        newDiv.find('.btn-success').click(duplicateForm);
        newDiv.find('.btn-danger').click(removeForm);
        newDiv.find("[name='OrdersForm[products]']").attr('name', `OrdersForm[products${$formCounter}]`);
        newDiv.find("[name='OrdersForm[client]']").attr('name', `OrdersForm[client${$formCounter}]`);
        newDiv.find("[name='OrdersForm[price]']").attr('name', `OrdersForm[price${$formCounter}]`);
        newDiv.find("[name='OrdersForm[count]']").attr('name', `OrdersForm[count${$formCounter}]`);
        $(".ordersMain").append(newDiv);
        $(".ordersMain").append($('.ordersMain > button'));
        $formCounter++;
    }

    $(".btn-success").click(duplicateForm);
    $(".btn-danger").click(removeForm);

    $('#ordersform-price, #ordersform-count').on('blur', function () {
        let price = Number($('#ordersform-price').val());
        let count = Number($('#ordersform-count').val());

        if(price < 0 || price === 0) {
            $('#ordersform-price').val(1);
            price = 1;
        }
        if(count < 0 || count === 0) {
            $('#ordersform-count').val(1);
            count = 1;
        }

        let newVal = Number(price * count);
        $(".OrdersForm > input").val(newVal);
    });

});
