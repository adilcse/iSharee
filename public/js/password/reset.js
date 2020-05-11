//password reset page
//show only email field and send button

$("#status").hide();
$('#otp').hide();
$('#password').hide();
$('#cpassword').hide();
$('#verify').hide();

// after user click to send button other fields are visible
$('#sendbtn').click(()=>{
    email=$('#email').val();
    $("#sendbtn").prop('disabled',true);
    setTimeout(() => {
        $("#sendbtn").prop('disabled',false);
    }, 5000);

    /**
     * send an otp to the requested email id to reset password
     */
    $.post("/password/reset/send",
    {email:email,"_token":$('meta[name="csrf-token"]').attr('content')},
    function(data,status){
        data=JSON.parse(data);
        $("#status").show();
        if('success' === status){
            //if otp sent then display other fields
            if(data.success){
                otpSendSuccess(email);
            }else{
                //show error message
                otpSendFailed(data);
            }
        }   
    });
})

/**
 * handle otp send action
 * @param {*} email  to be verified
 */
const otpSendSuccess=(email)=>{
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

//validate email address before sending otp
const ValidateEmail=(mail)=> {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
        return (true)
    }
    return (false)
}

//show error message if otp sending fails
const otpSendFailed=(data)=>{
    $("#sendbtn").prop('disabled',false);
    setError(data.data);
}

/**
 * ser error if anthing went wrong
 */
const setError=(error)=>{
    $("#status").show();
    $("#status").addClass('alert-danger');
    $("#status").removeClass('alert-success');
    $("#status").text(error);
}

/**
 * display success message if otp sent succcess
 * @param {*} message success message
 */
const setSuccess=(message)=>{
    $("#status").show();
    $("#status").addClass('alert-success');
    $("#status").removeClass('alert-danger');
    $("#status").text(message);
}

/**
 * validate otp and passwords before sending to server
 * @param {*} e event
 */
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

/**
 * set new password is data are validated
 * @param {*} otp entered by user
 * @param {*} password confirmed
 * @param {*} email id of the user
 */
const setPassword=(otp,password,email)=>{
    const data={
        otp:otp,
        password:password,
        email:email,
        '_token':$('meta[name="csrf-token"]').attr('content'),
    };
    //ajax request to reset password
    $.post('/password/reset/verify',data,function(res,status){
        res=JSON.parse(res);
        if('success'===status)
            otpverify(res,email);
        else{
            setError('something went wrong');
        }
    })
}

/**
 * if otp verifies then display success message
 * @param {*} response server response
 * @param {*} email id of the user
 */
const otpverify=(response,email)=>{
    if(response.success){
        window.location.replace("/login?verify=success&email="+email);
        setSuccess(response.data)
    }else{
        setError(response.data)
    }
}

//set click listener to verifiy button
$('#verify').click(verifyotp);
