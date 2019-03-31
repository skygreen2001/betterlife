$(function(){
    var rtype = $_.params("rtype");
    $.getJSON("api/web/report/config.php?rtype=" + rtype, function(response){
        var columns = response.columns;
        var title  = response.title;
        var intro  = response.intro;
        var rtitle = title + " ( 说明: " + intro + " )";
        // 设置报表标题
        $("#rtitle").html(rtitle);

        var rhead = "";
        var dtColumns = [];
        for (var i = 0; i < columns.length; i++) {
            var column = columns[i];
            rhead += "<th>" + column + "</th>\r\n";
            dtColumns.push({ data: column });
        }
        // 设置报表抬头
        $("#rhead").html(rhead);

        //Datatables中文网[帮助]: http://datatables.club/
        if ($.dataTable) {
            var infoTable = $('#infoTable').DataTable({
                "language"  : $.dataTable.chinese,
                "processing": true,
                "serverSide": true,
                "retrieve"  : true,
                "ajax": {
                    "url" : "api/web/report/reportone.php?rtype=" + rtype,
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
                "columns": dtColumns,
                "initComplete":function(){
                    $.dataTable.filterDisplay();
                },
                "drawCallback": function( settings ) {
                    $.dataTable.pageNumDisplay(this);
                    $.dataTable.filterDisplay();
                }
            });
            $.dataTable.doFilter(infoTable);

            $("#btn-report-export").click(function(){
                var link   = "index.php?go=report.reportone.export&rtype=" + rtype;
                $.getJSON(link, function(response){
                    window.open(response.data);
                });
            });
        }
    });


});
