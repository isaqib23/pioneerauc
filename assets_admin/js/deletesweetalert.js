
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings ) {
    return {
        "iStart": oSettings._iDisplayStart,
        "iEnd": oSettings.fnDisplayEnd(),
        "iLength": oSettings._iDisplayLength,
        "iTotal": oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage": Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
        "iTotalPages": Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
    };
};
                                                                                                                            
function deleteRecord(x){
    var id  = $(x).data('id');
    var obj = $(x).data('obj');
    var link = $(x).data('url');
    var token_name = $(x).data('token-name');
    var token_value = $(x).data('token-value');


    if(id!='' && obj!=''){

        swal({
                title: "Are you sure?",
                text: "You cannot recover it later.",
                type: "error",
                showCancelButton: true,
                cancelButtonClass: 'btn-default btn-md waves-effect',
                confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
                confirmButtonText: 'Confirm'
            },
            function(isConfirm) {
                if (isConfirm) {
                    $("#loading").show();

                    $.post(link, {id: id, obj: obj, [token_name] : token_value}, function(result){
                            if(result!='0'){

                                var data = JSON.parse(result);
                                if(data.type == 'success'){
                                    //hide gallery image
                                    location.reload();
                                    swal("Success!", '', "success");
                                    $("#row-"+id).fadeOut("slow");
                                   $("#row-"+id).remove();

                                    // var oTable = $('#datatable-responsive').dataTable();
                                    // var row = $(x).closest("tr").get(0);
                                    // oTable.fnDeleteRow( oTable.fnGetPosition(row)).draw( false );;

                                    // var page_number = oTable.fnPagingInfo().iPage;
                                    // oTable.fnDeleteRow(oTable.fnGetPosition(row), function(){oTable.fnPageChange(page_number);}, false);

                                }

                                if(data.type == 'error'){
                                    swal("Error!", data.msg, "error");
                                }

                            }else{
                                swal("Error!", "Something went wrong.", "error");
                            }
                            $('#loading').hide();
                        }

                    );

                } else {
                    swal("Cancelled", "Your action has been cancelled!", "error");
                }
            }

        );

    }else{
        swal("Error!", "Information Missing. Please reload the page and try again.", "error");
    }
}

function deleteRecord_Bulk(x){
    var ids = [];
    var obj = $(x).data('obj');
    var token_name = $(x).data('token-name');
    var token_value = $(x).data('token-value');
 
   $('table tbody input:checked').each(function(n) {

        ids[n]=$(this).attr('value');
    });
 
    var link = $(x).data('url'); 
    var ids = ids.toString(); 

    // alert(ids);
   //  var test = $('.multiple_rows').val();
   // console.log(ids);
   // console.log(test);
 
    if(ids!='' && obj!=''){

        swal({
                title: "Are you sure?",
                text: "You cannot recover it later.",
                type: "error",
                showCancelButton: true,
                cancelButtonClass: 'btn-default btn-md waves-effect',
                confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
                confirmButtonText: 'Confirm'
            },
            function(isConfirm) {
                    if (isConfirm) {
                    $("#loading").show();
                    $.post(link, {id: ids, obj:obj, [token_name] : token_value}, function(result){
                            if(result!='0'){
                                var data = JSON.parse(result);
                                // console.log(data);

                                if(data.type == 'success'){
                                    //hide gallery image
                                    location.reload();
                                    swal("Success!", data.msg, "success");
                                    $("#row-"+ids).fadeOut("slow");
                                   $("#row-"+ids).remove();

                                    // var oTable = $('#datatable-responsive').dataTable();
                                    // var row = $(x).closest("tr").get(0);
                                    // oTable.fnDeleteRow( oTable.fnGetPosition(row)).draw( false );;

                                    // var page_number = oTable.fnPagingInfo().iPage;
                                    // oTable.fnDeleteRow(oTable.fnGetPosition(row), function(){oTable.fnPageChange(page_number);}, false);

                                }

                                if(data.type == 'error'){
                                    swal("Error!", data.msg, "error");
                                }

                            }else{
                                swal("Error!", "Something went wrong.", "error");
                            }
                            $('#loading').hide();
                        }

                    );

                } else {
                    swal("Cancelled", "Your action has been cancelled!", "error");
                }
            }

        );

    }else{
        swal("Error!", "Information Missing. Please reload the page and try again.", "error");
    }
}

