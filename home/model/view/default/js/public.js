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
  },
  //解决Ueditor在线编辑器与顶部导航条遮挡的问题
  //全屏前，顶部导航条在最顶上，全屏后，在线编辑器在最顶部
  ueditorFullscreen: function(textareaIdName){
    UE.getEditor(textareaIdName).addListener('beforefullscreenchange',function(event,isFullScreen){
       if (isFullScreen) {
         $("#" + textareaIdName + " > .edui-editor.edui-default").addClass("bb-edui-fullscreen");
       } else {
         $("#" + textareaIdName + " > .edui-editor.edui-default").removeClass("bb-edui-fullscreen");
       }
    });
  },
  select2: function(selectName, ajaxUrl, defaultSelectValue, cache, delay){
    if(!cache) cache = true;
    if(!delay) delay = 250;
    if (!defaultSelectValue) defaultSelectValue = [];
    if (ajaxUrl) {
      $(selectName).select2({
        placeholder: {
          id: '-1',
          text: '请选择'
        },
        allowClear: true,
        data: defaultSelectValue,
        // closeOnSelect:false,
        language: "zh-CN",
        ajax: {
          url: ajaxUrl,
          dataType: 'json',
          delay: delay,
          cache: cache,
          // debug: true,
          data: function (query) {
            return query;
          },
          processResults: function (response,params) {
            return {
              results: response.data
            };
          }
        }
      });
    } else {
      $(selectName).select2({
          placeholder: {
            id: '-1',
            text: '请选择'
          },
          allowClear: true,
          data: defaultSelectValue,
          language: "zh-CN"
      });
    }
    if (defaultSelectValue && defaultSelectValue.length>0){
      var len = defaultSelectValue.length;
      if (len == 1){
        $(selectName).val(defaultSelectValue[0].id).trigger("change");
      } else {
        var selVal = new Array(len);
        for (var i = 0; i < len; i++) {
          selVal[i]= defaultSelectValue[i].id;
        }
        $(selectName).val(selVal).trigger("change");
      }
    }
  }
};
