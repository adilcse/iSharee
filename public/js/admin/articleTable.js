//only admin can peform those action in admin dashboard

/**
 * change article status
 * @param {*} id of the article
 * @param {*} element elemnt to change color
 */
const articleStatusChanged=(id,element)=>{
    //change element color either success or warning
    if(element.value == 0){
        changeBadge(element,true);
    }else{
        changeBadge(element,false);
    }
    //ajax call to update status
    $.ajax({
        url:`/admin/article/update/${id}?status=${element.value}`,
        error:(xhr,status,error)=>{
            //reverse back if error occured
            if(element.value == 1){
                changeBadge(element,true);
            }else{
                changeBadge(element,false);
            }
        },
        success:(res)=>{
            //log success message
            console.log(res);
        },      
    })
}

/**
 * change element color between green and yellow
 * @param {*} element elemet to change bolor
 * @param {boolean} status to change color
 */
const changeBadge=(element,status)=>{
    if(status){
        element.parentNode.classList.add("badge-warning");
        element.parentNode.classList.remove("badge-success");
    }else{
        element.parentNode.classList.remove("badge-warning");
        element.parentNode.classList.add("badge-success");
    }
}

/**
 * change current article view
 * @param {'all','pending','published'} element.value 
 */
const changeArticeView=(element)=>{
    window.location.href = "/admin/dashboard/article?view="+element.value;
}
/**
 * change current user view of admin dashboard user table
 * @param {'all','active','inactive'} element.value 
 */
const changeUserView=(element)=>{
    window.location.href = "/admin/dashboard/user?userview="+element.value;
}

/**
 * change a user's status for active to in active
 * @param {*} id user's id
 * @param {*} element to change color
 */
const userStatusChanged=(id,element)=>{
    //active user are indicated by green color 
    //where as inactive user are indicated by red color
    if(element.value==1){
        changeBadge(element,false);    
    }else{
        changeBadge(element,true);
    }
    /**
     * ajax call to update user status
     */
    $.ajax({
        url:`/admin/user/update/${id}?status=${element.value}`,
        error:(xhr,status,error)=>{
            //reverse changes if any error occurs
            if(element.value==0){
                changeBadge(element,false);    
            }else{
                changeBadge(element,true);
            }
        },
        success:(res)=>{
        //handle success
        },
    })
}

/**
 * update guest comment suatus
 * approve or reject guest comment
 * @param {*} element to remove the row after action
 * @param {*} id comment id
 * @param {boolean} status comment status
 */
const updateCommentStatus=(element,id,status)=>{
    const row=element.parentNode.parentNode;
    $.ajax({
        url:`/article/comment/update/${id}?status=${status}`,
        error:(xhr,status,error)=>{
            //handles error
        },
        success:(res)=>{
            if(!res.error){
                //if no error in response then delete whole row
                row.parentNode.removeChild(row);
            }
        },
        
        
    })
}

/**
 * delete an article and its related like, comments, catagories
 * @param {*} element 
 * @param {*} name 
 */
const deleteArticle=(element,name)=>{
    let r = confirm(`Are you sure ?\n Delete ${name} \n Note:deleting article will delete all related comments and likes`);
    if (r == true){
        window.location.href=$(element).attr('data-target');
    }
}