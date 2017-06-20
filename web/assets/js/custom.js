$(document).ready(function() {
	'use strict';

    $('[data-toggle="tooltip"]').tooltip(); 

    //init product images switcher
    $(".sidebar-wrapper .nav li").click(function(){
        $(this).addClass('active').siblings().removeClass('active');
    });
    //demo.initStatsDashboard();
    demo.initVectorMap();
    demo.initCirclePercentage();

    $('div.dataTables_wrapper div.dataTables_filter input').click(function(){
        alert("ok");
    });

    $('#datatables').DataTable({
        "order": [],
        "sScrollX": '100%',
        "pagingType": "full_numbers",
        "lengthMenu": [
            [25, 50, 100, -1],
            [25, 50, 100, "Tous"]
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Recherche . e.g(prix= 100)",
            "paginate": {
            "first":      "Premier",
            "last":       "Dernier",
            "next":       "Suivant",
            "previous":   "Précedent"
            },
            "lengthMenu":     "Affichage _MENU_ entrées",
            "zeroRecords":    "Aucun resultat trouvée !",
            "emptyTable":     "Aucune donnée dans la table",
            "info":           "Affichage _START_ à _END_ du _TOTAL_ entrées",
            "infoEmpty":      "Affichage 0 à 0 du 0 entrées",
        },
        "sDom": 'Rfrtlip',
        scrollCollapse: true,
    }); 

    function InitOverviewDataTable(){
        $("#datatables").DataTable({ 
            "order": [],
            "bDestroy": true,
            "lengthMenu": [
                [25, 50, 100, -1],
                [25, 50, 100, "Tous"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Recherche . e.g(prix= 100)",
                "paginate": {
                "first":      "Premier",
                "last":       "Dernier",
                "next":       "Suivant",
                "previous":   "Précedent"
                },
                "lengthMenu":     "Affichage _MENU_ entrées",
                "zeroRecords":    "Aucun resultat trouvée !",
                "emptyTable":     "Aucune donnée dans la table",
                "info":           "Affichage _START_ à _END_ du _TOTAL_ entrées",
                "infoEmpty":      "Affichage 0 à 0 du 0 entrées",
            },
            "sDom": 'Rfrtlip',
            drawCallback: function () { // this gets rid of duplicate headers
                $('.table tbody tr:first-child').css({ display: 'none' }); 
            },
            scrollCollapse: true,
        });
    }

    var table = $('#datatables').DataTable();

    // Edit record
    // table.on('click', '.edit', function() {
    //     $tr = $(this).closest('tr');

    //     var data = table.row($tr).data();
    //     alert('You press on Row: ' + data[0] + ' ' + data[1] + ' ' + data[2] + '\'s row.');
    // });

    // Delete a record
    // table.on('click', '.remove', function(e) {
    //     $tr = $(this).closest('tr');
    //     table.row($tr).remove().draw();
    //     e.preventDefault();
    // });

    //Like record
    // table.on('click', '.like', function() {
    //     alert('You clicked on Like button');
    // });

    $('.card .material-datatables label').addClass('form-group');

    // ************************************* //
    //       desactivate product with ajax
    // ************************************* //
    $("#datatables").on('click', '.desactivate-product', function () {
        var url = Routing.generate('product_desactivate', {'id': $(this).data('id')});
        var $tr = $(this).closest('tr');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                    "_method": "PUT",
                    "form[_token]": $("#csrf_token").data("token")
                    }
                }).done(function(response){
                    swal('Deleted!', 'Product has been deleted.', 'success');
                    $tr.find('td').fadeOut(1000,function(){ $tr.remove(); });
                    location.reload();
                }).fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error') .catch(swal.noop);
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    // ************************************* //
    //       revert product with ajax
    // ************************************* //
    $("#datatables").on('click', '.revert-product', function () {
        var url = Routing.generate('product_revert', {'id': $(this).data('id')});
        var $tr = $(this).closest('tr');
        swal({
            title: 'Are you sure?',
            text: "Do you want to revert it ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, revert it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                    "_method": "PUT",
                    "form[_token]": $("#csrf_token").data("token")
                    }
                }).done(function(response){
                    swal('Reverted!', 'Product has been reverted.', 'success');
                    $tr.find('td').fadeOut(1000,function(){ $tr.remove(); });
                    location.reload();
                }).fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error') .catch(swal.noop);
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    // ************************************* //
    //       delete product with ajax
    // ************************************* //
    $("#datatables").on('click', '.remove-product', function () {
        var url = Routing.generate('product_delete', {'id': $(this).data('id')});
        var $tr = $(this).closest('tr');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                    "_method": "DELETE",
                    "form[_token]": $("#csrf_token").data("token")
                    }
                }).done(function(response){
                    swal('Deleted!', 'Product has been deleted.', 'success');
                    $tr.find('td').fadeOut(1000,function(){ $tr.remove(); });
                    location.reload();
                }).fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error') .catch(swal.noop);
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    // ************************************* //
    //       delete cat with ajax
    // ************************************* //
    $("#datatables").on('click', '.remove-category',function () {
        var url = Routing.generate('category_delete', {'id': $(this).data('id')});
        var $tr = $(this).closest('tr');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                    "_method": "DELETE",
                    "form[_token]": $("#csrf_token").data("token")
                    }
                }).done(function(response){
                    swal('Deleted!', 'Category has been deleted.', 'success');
                    $tr.find('td').fadeOut(1000,function(){ $tr.remove(); });
                    location.reload();
                }).fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error');
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    // ************************************* //
    //       delete package with ajax
    // ************************************* //
    $("#datatables").on('click', '.remove-package',function () {
        var url = Routing.generate('package_delete', {'id': $(this).data('id')});
        var $tr = $(this).closest('tr');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                    "_method": "PUT",
                    "form[_token]": $("#csrf_token").data("token")
                    }
                }).done(function(response){
                    swal('Deleted!', 'Package has been deleted.', 'success');
                    $tr.find('td').fadeOut(1000,function(){ $tr.remove(); });
                    location.reload(); 
                }).fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error');
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    // ************************************* //
    //       delete provider with ajax
    // ************************************* //
    $("#datatables").on('click', '.remove-provider', function () {
        var url = Routing.generate('provider_delete', {'id': $(this).data('id')});
        var $tr = $(this).closest('tr');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                    "_method": "PUT",
                    "form[_token]": $("#csrf_token").data("token")
                    }
                }).done(function(response){
                    swal('Deleted!', 'Provider has been deleted.', 'success');
                    $tr.find('td').fadeOut(1000,function(){ $tr.remove(); });
                    location.reload();
                }).fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error');
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    // ************************************* //
    //       delete user with ajax
    // ************************************* //
    $("#datatables").on('click', '.remove-user', function () {
        var url = Routing.generate('user_delete', {'id': $(this).data('id')});
        var $tr = $(this).closest('tr');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                    "_method": "DELETE",
                    "form[_token]": $("#csrf_token").data("token")
                    }
                }).done(function(response){
                    swal('Deleted!', 'User has been deleted.', 'success');
                    $tr.find('td').fadeOut(1000,function(){ $tr.remove(); });
                    location.reload();
                }).fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error');
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    // ************************************* //
    //       manage images of product
    // ************************************* //
    $('.remove-img-link').click(function(){
        var id= $(this).data('id');
        var url = Routing.generate('imageproduct_delete', {'id': id });
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                    "_method": "DELETE",
                    "form[_token]": $("#csrf_token").data("token")
                    },
                    success: function() {
                        $(this).parents("#media-"+ id).remove();
                    }
                }).done(function(response){
                    $("#media-"+ id).remove();
                    swal('Deleted!', 'image has been deleted.', 'success');
                }).fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error');
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    // ************************************* //
    //       filter product by cat
    // ************************************* //

    $("select#categories").change(function(){
        var category_id = $(this).val();
        var url = Routing.generate('filter-by-cat', {'category_id': category_id});
        var product_results = $('#product-results');
        $('#loading-cat').show();
        $.ajax({
            //ajax: true,
            type: 'GET',
            url:  url,
            dataType: 'html',
            success: function(response){
                if(response){
                    $("#datatables").dataTable().fnDestroy();
                    product_results.html(response);
                    $('#loading-cat').hide();
                    InitOverviewDataTable();
                }else{
                    
                }
            },
            error: function(){
                console.log("error ...");
                $('#loading-cat').hide();
            }
        });

    });

    // ************************************* //
    //       filter product by sub cat
    // ************************************* //

    $("select#s_categories").change(function(){
        var sub_category_id = $(this).val();
        var url = Routing.generate('filter-by-sub-cat', {'sub_category_id': sub_category_id});
        var product_results = $('#product-results');
        $('#loading-sub-cat').show();
        $.ajax({
            //ajax: true,
            type: 'GET',
            url:  url,
            dataType: 'html',
            success: function(response){
                if(response){
                    $("#datatables").dataTable().fnDestroy();
                    product_results.html(response);
                    $('#loading-sub-cat').hide();
                    InitOverviewDataTable();
                }else{
                    
                }
            },
            error: function(){
                console.log("error ...");
            }
        });
    });

    $(".material-datatables").on('change', '#filter-by-prov',function(){
        var provider_id = $(this).val();
        var url = Routing.generate('filter-by-prov', {'provider_id': provider_id});
        var product_results = $('#product-results');
        $('#loading-prov').show();
        $.ajax({
            //ajax: true,
            type: 'GET',
            url:  url,
            dataType: 'html',
            success: function(response){
                if(response){
                    $("#datatables").dataTable().fnDestroy();
                    product_results.html(response);
                    $('#loading-prov').hide();
                    InitOverviewDataTable();
                }else{
                    
                }
            },
            error: function(){
                console.log("error ...");
            }
        });
    });

    //to set checkbox checked if edit_form.active == '1'
    var checked=$("input[type=checkbox]").attr("checked");
    if(checked=="checked"){
        $("label.checkbox").addClass("checked");
    }

    //to customize select of category & subcategory in product form
    $("#productbundle_product_categories optgroup").attr("label", "-----------------------------------");

    //to make background for color select
    $("select#productbundle_product_color option").find()

    //make backgroud for product color div 


    //filter sub cat by parent id
    $('select#categories').change(function(){
        var id =  $( "select#categories option:selected" ).val();          
        var url = Routing.generate('ajax_subcategories',{'id':id});
        $("#loading-child-cat").show();
        var sub_cat_selected=$("#sub_cat_selected").data("sub-cat-selected");
        $.ajax({
            //ajax: true,
            type: 'GET',
            url:  url,
            dataType: 'json',
            success: function(data){
                var sub = $('select#s_categories');
                sub.empty();
                $.each(data , function(key, value) { 
                    if(sub_cat_selected == value.id){
                        sub.append( $("<option selected></option>").attr("value",value.id).text(value.name) ); 
                    }else{
                        sub.append( $("<option></option>").attr("value",value.id).text(value.name) ); 
                    }
                });
                $("#loading-child-cat").hide();
            },
            error: function(){
                console.log("error ...");
            }
        });
    });


    //$("select#categories").change();


    if ($.fn.DataTable.isDataTable( '#datatables' ) ) {
        $('#datatables').DataTable().rows().invalidate().draw('full-reset');
    }else{
        $('#datatables').DataTable();
    }

    //to change size inch and sizecm automatically
    var sizeInch=$("#productbundle_product_sizeInch");
    var sizeCm=$("#productbundle_product_sizeCm");
    sizeInch.blur(function(){
        var inch = $(this).val();
        if(isNaN(inch)){
            $("span#sizeInchError").show().html("valeur invalide");
            $("#sizeInchdiv").addClass("has-error");
            sizeInch.focus();
            sizeCm.val(0);
        }else{
            var cm   = Number( inch * 2.54 );
            sizeCm.val(cm);
            $("span#sizeInchError").hide();
            $("#sizeInchdiv").removeClass("has-error");
        }
    });

    sizeCm.on('blur', function(){
        var cm = $(this).val();
        if(isNaN(cm)){
            $("span#sizeCmError").show().html("valeur invalide");
            $("#sizeCmdiv").addClass("has-error");
            sizeCm.focus();
            sizeInch.val(0);
        }else{
            var inch   = Number( cm * 0.39 );
            sizeInch.val(inch);
            $("span#sizeCmError").hide();
            $("#sizeCmdiv").removeClass("has-error");
        }
    })

    //get selected start of prodvider by criteres
    $(".stars.quality").each(function(index,elem){
        var $this=$(".stars.quality").eq(index);
        var value=$this.data('value');
        $this.find('#star-'+value+'-q').attr('checked','checked');
    });
    $(".stars.qualityPrice").each(function(index,elem){
        var $this=$(".stars.qualityPrice").eq(index);
        var value=$this.data('value');
        $this.find('#star-'+value+'-rqp').attr('checked','checked');
    });
    $(".stars.delivery").each(function(index,elem){
        var $this=$(".stars.delivery").eq(index);
        var value=$this.data('value');
        $this.find('#star-'+value+'-dl').attr('checked','checked');
    });
    $(".stars.communication").each(function(index,elem){
        var $this=$(".stars.communication").eq(index);
        var value=$this.data('value');
        $this.find('#star-'+value+'-c').attr('checked','checked');
    });
    $(".stars.partnership").each(function(index,elem){
        var $this=$(".stars.partnership").eq(index);
        var value=$this.data('value');
        $this.find('#star-'+value+'-ep').attr('checked','checked');
    });

    //slide toggle div that show field to show table product
    $(".entete").click(function () {
        var contenu = $(this).next();
        //Ouvre le contenu s'il est masqué, sinon le masque avec un effet de glissement
        contenu.slideToggle(500, function () {
            //On change le texte de l'entête suivant si le contenu est affiché ou non
            $(".entete span").html(function () {
                return contenu.is(':visible') ? '<i class="fa fa-eye-slash fa-2x"></i>' : '<i class="fa fa-filter fa-2x"></i>';
            });
            return false;
        });
    });

    $("input:checkbox:not(:checked)").each(function() {
        var column = "table ." + $(this).attr("name");
        $(column).hide();
    });

    $("input[type='checkbox']").click(function(){
        var column = "table ." + $(this).attr("name");
        $(column).toggle();
    });

    $("input[type='checkbox']").click(function(){
        var column = "table ." + $(this).attr("name");
        $(column).removeClass('hidden');
    });


     


});