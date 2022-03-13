window.onload = function validateModal()
{
    let modalCheck = document.getElementsByClassName("errQs");
    console.log(modalCheck);
    if (modalCheck.length !== 0) {
        $('#amdQuestionModal').modal('show');
    }
}