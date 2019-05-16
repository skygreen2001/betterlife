$(function(){
    var rtype = $_.params("rtype");
    $.getJSON("api/web/report/config.php?rtype=" + rtype, function(response){
        var columns = response.columns;
        var title  = response.title;
        var intro  = response.intro;
        var rtitle = title + " ( 说明: " + intro + " )";
        // 设置报表标题
        $("#rtitle").html(rtitle);
        $("#rtitle").attr("title", rtitle);

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
                        d.startDate = '';
                        d.endDate   = '';

                        // [Add parameter to datatable ajax call before draw](https://stackoverflow.com/questions/28906515/add-parameter-to-datatable-ajax-call-before-draw)
                        // Retrieve dynamic parameters
                        var dt_params = $('#infoTable').data('dt_params');
                        // Add dynamic parameters to the data object sent to the server
                        if(dt_params){ $.extend(d, dt_params); }
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
                var params = infoTable.ajax.params();
                params = "&query=" + params.query + "&startDate=" + params.startDate + "&endDate=" + params.endDate;
                var link   = "index.php?go=report.reportone.export&rtype=" + rtype + params;
                $.getJSON(link, function(response){
                    window.open(response.data);
                });
            });

            $.edit.datetimePicker('#startDate');
            $.edit.datetimePicker('#endDate');

            $(".datetimeStyle").on("dp.change", function(e) {
                $(this).children("input").val(e.date.format("YYYY-MM-DD") + " 00:00");
                var startDate = $("#startDateStr").val();
                var endDate   = $("#endDateStr").val();
                if (startDate && endDate) {
                    $('#infoTable').data('dt_params', { startDate: startDate, endDate: endDate });
                    // console.log(infoTable.ajax.params());
                    infoTable.draw();
                }
            });
        }
    });


});
