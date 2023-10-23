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


});