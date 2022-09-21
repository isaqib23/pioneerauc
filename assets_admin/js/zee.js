//custom DataTable builder with server side processing
function dtBuilder(options){
    //console.log(options.ajaxData);
    /*var exportOptions = {
        'rows': {selected: true},
        'columns': ['0,1,2,3,4,5,6,7']
    };*/

    var exportOptions = {
        columns: ':visible',
        orthogonal: 'excel',
        modifier: {
            order: 'current',
            page: 'all',
            selected: false,
        }
    };

    var dtButtons = [
        { 
            extend : 'excel',
            exportOptions: exportOptions
        },
        { 
            extend : 'pdf',
            exportOptions: exportOptions
        },
        /*{ 
            extend : 'csv',
            exportOptions: exportOptions
        },
        { 
            extend : 'print',
            exportOptions: exportOptions
        },*/
    ];

    //console.log(options);   connects, shortlistme, mozuzee

    //var dbColumnsMine = [];

    if ($.fn.DataTable.isDataTable(options.tableId)) {
        $(options.tableId).DataTable().clear().destroy();
    }
    
    var datatableObj = $(options.tableId).DataTable({
        /*'createdRow': function( row, data, dataIndex ) {
            $(row).attr('id', data.id);
        },*/
        dom: '<"pull-left top"B><"pull-right"fl><"clear">r<t><"bottom"ip><"clear">', // Bfrtip
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                className: 'btn-sm',
                buttons: dtButtons
            },
            /*{
                extend: 'selectAll',
                className: 'btn-sm',
                text: 'Select All',
                exportOptions: exportOptions
            },
            {
                extend: 'selectNone',
                className: 'btn-sm',
                text: 'Deselect',
                exportOptions:exportOptions
            }, */
        ],
        /*'select': {
            style: 'multi'
        },*/
        rowId: 'id',
        'searching': true,
        "bStateSave": true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':options.url,
            'data': function(data){
                if(options.ajaxData){
                    jQuery.each(options.ajaxData, function(index, item) {
                        data[item.name] = item.value;
                    })
                }
            }/*,
            "dataSrc": function ( json ) {
                var columnNames = Object.keys(json.aaData[0]);
                
                for (var i in columnNames) {
                    dbColumnsMine.push({'data': columnNames[i], 'sTitle': columnNames[i]});
                }

                //console.log(dbColumns);
                //localStorage.setItem("dbColumns", dbColumns);
                
                return json.aaData;
            }*/
        },
        'columns': options.dbFields, // DB columns array server side
        //'columnDefs' : [{"targets":1, "type":"Number"}],
        'responsive': false,
        'order': [[ options.orderColumn, options.order ]],
        'language': {
            // "infoFiltered":"Loading..",
            "infoFiltered":"",
            "processing": '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="'+window.location.origin+'/assets_admin/images/load.gif" /></span>'
        },
        'lengthMenu': [[ 10, 50, 100, -1], [10, 50, 100, "All"]],
        'iDisplayLength': 10,
    });
    //console.log(dbColumns);
    return datatableObj;
}

/*function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}*/


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

            //validate email input
            // if(value == 'email'){  
            if(value.includes('email')){
                var email = e.val();
                if(! isEmail(email)){
                    e.focus();
                    $('.'+value+'-error').html(errorMsgEmail).show();
                    validation = false;
                    return false;
                }
            }

            //validate select inputs
            if(! e.is('input')){
               e = $('select[name='+value+']');
               //console.log('select: ', value);
            }

            //these lines must after select validation
            value = value.replace(/\"/g, "");
            value = value.replace(/\]/g, "");
            value = value.replace(/\[/g, "");

            //validate rodio inputs
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

            //validate checkbox inputs
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

            // if (! e.checkValidity()) {
            //     e.focus();
            //    $('.'+value+'-error').html('js not allowed').show();
            //     validation = false;
            //     return false;
            // }

            //validate all other input types
            if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
               e.focus();
               $('.'+value+'-error').html(errorMsg).show();
                validation = false;
                return false;
            }else{
                validation = true;
                $('.'+value+'-error').html('').hide();
            }
        });

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

//Remove an element from an javascript array
function arrayRemove(arr, value) { 
    return arr.filter(function(ele){ 
        return ele != value; 
    });
}