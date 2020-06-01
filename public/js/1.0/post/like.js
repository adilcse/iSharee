//like press action
const likepressed=(articleId,el)=>{
    const e=$(el).parentsUntil('.row').last();
    const likeSpan=e.children('div').first().children().children();
    let likes=parseInt(likeSpan.text());
    const i=e.children('div').last();
    const err=e.children('span');
    //update like count in server and update like icon and like counter
    $.ajax({url:'/article/like/'+articleId,
    success:(data)=>{
        if(data.error){
            err.text(data.message);
            err.show();
        }else{
            if(data.liked){
                likeSpan.text(likes+1);
                likeIcon(i,true);
            }else{
                likeSpan.text(likes-1);
                likeIcon(i,false);
            }
        }
    }}) 
}

//change like icon
const likeIcon=(icon,liked)=>{
    if(liked){
        icon.addClass('fas');
        icon.addClass('red-text');
        icon.removeClass('far');
    }else{
        icon.addClass('far');
        icon.removeClass('fas');
        icon.removeClass('red-text');
    }
}

