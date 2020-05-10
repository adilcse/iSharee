
const articleStatusChanged=(id,element)=>{
    if(element.value == 0){
        changeBadge(element,true);
    }else{
        changeBadge(element,false);
    }
  
    $.ajax({
        url:`/admin/article/update/${id}?status=${element.value}`,
        error:(xhr,status,error)=>{
            console.log(xhr,status,error);
        },
        success:(res)=>{
        console.log(res);
        },      
    })
}

const changeBadge=(element,status)=>{
    if(status){
        element.parentNode.classList.add("badge-warning");
        element.parentNode.classList.remove("badge-success");
    }else{
        element.parentNode.classList.remove("badge-warning");
        element.parentNode.classList.add("badge-success");
    }
}

const changeArticeView=(element)=>{
    console.log(element.value)
    window.location.href = "/admin/dashboard/article?view="+element.value;
}

const changeUserView=(element)=>{
    window.location.href = "/admin/dashboard/user?userview="+element.value;
}

const userStatusChanged=(id,element)=>{
    console.log(element.value)
    if(element.value==1){
        changeBadge(element,false);    
    }else{
        changeBadge(element,true);
    }
    $.ajax({
        url:`/admin/user/update/${id}?status=${element.value}`,
        error:(xhr,status,error)=>{
            console.log(xhr,status,error);
        },
        success:(res)=>{
        console.log(res);
        },
        
        
    })
}

const updateCommentStatus=(element,id,status)=>{
    const row=element.parentNode.parentNode;
    $.ajax({
        url:`/article/comment/update/${id}?status=${status}`,
        error:(xhr,status,error)=>{
            console.log(xhr,status,error);
        },
        success:(res)=>{
            if(!res.error){
                row.parentNode.removeChild(row);
            }
            else{
                
            }
        },
        
        
    })
}

const deleteArticle=(element,name)=>{
    let r = confirm(`Are you sure ?\n Delete ${name} \n Note:deleting article will delete all related comments and likes`);
    if (r == true) {
        window.location.href=$(element).attr('data-target');
    }
}