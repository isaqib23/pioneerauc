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
function validateFields(selectedInputs, language='english'){
    var validation = false;
    var errors = getErrorMessages(language);
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
            //if(value == 'email'){
            // if(value.indexOf('email') !== -1){
            if(value.includes('email')){
                var email = e.val();
                if(! isEmail(email)){
                    e.focus();
                    // $('.'+value+'-error').html(language).show();
                    $('.'+value+'-error').html(errors.errorMsgEmail).show();
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
                    $('.'+value+'-error').html(errors.errorMsg).show();
                    validation = false;
                    return false;
                }
            }

            //validate check box inputs
            if(e.is('input:checkbox')){
                if( ! $('input:checkbox[name='+value+']:checked').length) {
                    e.focus();
                    $('.'+value+'-error').html(errors.errorMsg).show();
                    validation = false;
                    return false;
                }
            }

            //Check HTML escape validation for all text inputs
            if (isHTML(e.val())) {
                e.focus();
               $('.'+value+'-error').html(errors.htmlerrorMsg).show();
                validation = false;
                return false;
            }

            //validate all other input types
            if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
               console.log('input: ', e.val());
               e.focus();
               $('.'+value+'-error').html(errors.errorMsg).show();
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

// sort select options
function selectSort(selectELement) {
    var options = $(selectELement+' option');
    var arr = options.map(function(_, o) {
        return {
            t: $(o).text(),
            v: o.value
        };
    }).get();
    arr.sort(function(o1, o2) {
        return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0;
    });
    options.each(function(i, o) {
        o.value = arr[i].v;
        $(o).text(arr[i].t);
    });
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

// JSON Validation
function getErrorMessages(language) {
    if (language == 'english') {
        var errorMsg = "This value is required.";
        var htmlerrorMsg = "HTML Tags are not allowed.";
        var errorMsgEmail = "Email is not correct.";
    }
    if (language == 'arabic') {
        var errorMsg = "هذه القيمة مطلوبة.";
        var htmlerrorMsg = "علامات HTML غير مسموح بها.";
        var errorMsgEmail = "البريد الإلكتروني غير صحيح.";
    }
    
    //reurn error messages object
    var messages = {
        errorMsg: errorMsg, 
        htmlerrorMsg: htmlerrorMsg, 
        errorMsgEmail: errorMsgEmail
    };
    return messages;
}

//load default image for every img tag in the project
/*function loadDefaultImage(path){
    $("img").on("error", function(){
        $(this).attr("src", path);
    });
}*/

//// Number formater //////
/*function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}*/

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};
function aed_numberWithCommas(x, $lang) {
  var amount=  x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    if($lang=='arabic'){
        return amount + 'درهم إماراتيs'
    }
    return 'AED'+amount;
}