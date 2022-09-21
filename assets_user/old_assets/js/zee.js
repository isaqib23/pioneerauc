// Email Validation
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

// HTML Escape Validation
function isHTML(html) {
    var regex = /<(.|\n)*?>/g;
    return regex.test(html);
}

// JSON Validation
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

// Form Validation
function validateFields(selectedInputs){
    var validation = false;
    var errorMsg = "This value is required.";
    var htmlerrorMsg = "HTML Tags are not allowed.";
    var errorMsgEmail = "Email is not correct.";
    var e;

    if(! $.isArray(selectedInputs)){
        validation = false;
        return false;
    }else{

        $.each(selectedInputs, function(index, value){
            e = $('input[name='+value+']');
            
            if(! e.is('input')){
                e = $('select[name='+value+']');
                if(! e.is('select')){
                   e = $('textarea[name='+value+']');
                }
            }

            //validate email input
            if(value == 'email'){
                var email = e.val();
                if(! isEmail(email)){
                    e.focus();
                    $('.'+value+'-error').html(errorMsgEmail).show();
                    validation = false;
                    return false;
                }
            }

            //validate select inputs
            if(e.is('select')){
               e = $('select[name='+value+']');
               //console.log('select: ', value);
            }

            //these lines must after select validation
            value = value.replace(/\"/g, "");
            value = value.replace(/\'/g, "");
            value = value.replace(/\]/g, "");
            value = value.replace(/\[/g, "");

            //validate radio inputs
            if(e.is('input:radio')){
                //console.log('radio: ', e);
                if( ! $('input:radio[name='+value+']:checked').length) {
                    //console.log('radio unchecked: ', e);
                    e.focus();
                    $('.'+value+'-error').html(errorMsg).show();
                    validation = false;
                    return false;
                }
            }

            //validate check box inputs
            if(e.is('input:checkbox')){
                if( ! $('input:checkbox[name='+value+']:checked').length) {
                    e.focus();
                    $('.'+value+'-error').html(errorMsg).show();
                    validation = false;
                    return false;
                }
            }

            //Check HTML escape validation for all text inputs
            if (isHTML(e.val())) {
                e.focus();
               $('.'+value+'-error').html(htmlerrorMsg).show();
                validation = false;
                return false;
            }

            //validate all other input types
            if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
               console.log('input: ', e.val());
               e.focus();
               $('.'+value+'-error').html(errorMsg).show();
                validation = false;
                return false;
            }else{
                validation = true;
                $('.'+value+'-error').html('').hide();
            }
        });
        // console.log(validation);
        if(validation == true){
            return true;
        }else{
            return false;
        }
    }
}

// convert form data to JSON object
$.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

//load default image for every img tag in the project
function loadDefaultImage(path){
    $("img").on("error", function(){
        $(this).attr("src", path);
    });
}