$(document).ready(function() {
    $('form').on('submit', function(e){
        e.preventDefault();
        let comment = $('form  textarea').val().trim();
        if(comment.length < 3 ){
            setError('invalid comment')
        } else {
            $('form  :submit').hide();
            $("#commentLoading").show();
            const elements = $('form :input').toArray();
            const data ={}
                elements.forEach(el=>{
                data[el.name]=el.value
            });
            sendComment(data)
        }
    });
});

const setError=(err,type="alert-danger")=>{
    const msg=$("#commentMsg");
    $("#zeroComments").hide();
    msg.show();
    msg.removeClass(`alert-*`);
    msg.addClass(`alert ${type} h4`);
    msg.text(err);
}

const sendComment=(data)=>{
    console.log(data)
    $.post("/article/comment",data,function(res,status){
        try{
            res=JSON.parse(res);
        }catch(e){}
        if('success'===status){
            $('form  textarea').val(' ');
            if(res.status){
                setError(res.message,"alert-success");
                location.reload();
            }else if(res.pending){
                setError(res.message,"alert-warning");
            }else{
                setError(res.message);
            }
        }
        else{
            setError('something went wrong');
        }
        $("#commentLoading").hide();
        $('form  :submit').show();
    })
}
