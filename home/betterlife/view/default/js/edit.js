var edit = {
  //解决Ueditor在线编辑器与顶部导航条遮挡的问题
  //全屏前，顶部导航条在最顶上，全凭后，在线编辑器在最顶部
  ueditorFullscreen: function(textareaIdName){
    UE.getEditor(textareaIdName).addListener('beforefullscreenchange',function(event,isFullScreen){
        var edui = document.querySelector("#" + textareaIdName + " > .edui-editor.edui-editor");
        if (isFullScreen && edui) {
          edui.classList.add("bb-edui-fullscreen");
        } else {
          edui.classList.remove("bb-edui-fullscreen");
        }
    });
  }
};

(function(window, document){
  // 如果使用了jQuery库，需删除该声明
  window.$ = edit;
  window.$.edit = edit;
})(window, document);
