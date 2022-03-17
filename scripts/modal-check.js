//This page handles the 'toggle question modal errQ' class stuff.

window.onload = function validateModal()
{
    let modalCheck = document.getElementsByClassName("errQs");
    console.log(modalCheck);
    if (modalCheck.length !== 0) {
        $('#amdQuestionModal').modal('show');
    }
}