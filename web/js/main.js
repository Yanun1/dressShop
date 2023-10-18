$(document).ready(function() {
    let productList;
    $.ajax({
        url: '/ajax/base',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            productList =  res;
        },
        error: function () {
            alert('Error!');
        }
    });
    // lracnum enq ordersi meji input dashtery stacac tablicayi toxeric
    function selectChange() {
        let valuesArr = $(this).val();
        for (let i = 1; i <= Object.keys(productList).length; i++) {
            if (productList[i]['id'] == valuesArr) {
                $(this).val(productList[i]['product']);
                $(this).attr('value',productList[i]['id']);
                $(this).parent().parent().find("input[name='Orders[price][]']").val(productList[i]['price']);
                $(this).parent().parent().find("input[name='saler']").val(productList[i]['user']['login']);
                $(this).parent().parent().find(".micro-image > img").attr('src', "http://dress-shop/images/" + $(this).attr('data-image'));
            }
        }
        changePrice($(this));
    }

    // - kochaky
    function removeForm() {
        if ($(".OrdersForm").length === 1) {
            return;
        }
        $(this).parent().remove();
        changeTotalCost();
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
        let newDiv = $(".OrdersForm:first-child").clone(true,true);

        // empting cloned inputs
        newDiv.find("input[type='Number']").val(0);
        newDiv.find("#orders-count").val(1);
        newDiv.find("input[name='saler']").val('None');
        newDiv.find(".productInput ").val('Select product');
        newDiv.find("img").attr('src', 'http://dress-shop/images/window_product_default.jpg');

        $(".ordersMain_container").append(newDiv);
        $(".ordersMain_container").append($('.ordersMain_container > button'));
    }

    // poxum enq selectov yntrac produkti tvyalnery
    function changePrice(element) {
        let price = Number(element.parent().parent().find("input[name='Orders[price][]']").val());
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

    // mi apranqi yndhanur patveri gumari hashvarkum
    function changePriceEvent() {
        let price = Number($(this).parent().parent().find("input[name='Orders[price][]']").val());
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

    $('.buyCost > button').click(function () {
        $('.productInput ').each(function () {
            $(this).val($(this).attr('value'));
        });
        $(this).trigger('submit');
    });

    $('.to-excel').click(function () {
        let ordersList = [];
        let i = 0;
        $('.ordersMain_container .OrdersForm').each(function () {
            ordersList[i] = [];
            ordersList[i]['Product'] = $(this).find('.productInput ').val();
            ordersList[i]['Saler'] = $(this).find("input[name='saler']").val();
            ordersList[i]['Price'] = $(this).find("input[name='Orders[price][]']").val();
            ordersList[i]['Image'] = $(this).find('.micro-image img').attr('src');
            ordersList[i]['Count'] = $(this).find('#orders-count').val();
            ordersList[i]['Total'] = $(this).find("input[name='sumName']").val();
            i++;
        });
        ordersList[i] = [];

        ordersList[i]['Total'] = $(".ordersMain input[name='totalCost']").val();

        let workbook = XLSX.utils.book_new();
        let worksheet = XLSX.utils.json_to_sheet(ordersList);
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet 1');
        XLSX.writeFile(workbook, 'orders.xlsx');
    });


    $(".add-row").click(duplicateForm);
    $(".remove-row").click(removeForm);
    $('.productInput').on('change', selectChange);
    $('#orders-count').on('input', changePriceEvent);
})