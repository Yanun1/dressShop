$(".reset-button").click(function () {
    $('.search-inputs input').each(function () {
        $(this).attr('value', '');
    });
    $('.search-inputs select').each(function () {
        $(this).val('');
    });
    localStorage.clear();
    $(this).parent().find('.btn-secondary').trigger('submit');
});