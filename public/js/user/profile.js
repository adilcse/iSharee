$('#verify-mobile-btn').click(function(e){
    e.preventDefault();
    let url=$(this).attr('data-target');
        let number = $('#mobile').val();
        if(!isNaN(number) && number.length === 10){
            window.location.href = url+'?number='+number;
        }else{
            const err=$(this).parent().children('span').show();
            console.log(err);
        }
            
})