function deleteRows_Bulk(x){
    var ids = [];
    var obj = $(x).data('obj');

    var token_name = $(x).data('token-name');
    var token_value = $(x).data('token-value');

    var tableId = $(x).data('table');
    $.each($("#"+tableId+" tr.selected"),function(n){
        ids[n]=$(this).attr('id');
    }); 
    var link = $(x).data('url'); 
    var ids = ids.toString(); 
 
    if(ids!='' && obj!=''){

        swal({
                title: "Are you sure?",
                text: "You cannot recover it later.",
                type: "error",
                showCancelButton: true,
                cancelButtonClass: 'btn-default btn-md waves-effect',
                confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
                confirmButtonText: 'Confirm'
            },
            function(isConfirm) {
                    if (isConfirm) {
                    $("#loading").show();
                    $.post(link, {id: ids, obj:obj, [token_name]:token_value}, function(result){
                            if(result!='0'){
                                var data = JSON.parse(result);
                                if(data.type == 'success'){
                                    //hide gallery image
                                    location.reload();
                                    swal("Success!", data.msg, "success");
                                    $("#row-"+ids).fadeOut("slow");
                                    $("#row-"+ids).remove();
                                }
                                if(data.type == 'error'){
                                    swal("Error!", data.msg, "error");
                                }

                            }else{
                                swal("Error!", "Something went wrong.", "error");
                            }
                              $('#loading').hide();
                        }
                    );

                } else {
                    swal("Cancelled", "Your action has been cancelled!", "error");
                }
            }

        );

    }else{
        swal("Error!", "Information Missing. Please reload the page and try again.", "error");
    }
}

function Refund(e){
    var id  = $(e).data('id');
    var obj = $(e).data('obj');
    var column = $(e).data('field');
    var amount = $(e).data('amount');
    var link = $(e).data('url');
    var token_name = $(e).data('token-name');
    var token_value = $(e).data('token-value');


    if(id!='' && obj!=''){
        swal({
                title: amount +' AED', 
                text: "Are you sure to refund the amount.",
                type: "error",
                showCancelButton: true,
                cancelButtonClass: 'btn-default btn-md waves-effect',
                confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
                confirmButtonText: 'Confirm'
            },
            function(isConfirm) {
                if (isConfirm) {
                    $("#loading").show();
                    $.post(link, {id: id, obj: obj, column: column, [token_name]:token_value}, function(result){
                        if(result!='0'){

                            var data = JSON.parse(result);
                            if(data.type == 'success'){
                                // alert('success');
                                //hide gallery image
                                location.reload();
                                swal("Success!", data.msg, "success");
                                $("#row-"+id).fadeOut("slow");
                               $("#row-"+id).remove();

                                // var oTable = $('#datatable-responsive').dataTable();
                                // var row = $(x).closest("tr").get(0);
                                // oTable.fnDeleteRow( oTable.fnGetPosition(row)).draw( false );;

                                // var page_number = oTable.fnPagingInfo().iPage;
                                // oTable.fnDeleteRow(oTable.fnGetPosition(row), function(){oTable.fnPageChange(page_number);}, false);

                            }

                            if(data.type == 'error'){
                                swal("Error!", data.msg, "error");
                            }

                        }else{
                            swal("Error!", "Something went wrong.", "error");
                        }
                        $('#loading').hide();
                    }

                    );

                } else {
                    swal("Cancelled", "Your action has been cancelled!", "error");
                }
            }

        );
    }else{
        swal("Error!", "Information Missing. Please reload the page and try again.", "error");
    }
}