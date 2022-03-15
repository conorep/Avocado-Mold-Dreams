document.getElementById("newUser").onsubmit = validate;
$validation = new ValidationFuntions;
function validate()
{
    let isValid = true;
    clearErrors();
    //validate password
    let password = document.getElementById("newpass").value;
    if(!checkPassword(password)){
        document.getElementById("err-newpass").style.display = "block";
        isValid = false;
    }
    return isValid;

}

function clearErrors()
{
    //Clear all error messages
    let errors = document.getElementsByClassName("err");
    for (let i=0; i<errors.length; i++) {
        errors[i].style.display = "none";
    }
}

function checkPassword(newPass)
{
    let validPass =  /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/;
    if(newPass.match(validPass))
    {
        return true;
    }
    else
    {
        return false;
    }
}