//catagories multiselect enables
$(document).ready(function() {
    $('#multiple-selected').multiselect();
    });

//change image when user selects an image for preview
$('#image').change((inp)=>{
    const input=inp.target;
    var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")){
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img').show()
            $('#img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }else{
        $('#img').attr('src', '/assets/no_preview.png');
    }
})