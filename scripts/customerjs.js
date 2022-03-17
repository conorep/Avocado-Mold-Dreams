//execute validation for customer info change form
document.getElementById("changeInfo").onsubmit = validateChanges;

//execute validation for customer info change form
document.getElementById("newPass").onsubmit = validateNewPass;

//execute validation for customer info change form
document.getElementById("addAddress").onsubmit = validateAddress;

function validateChanges()
{
    let isValid = true;
    clearErrors();

    //get the selected option
    let target = document.getElementById("choices").value;

    //validate first name change
    let emptyfName = document.getElementById("newInfo").value;
    if(target === "f_name" && emptyfName === ""){
        document.getElementById("err-fname").style.display = "block";
        isValid = false;
    }

    //validate last name change
    let emptylName = document.getElementById("newInfo").value;
    if(target === "l_name" && emptylName === ""){
        document.getElementById("err-lname").style.display = "block";
        isValid = false;
    }

    //validate email change
    //if email is empty...
    let invalidEmail = document.getElementById("newInfo").value;
    if(target === "user_email" && invalidEmail === ""){
        document.getElementById("err-email").style.display = "block";
        isValid = false;
    }
    //if email is in incorrect format
    if(target === "user_email" && !validateEmail(invalidEmail)){
        document.getElementById("err-email").style.display = "block";
        isValid = false;
    }

    //validate phone change
    //if phone is empty...
    let invalidPhone = document.getElementById("newInfo").value;
    if(target === "user_phone" && invalidPhone === ""){
        document.getElementById("err-phone").style.display = "block";
        isValid = false;
    }
    //if phone is in incorrect format
    if(target === "user_phone" && !validPhone(invalidPhone)){
        document.getElementById("err-phone").style.display = "block";
        isValid = false;
    }

    return isValid;
}

function validateNewPass()
{
    let isValid = true;
    clearErrors();

    let firstPass = document.getElementById("firstPass").value;
    let secondPass = document.getElementById("secondPass").value;

    //check that firstPass is not empty
    if(firstPass === ""){
        document.getElementById("err-firstPass").style.display = "block";
        isValid = false;
    }
    //check that passwords match
    if(firstPass !== secondPass){
        document.getElementById("err-passMatch").style.display = "block";
        isValid = false;
    }
    //check that passwords fit criteria
    if(firstPass === secondPass && !checkPassword(firstPass)){
        document.getElementById("err-passCriteria").style.display = "block";
        isValid = false;
    }


    return isValid;
}

function validateAddress()
{
    let isValid = true;
    clearErrors();

    let addAddress = document.getElementById("addressAdd").value;

    //check that firstPass is not empty
    if(addAddress === ""){
        document.getElementById("err-addAddress").style.display = "block";
        isValid = false;
    }

    return isValid
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