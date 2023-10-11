$(document).ready(function() {
    function removeForm() {
        console.log('remove');
        if($(".OrdersForm").length === 1) {
            return;
        }
        $(this).parent().remove();
    }
    function duplicateForm() {
        console.log('duplicate');
        let newDiv = $(".OrdersForm:first-child").clone();
        newDiv.find('.btn-success').click(duplicateForm);
        newDiv.find('.btn-danger').click(removeForm);
        console.log($(".ordersMain"));
        $(".ordersMain").append(newDiv);
    }

    $(".btn-success").click(duplicateForm);
    $(".btn-danger").click(removeForm);
   
});
