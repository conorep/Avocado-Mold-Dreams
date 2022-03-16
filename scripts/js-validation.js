
//execute validation function for login
document.getElementById("adminLogin").onsubmit = validateLogin;

//execute validation function for new account creation
document.getElementById("newUser").onsubmit = validateNewUser;

//execute validation for question form
document.getElementById("questionForm").onsubmit = validateQuestion;

function validateLogin()
{
    let isValid = true;
    clearErrors();

    //validate email/username
    let userName = document.getElementById("username").value;
    let userEmail = document.getElementById("username").value;
    if(userName === ""){
        document.getElementById("err-username").style.display = "block";
        isValid = false;
    }
    if(!validateEmail(userEmail) && userEmail !== ""){
        document.getElementById("err-useremail").style.display = "block";
        isValid = false;
    }

    return isValid;
}

function validateNewUser()
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

function validateQuestion()
{
    let isValid = true;
    clearErrors();

    //validate question field
    let question = document.getElementById("questionContent").value;
    if(question === ""){
        document.getElementById("err-question").style.display = "block";
        isValid = false;
    }

    //validate name field
    let name = document.getElementById("questionUserInput").value;
    if(name === ""){
        document.getElementById("err-name").style.display = "block";
        isValid = false;
    }

    //validate email field
    //if email field is empty
    let emptyEmail = document.getElementById("questionEmailInput").value;
    if(emptyEmail === ""){
        document.getElementById("err-emptyEmail").style.display = "block";
        isValid = false;
    }

    //if email is invalid
    let invalidEmail = document.getElementById("questionEmailInput").value;
    if(!validateEmail(invalidEmail) && invalidEmail !== ""){
        document.getElementById("err-invalidEmail").style.display = "block";
        isValid = false;
    }

    return isValid;
}



/*Helper functions*/

//clears errors messages
function clearErrors()
{
    //Clear all error messages
    let errors = document.getElementsByClassName("js-err");
    for (let i=0; i<errors.length; i++) {
        errors[i].style.display = "none";
    }
}

//function for checking password requirements
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

//function for validating email format
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

//function for validating phone format
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
