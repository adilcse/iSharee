//check email is valid or not
$("#email").keyup(e=>{
const mail=e.target.value;
const mailError=$('#email-error');
if(validateEmail(mail)){
    mailError.hide();
}else{
    mailError.show();
    mailError.children().first().text('invalid Email');
}
});

//email validator function
const validateEmail=(email)=> {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

//validate password
$("#password").keyup(e=>{
    const password = e.target.value;
    const passwordError = $('#password-error');
    if(password.length < 6){
        passwordError.show();
        passwordError.children().first().text('Enter atleast 6 charecter');
    }else{
        passwordError.hide();
    }
});

//submit form only if email and password is valid
$('form').submit(e=>{
    const email=$('#email').val();
    const password=$('#password').val();
    if(!validateEmail(email) || password.length < 6){
        e.preventDefault();
    }else
        return;
})