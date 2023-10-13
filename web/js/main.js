$(document).ready(function() {
    var productList;
    $.ajax({
        url: '/ajax/base',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            productList = res;
            // res.forEach(function (temp) {
            //     $('#orders-id_product').append(`<option value="${temp['id']}"> ${temp['product']}</option>`);
            // });
        },
        error: function () {
            alert('Error!');
        }
    });

    // lracnum enq ordersi meji input dashtery stacac tablicayi toxeric
    function selectChange() {
        for (let i = 0; i < productList.length; i++) {
            if (productList[i]['id'] == $(this).val()) {
                $(this).parent().parent().find("input[name='price']").val(productList[i]['price']);
                $(this).parent().parent().find("input[name='saler']").val(productList[i]['user']['login']);
            }
        }
        changePrice($(this));
    }

    // - kochaky
    function removeForm() {
        if ($(".OrdersForm").length === 1) {
            return;
        }
        changeTotalCost();
        $(this).parent().remove();
    }
    function changeTotalCost(){
        let costValues = 0;
        $('.costInput').each(function () {
            costValues += Number($(this).val());
        });

        $("input[name='totalCost']").val(costValues);
    }


    // + kochaky
    function duplicateForm() {
        let newDiv = $(".OrdersForm:first-child").clone();

        // adding events again for new buttons
        newDiv.find('.btn-success').click(duplicateForm);
        newDiv.find('.btn-danger').click(removeForm);
        newDiv.find('#orders-id_product').change(selectChange);
        newDiv.find('#orders-count').on('input', changePriceEvent);

        // empting cloned inputs
        newDiv.find("input[type='Number']").val(0);
        newDiv.find("#orders-count").val(1);
        newDiv.find("input[name='saler']").val('None');

        $(".ordersMain_container").append(newDiv);
        $(".ordersMain_container").append($('.ordersMain_container > button'));
    }

    // poxum enq selectov yntrac produkti tvyalnery
    function changePrice(element) {
        let price = Number(element.parent().parent().find("input[name='price']").val());
        let count = Number(element.parent().parent().find("input[name*='Orders[count']").val());

        if (price < 0 || price === 0) {
            price = 0;
        }
        if (count < 0) {
            element.parent().parent().find("input[name*='Orders[count']").val(0);
            count = 1;
        }

        let newVal = price * count;
        element.parent().parent().find("input[name='sumName']").val(newVal);

        changeTotalCost();
    }
    function changePriceEvent() {
        let price = Number($(this).parent().parent().find("input[name='price']").val());
        let count = Number($(this).parent().parent().find("input[name*='Orders[count']").val());

        if (price < 0 || price === 0) {
            price = 0;
        }
        if (count < 0) {
            $(this).parent().parent().find("input[name*='Orders[count']").val(0);
            count = 1;
        }

        let newVal = price * count;
        $(this).parent().parent().find("input[name='sumName']").val(newVal);

        changeTotalCost();
    }

    $(".btn-success").click(duplicateForm);
    $(".btn-danger").click(removeForm);
    $('#orders-id_product').on('input', selectChange);
    $('#orders-count').on('input', changePriceEvent);

    // $('html').on('mousemove',function () {
    //     console.log($(this));
    //     if($(this).text() == '') {
    //         $(this).find('#orders-id_product').css('border-color', '#ced4da');
    //     }
    //     else {
    //         $(this).
    //         find('#orders-id_product').css('border-color', 'red');
    //     }
    // });

})