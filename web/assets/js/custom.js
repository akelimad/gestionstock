$(function(){
	'use strict'
    //demo.initStatsDashboard();
    demo.initVectorMap();
    demo.initCirclePercentage();

    $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records",
        }
    });

    var table = $('#datatables').DataTable();

    //Edit record
    // table.on('click', '.edit', function() {
    //     $tr = $(this).closest('tr');

    //     var data = table.row($tr).data();
    //     alert('You press on Row: ' + data[0] + ' ' + data[1] + ' ' + data[2] + '\'s row.');
    // });

    // Delete a record
    // table.on('click', '.remove', function(e) {
    //     //e.preventDefault();
    //     return confirm('Etes-vous sur de vouloir supprimer ?');
    //     // $tr = $(this).closest('tr');
    //     // table.row($tr).remove().draw();
    // });

    //Like record
    table.on('click', '.like', function() {
        alert('You clicked on Like button');
    });

    $('.card .material-datatables label').addClass('form-group');

    // delete product 
    $('.remove').click(function (e) {

        var url = Routing.generate('product_delete', {'id': $(this).attr('data-product-id')},{ expose: true});
        e.preventDefault();
        //var url = "{{path('product_delete', { 'id': 0}) }}";
        alert(url);
        $.ajax({
            type: 'delete',
            url:  url,
            data: $(this).serialize(),
            success: function (response) {
                location.reload();
                console.log("deleted");
            },
            error: function (response) {
                console.log("error");
            }
        });
    });

    //add image of product . dropzone js

    //je récupère l'action où sera traité l'upload en PHP
    var _actionToDropZone = $("#form_snippet_image").attr('action');
    //je définis ma zone de drop grâce à l'ID de ma div citée plus haut.
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#form_snippet_image", {  
        url: _actionToDropZone 
    });
    myDropzone.on("complete", function(file) {
        myDropzone.removeFile(file);
    });



});