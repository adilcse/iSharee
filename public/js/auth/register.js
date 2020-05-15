const validateName=(name)=>{
    return (name.length > 3 );
}

const validateMobile=(mobile)=>{
    return (mobile.length === 10 && !isNaN(mobile));
}

const validateEmail=(email)=> {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

const validatePassword=(password)=>{
    return (password.length >= 6);
}

const validateConfirmPassword=(cPassword)=>{
    const password=$('#password').val();
    return (cPassword.length >= 6 && password.length >=6 && password === cPassword);
}

const validateInput=(e,fun,msg)=>{
    const current = $(e.currentTarget);
    const err=$(e.currentTarget).siblings().first();
    if(fun(e.target.value)){
        current.removeClass('is-invalid')
        current.addClass('is-valid')
        err.hide();        
    }else{
        current.removeClass('is-valid')
        current.addClass('is-invalid')
        err.show();
        err.children().text(msg)
    }
}

$('#name').keyup(e=>{
    validateInput(e,validateName,'Enter atleast 3 charecters')
});

$('#mobile').keyup(e=>{
    validateInput(e,validateMobile,'Enter a valid number');
});

$('#email').keyup(e=>{
    validateInput(e,validateEmail,'Enter valid email address');
});

$('#password').keyup(e=>{
    validateInput(e,validatePassword,'Password must be atleast 6 charecter long');
});

$('#password-confirm').keyup(e=>{
    validateInput(e,validateConfirmPassword,'Password and confirm password must be same');
});

$('form').submit(e=>{
    const val=$(e.currentTarget).serializeArray();
    const name = val.filter(el=>el.name==='name')[0].value;
    const mobile = val.filter(el=>el.name==='mobile')[0].value;
    const email = val.filter(el=>el.name==='email')[0].value;
    const password = val.filter(el=>el.name==='password')[0].value;
    const cPassword = val.filter(el=>el.name==='password_confirmation')[0].value;
    const validate=validateName(name) && validateMobile(mobile) && validateEmail(email)
                    &&validatePassword(password) && validateConfirmPassword(cPassword);
    if(!validate){
        e.preventDefault();
        $('#error').show();
        $('#error').children().text('Please enter correct data');
    }    
})