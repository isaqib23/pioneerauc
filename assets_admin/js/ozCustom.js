/**
 * Custom function for 
 * Datatable, JQuery Select2 libraries
 * 
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 * 
  Usage: 

  let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4,5,6,7,8,9,10,11']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    'div' : '#tableId',
    'url' : 'URL FOR POSTED DATA',
    'orderColumn' : 10,
    'iDisplayLength' : 10,
    'dom' : 'Bfrtip',
    'columnCustomeTypeTarget' : 10,
    'defaultSearching': false,
    'responsive': true,
    'lengthMenu': '', // [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]]
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span>Loading.. <img  src="load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': ['category_id','seller_id','seller_code','sale_person_id','item_status','keyword','registration_no','days_filter','datefrom','dateto'],
    'dataColumnArray': [{'data':'id'},{'data':'image'},{'data':'name'},{'data':'category_id'},{'data':'registration_no'},{'data':'make'},{'data':'model'},{'data':'price'},{'data':'keyword'},{'data':'seller_id'},{'data':'created_on'},{'data':'item_status'},{'data':'action'}]
  });
  
 */

 function customeDatatable(options){

  var datatableObj = $(options.div).DataTable({
  'createdRow': function( row, data, dataIndex ) {
    // $(row).attr('id', data.id);
  },
  dom: options.dom, // Bfrtip
    buttons: [
      {
        extend: 'collection',
        text: 'Export',
        className: options.buttonClassName,
        buttons: options.collectionButtons
       },{
        extend: 'selectAll',
        className: options.buttonClassName,
        text: 'Select All',
        exportOptions: exportOptions
      },{
        extend: 'selectNone',
        className: options.buttonClassName,
        text: 'Deselect',
        exportOptions:exportOptions
      }, 
    ],
    'select': {
      style: options.selectStyle
    },
    rowId: options.rowId,
    "bStateSave": options.stateSave,
    'processing': options.processing, // true , false 
    'serverSide': options.serverSide, // true , false
    'serverMethod': 'post',
    'searching': options.defaultSearching, // Remove default Search Control
    'ajax': {
        'url':options.url,
        'data': function(data){
          // Link inputs Fields with post data array
          jQuery.each(options.customFieldsArray, function(index, item) {
            data[item] = $('#'+item).val();
          })  

          //add CSRF token to request
          var datakeys = Object.keys(options);
          data[datakeys[0]] = options[datakeys[0]];
        }
    },
    'columns':  options.dataColumnArray, // DB columns array server side
    'responsive': options.responsive, // responsive option
    'order': [[ options.orderColumn, options.order ]],
    'columnDefs' : [{"targets":options.columnCustomeTypeTarget, "type":options.columnCustomType}],
    'language': {
      // "infoFiltered":"Loading..",
      "infoFiltered":"",
      "processing": options.processingGif
    },
    'lengthMenu': options.lengthMenu,
    'iDisplayLength': options.iDisplayLength,
  });
  return datatableObj;
}



 function customeDatatableOzair(options){

  var datatableObj = $(options.div).DataTable({
  'createdRow': function( row, data, dataIndex ) {
    // $(row).attr('id', data.id);
  },
  dom: options.dom, // Bfrtip
    buttons: [],
    'select': {
      style: options.selectStyle
    },
    rowId: options.rowId,
    "stateSave": options.stateSave,
    'processing': options.processing, // true , false 
    'serverSide': options.serverSide, // true , false
    'serverMethod': 'post',
    'searching': options.defaultSearching, // Remove default Search Control
    'ajax': {
        'url':options.url,
        'data': function(data){
          // Link inputs Fields with post data array
          jQuery.each(options.customFieldsArray, function(index, item) {
            data[item] = $('#'+item).val();
          })
          //add CSRF token to request
          var datakeys = Object.keys(options);
          data[datakeys[0]] = options[datakeys[0]];
        }
    },
    'columns':  options.dataColumnArray, // DB columns array server side
    'responsive': options.responsive, // responsive option
    'order': [[ options.orderColumn, options.order ]],
    'columnDefs' : [{"targets":options.columnCustomeTypeTarget, "type":options.columnCustomType}],
    'language': {
      "infoFiltered":"Loading..",
      "processing": options.processingGif
    },
    'lengthMenu': options.lengthMenu,
    'pageLength': options.iDisplayLength,
  });
  return datatableObj;
}

function remoteSelect2(options){
  var token = [];
  var datakeys = Object.keys(options);
  var token_name = datakeys[0];
  $(options.selectorId).select2({
    placeholder: options.placeholder, 
    width: options.width,
    sorter: function(data) {
        return data.sort();
    },
    ajax: {
    url: options.url,
    dataType: 'json',
    type: 'post',
    delay: options.delay,
    cache: options.cache,
    minimumInputLength: options.minimumInputLength,
    data: function (params) {
      return {
        search: params.term, // search term
        table: options.table, // search table
        values: options.values, // search columns
        csrf_test_name: options.csrf, // search columns
        [token_name] : options[datakeys[0]], //add CSRF token to request
        page: params.page || 1,
      };
    },
    processResults: function (data, params) {
    //  NO NEED TO PARSE DATA `processResults` automatically parse it
    //var c = JSON.parse(data);
      var page = params.page || 1;
        return {
          results: $.map(data, function (item) { return {id: item.id, text: item.value}}),
          pagination: {
            // THE `x.limit` SHOULD BE SAME AS `$resultCount FROM PHP, it is the number of records to fetch from table` 
            more: (page * options.limit) <= data[0].total_count
          }
        };
     },
   }
  });
}
    