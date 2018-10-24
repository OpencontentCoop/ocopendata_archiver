
{ezcss_require( array(    
    'dataTables.bootstrap.css'
))}
{ezscript_require(array(
    'ezjsc::jquery',
    'plugins/chosen.jquery.js',
    'moment.min.js',
    'jquery.dataTables.js',
    'dataTables.bootstrap.js',
    'jquery.opendataDataTable.js',
    'jquery.opendataTools.js'    
))}

<script type="text/javascript" language="javascript" class="init">
{literal}
$(document).ready(function () {
    var baseQuery = "repository = 'opendata_archive'";
    var tools = $.opendataTools;
    var datatable = $('.content-data').opendataDataTable({
        "builder":{
            "query": baseQuery
        },
        "datatable":{
            "ajax": {
                url: "/customdatatable/opendata_archive/"
            },
            "order": [[ 1, 'desc' ]],
            "columns": [
                {"data": "name", "name": 'name', "title": "{/literal}{"Name"|i18n( 'extension/ocopendata_archiver' )}{literal}"},
                {"data": "published", "name": 'published', "title": "{/literal}{"Publish date"|i18n( 'extension/ocopendata_archiver' )}{literal}"},
                {"data": "archived", "name": 'archived', "title": "{/literal}{"Archive date"|i18n( 'extension/ocopendata_archiver' )}{literal}"},
                {"data": "class","name": 'class',"title": "{/literal}{"Class"|i18n( 'extension/ocopendata_archiver' )}{literal}"},
                {"data": "language","name": 'language',"title": "{/literal}{"Language"|i18n( 'extension/ocopendata_archiver' )}{literal}"}
            ],
            "columnDefs": [
                {
                  "render": function ( data, type, row ) {
                    return '<a href="/archiver/view/'+row.id+'">'+data+'</a>';
                  },
                  "targets": [0]
                },
                {          
                  "render": function ( data, type, row ) {              
                    var date = moment(data,moment.ISO_8601);            
                    return date.format('DD/MM/YYYY');
                    
                  },
                  "targets": [1,2]
                },
                {          
                  "render": function ( data, type, row ) {
                    return '<span data-class="'+data+'">'+data+'</span>';
                    
                  },
                  "targets": [3]
                }
            ],
            "oLanguage": {
                "sProcessing": "Caricamento",
                "sLengthMenu": "_MENU_ elementi per pagina",
                "sZeroRecords": "Oooops! Nessun risultato...",
                "sInfo": "Da _START_ a _END_ di _TOTAL_ elementi",
                "sInfoEmpty": "",
                "sSearch": "Cerca",
                "oPaginate": {
                    "sFirst":    "Primo",
                    "sPrevious": "Precedente",
                    "sNext":     "Successivo",
                    "sLast":     "Ultimo"
                }
            }
        }
    })    
    .data('opendataDataTable');
    
    datatable.loadDataTable();

    $('#QuerySubmit').on('click', function(e){
        var customQuery = $('#Query').val();
        if (customQuery.length > 0){
            customQuery = ' and ' + customQuery;
        }
        datatable.settings.builder.query = baseQuery + customQuery;
        datatable.loadDataTable();
        e.preventDefault();
    });
});

{/literal}
</script>

<form class="form" id="QueryForm">    
    <div class="input-group">
      <input type="text" name="Query" id="Query" class="form-control input-lg" placeholder="{"Available fields"|i18n( 'extension/ocopendata_archiver' )}: id, language, class, section, author, url_alias, archived, name, published" />
      <span class="input-group-btn">
        <button class="btn btn-success btn-lg" type="button" id="QuerySubmit">{"Search"|i18n( 'extension/ocopendata_archiver' )}</button>
      </span>
    </div>
</form>
<div class="content-data"></div>