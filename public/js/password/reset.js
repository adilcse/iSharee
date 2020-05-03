$("#status").hide();
$('#otp').hide();
$('#password').hide();
$('#cpassword').hide();
$('#verify').hide();
$('#sendbtn').click(()=>{
    email=$('#email').val();
    $("#sendbtn").prop('disabled',true);
    setTimeout(() => {
        $("#sendbtn").prop('disabled',false);
    }, 5000);
    $.post("/password/reset/send",
    {email:email,"_token":$('meta[name="csrf-token"]').attr('content')},
    function(data,status){
        data=JSON.parse(data);
        $("#status").show();
        if('success' === status){
            if(data.success){
                otpSendSuccess(data,email);
            }else{
                otpSendFailed(data);
            }
        }
        
    });
})

const otpSendSuccess=(data,email)=>{
    $("#sendbtn").text('Resend');
    $("#mail").prop('value',email);
    $("#sendbtn").prop('disabled',true);
    setTimeout(() => {
        $("#sendbtn").prop('disabled',false);
    }, 5000);
    $('#otp').show();
    $('#password').show();
    $('#cpassword').show();
    $('#verify').show();

    setSuccess('otp send success');


}

const ValidateEmail=(mail)=> {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
        return (true)
    }
    return (false)
}

const otpSendFailed=(data)=>{
    $("#sendbtn").prop('disabled',false);
    setError(data.data);
}

const setError=(error)=>{
    $("#status").show();
    $("#status").addClass('alert-danger');
    $("#status").removeClass('alert-success');
    $("#status").text(error);
}

const setSuccess=(message)=>{
    $("#status").show();
    $("#status").addClass('alert-success');
    $("#status").removeClass('alert-danger');
    $("#status").text(message);
}
const verifyotp=(e)=>{
    e.preventDefault();
    const otp=$('#otp').val();
    const email=$('#mail').val();
    const password=$('#password').val();
    const cpassword=$('#cpassword').val();
    if(!ValidateEmail(email)){
        setError('invalid email address');
        return;
    }
    if ((password.length>5 && password === cpassword)&&(otp.length===4 && !isNaN(otp)))
    {
        setPassword(otp,password,email);
    }
    else if(otp.length!=4 || isNaN(otp)){
        setError('otp must be 4 digit number')
    }
    else{
        setError('password now matched')
    }
}

const setPassword=(otp,password,email)=>{
    const data={
        otp:otp,
        password:password,
        email:email,
        '_token':$('meta[name="csrf-token"]').attr('content'),
    };

    $.post('/password/reset/verify',data,function(res,status){
        res=JSON.parse(res);
        if('success'===status)
            otpverify(res,email);
        else{
            setError('something went wrong');
        }
    })
}

const otpverify=(response,email)=>{
    if(response.success){
        window.location.replace("/login?verify=success&email="+email);
        setSuccess(response.data)
    }else{
        setError(response.data)
    }
}

$('#verify').click(verifyotp);
