$(document).ready(function() {
    function duplicateForm() {
        let newDiv = $(".OrdersForm:first-child").clone();
        newDiv.find('.btn-success').click(duplicateForm);
        $(".newForm").append(newDiv);
        // $(this).parent;
    }
    function removeForm(ev) {
        console.log(ev);
    }
    $(".btn-success").click(duplicateForm);
    $(".btn-danger").click(removeForm);
   
});
