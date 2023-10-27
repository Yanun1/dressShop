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

    $('.update-employee').click(function () {
        let mainBlock = $(this).parent();
        $('.employee-column').css('width', '150px');
        $('.employee-column').find("input[name='orderEmployee']").val($('.employee-column').find(".btn-danger").attr('data-oldValue'));
        $('.employee-column').find('input').addClass('input-no');
        $('.employee-column').find('input').attr('readonly', 'readonly');
        $('.employee-column').find('button').hide();
        $('.employee-column').find('svg').show();

        mainBlock.parent().css('width', '200px')
        mainBlock.find('input').removeClass('input-no');
        mainBlock.find('button').show();
        //mainBlock.find('.btn-danger').attr('data-oldValue', $(this).parent().find("input[name='orderEmployee']").val());
        mainBlock.find('input').removeAttr('readonly');
        $(this).hide();
        mainBlock.find('input').focus();
    });

    $('.btn-danger').click(function (event) {
        event.preventDefault();
        let mainBlock = $(this).parent();
        mainBlock.parent().css('width', '150px');
        mainBlock.find("input[name='orderEmployee']").val($(this).attr('data-oldValue'));
        mainBlock.find('input').addClass('input-no');
        mainBlock.find('input').attr('readonly', 'readonly');
        mainBlock.find('button').hide();
        mainBlock.find('svg').show();
    });

    // $('.employee-name').blur(function (event) {
    //     let mainBlock = $(this).parent();
    //     mainBlock.parent().css('width', '150px')
    //     mainBlock.find('input').val(mainBlock.find('.btn-danger').attr('data-oldValue'));
    //     mainBlock.find('input').addClass('input-no');
    //     mainBlock.find('input').attr('readonly', 'readonly');
    //     mainBlock.find('button').hide();
    //     mainBlock.find('svg').show();
    // });

    $('.employee-column form button').hide();

});