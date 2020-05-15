
//catagories multiselect enable
$(document).ready(function() {
    $('#multiple-selected').multiselect();
});

const validateTitle=(text)=>{
    return text.length > 3 && text.length < 50;
}

const validateBody=(text)=>{
    return text.length > 5 ;
}

const validateInput=(e,fun,msg)=>{
    const current = $(e.currentTarget)
    if(fun(current.val())){
        current.siblings().first().hide();
        current.removeClass('is-invalid');
        current.addClass('is-valid');
    }else{
        current.siblings().first().show();
        current.siblings().first().text(msg);
        current.removeClass('is-valid');
        current.addClass('is-invalid');
    }
}

$('#title').keyup(e=>{
    validateInput(e,validateTitle,'enter a valid title');
});

$('#body').keyup(e=>{
    validateInput(e,validateBody,'Enter a valid description');
})

$('form').submit(e=>{
    const val=$(e.currentTarget).serializeArray();
    const title = val.filter(el=>el.name==='title')[0].value;
    const body = val.filter(el=>el.name==='body')[0].value;
    if(!validateTitle(title) || !validateBody(body)){
        e.preventDefault();
        $('#error').show().text('Please ente rcorrect data');
    }
})