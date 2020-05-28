//validate mobile number before sending otp
$('#verify-mobile-btn').click(function(e){
    e.preventDefault();
    let url=$(this).attr('data-target');
        let number = $('#mobile').val();
        if(!isNaN(number) && number.length === 10){
            window.location.href = url+'?number='+number+'&resend=true';
        }else{
            const err=$(this).parent().children('span').show();
        }  
})

const changePasswordClick=(element)=>{
    const ps= $('.password-section');
    if(ps.css('display')== 'none'){
        ps.show()
    }else{
        ps.hide()
    }
}