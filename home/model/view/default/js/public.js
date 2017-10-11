/**
 * 通用全局JS文件
 */
$(function() {
  $.extend({"edit":edit});
});

var edit = {
  fileBrowser: function(fieldName, inputName, btnBrowserName){
    $(inputName + "," + btnBrowserName).click(function(e){
        $(fieldName).trigger('click');
    });
    $(fieldName).change(function(){
        $(inputName).val($(this).val());
    });
  }
};
