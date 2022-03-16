document.getElementById("adminLogin").onsubmit = validateLogin;
document.getElementById("newUser").onsubmit = validate;

function validateLogin()
{
    let isValid = true;
    clearErrors();

    //validate email/username
    let userName = document.getElementById("username").value;
    if(userName === ""){
        document.getElementById("err-username").style.display = "block";
        isValid = false;
    }
    if(!validateEmail(userName)){
        document.getElementById("err-useremail").style.display = "block";
        isValid = false;
    }

    return isValid;
}

function validate()
{
    let isValid = true;
    clearErrors();

    //validate first name
    let fname = document.getElementById("newfname").value;
    if(fname === ""){
        document.getElementById("err-newfname").style.display = "block";
        isValid = false;
    }

    //validate last name
    let lname = document.getElementById("newlname").value;
    if(lname === ""){
        document.getElementById("err-newlname").style.display = "block";
        isValid = false;
    }

    //validate phone number
    let phone = document.getElementById("newphone").value;
    if(!validPhone(phone)){
        document.getElementById("err-newphone").style.display = "block";
        isValid = false;
    }

    //validate new user email
    let newEmail = document.getElementById("newemail").value;
    if(!validateEmail(newEmail)){
        document.getElementById("err-newemail").style.display = "block";
        isValid = false;
    }
    //validate new user password and make sure passwords match
    let newPassword = document.getElementById("newpass").value;
    let repeatNewPass = document.getElementById("newpass2").value;
    if(!checkPassword(newPassword)){
        document.getElementById("err-newpass").style.display = "block";
        isValid = false;
    }
    if(newPassword !== repeatNewPass){
        document.getElementById("err-repeatPass").style.display = "block";
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

function validateEmail(newEmail)
{
    let mailformat = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(newEmail.match(mailformat))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validPhone(phoneNum)
{
    let phonedash = /^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$/;
    let phoneTen = /^\d{10}$/;
    if(phoneNum.match(phonedash) || phoneNum.match(phoneTen))
    {
        return true;
    }
    else
    {
        return false;
    }
}
