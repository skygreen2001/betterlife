$(function(){
    //Datatables中文网[帮助]: http://datatables.club/
    if ($.dataTable) {
        var infoTable = $('#infoTable').DataTable({
            "language"  : $.dataTable.chinese,
            "processing": true,
            "serverSide": true,
            "retrieve"  : true,
            "ajax": {
                "url" : "api/web/blog.php",
                "data": function ( d ) {
                    d.query    = $("#input-search").val();
                    d.pageSize = d.length;
                    d.page     = d.start / d.length + 1;
                    d.limit    = d.start + d.length;
                    return d;
                },
                //可以对返回的结果进行改写
                "dataFilter": function(data){
                    return data;
                }
            },
            "responsive"   : true,
            "searching"    : false,
            "ordering"     : false,
            "dom"          : '<"top">rt<"bottom"ilp><"clear">',
            "deferRender"  : true,
            "bStateSave"   : true,
            "bLengthChange": true,
            "aLengthMenu"  : [[10, 25, 50, 100,-1],[10, 25, 50, 100,'全部']],
            "columns": [
                { data:"blog_name" },
                { data:"user_name" },
                { data:"icon_url" },
                { data:"isPublic" },
                { data:"status" },
                { data:"publish_date"},
                { data:"blog_id" }
            ],
            "columnDefs": [
                {"orderable": false, "targets": 2,
                 "render"   : function(data, type, row) {
                    // 该图片仅供测试
                    if ( !data ) data = "https://lorempixel.com/900/500?r=1";
                    var blog_id = row.blog_id;
                    var result = '<a id="' + "imgUrl" + blog_id + '" href="#"><img src="' + data + '" class="img-thumbnail" alt="' + row.blog_name + '" /></a>';

                    $("body").off('click', 'a#imgUrl' + blog_id);
                    $("body").on('click', 'a#imgUrl' + blog_id, function(){
                        var imgLink = $('a#imgUrl' + blog_id + " img").attr('src');
                        $('#imagePreview').attr('src', imgLink);
                        $('#imagePreview-link').attr('href', imgLink);
                        var isShow = $.dataTable.showImages($(this).find("img"), "#imageModal .modal-dialog");
                        if (isShow) $('#imageModal').modal('show'); else window.open(imgLink, '_blank');
                    });
                    return result;
                 }
                },
                {"orderable": false, "targets": 3,
                 "render"   : function(data,type,row){
                    if ( data == 1 ) {
                        return '是';
                    } else {
                        return '否';
                    }
                 }
                },
                {"orderable": false, "targets": 4,
                 "render"   : function(data, type, row){
                    switch (data) {
                      case '0':
                        return '<span class="status-wait">待审核</span>';
                        break;
                      case '1':
                        return '<span class="status-pass">正常</span>';
                        break;
                      default:
                        return '<span class="status-fail">已结束</span>';
                    }
                 }
                },
                {"orderable": false, "targets": 6,
                 "render"   : function(data, type, row){
                    var result = $.templates("#actionTmpl").render({ "id"  : data });

                    $("a#info-view"+data).click(function(){
                        var pageNo = $_.params("pageNo");
                        if (!pageNo ) pageNo = 1;
                        location.href = 'index.php?go=admin.blog.view&id='+data+'&pageNo='+pageNo;
                    });

                    $("a#info-edit"+data).click(function(){
                        var pageNo = $_.params("pageNo");
                        if (!pageNo ) pageNo = 1;
                        location.href = 'index.php?go=admin.blog.edit&id='+data+'&pageNo='+pageNo;
                    });

                    $("body").off('click', 'a#info-dele' + data);
                    $("body").on('click', 'a#info-dele' + data, function(){//删除
                        bootbox.confirm("确定要删除该博客:" + data + "?",function(result){
                            if ( result == true ){
                                $.get("index.php?go=admin.blog.delete&id="+data, function(response, status){
                                    $( 'a#info-dele' + data ).parent().parent().css("display", "none");
                                });
                            }
                        });
                    });
                    return result;
                }
             }
            ],
            "initComplete":function(){
                $.dataTable.filterDisplay();
            },
            "drawCallback": function( settings ) {
                $.dataTable.pageNumDisplay(this);
                $.dataTable.filterDisplay();
            }
        });
        $.dataTable.doFilter(infoTable);
    }

    if( $(".content-wrapper form").length ){
        $.edit.fileBrowser("#iconImage", "#iconImageTxt", "#iconImageDiv");
        $.edit.datetimePicker('#publishDate');
        $.edit.multiselect('#categoryIds');

        $("input[name='isPublic']").bootstrapSwitch();

        $('input[name="isPublic"]').on('switchChange.bootstrapSwitch', function(event, state) {
            console.log(state);
        });

        $('#editBlogForm').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            // focusInvalid: false,
            focusInvalid: true,
            // debug:true,
            rules: {
                blog_name:{
                    required:true
                },
                sequenceNo: {
                    required:true,
                    number:true,
                }
            },
            messages: {
                blog_name:"此项为必填项",
                sequenceNo:{
                    required:"此项为必填项",
                    number:"此项必须为数字"
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
            },
            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                $(e).remove();
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
});
