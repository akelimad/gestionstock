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

    // delete product with ajax
    $(".remove").click(function(){
        var pid = $(this).attr("data-product-id");
        //alert(pid);
        confirm("Are you sure?", function(result) {
            url = "{{path('product_delete', { 'id': 0}) }}";
            url = $url.replace("0",pid);
            alert(url);
            $.ajax({ 
                url: url,
                type: 'delete', 
                success: function(result) {
                    console.log('Delete');
                },
                error: function(e){
                    console.log(e.responseText);
                }
            });
        });
    });


});