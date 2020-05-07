
const articleStatusChanged=(id,element)=>{
    if(element.value == 0){
        changeBadge(element,false);
    }else{
        changeBadge(element,true);
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

const userStatusChanged=(id,element)=>{
    if(element.value==1){
        changeBadge(element,false);    
    }else{
        changeBadge(element,true);
    }
  
}