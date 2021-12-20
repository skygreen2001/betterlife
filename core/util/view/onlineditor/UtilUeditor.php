<?php
/**
 * -----------| 定义 UEditor 在线编辑器 |-----------
 * @category betterlife
 * @package util.view
 * @subpackage onlinediotr
 * @author skygreen
 * @link http://fex.baidu.com/ueditor/ [ueditor API 文档]
 * @link http://www.comsharp.com/GetKnowledge/zh-CN/It_News_k1067.aspx [百度 UEditor Web 编辑器同 CMS 集成全攻略]
 */
class UtilUeditor extends Util
{

    /**
     * 设置标准toolbar
     */
    public static function toolbar_normal()
    {
        return "[
                    [

                      'fontfamily', 'fontsize', 'paragraph', 'forecolor', 'backcolor','bold', 'italic', 'underline', 'fontborder', 'strikethrough','|',
                      'lineheight', 'indent', 'touppercase', 'tolowercase','superscript', 'subscript','insertorderedlist', 'insertunorderedlist', '|',
                    ],
                    [ 'link', 'unlink','simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map','spechars','wordimage','|',
                      'undo','redo', 'removeformat', 'formatmatch', 'autotypeset','background','template','snapscreen','preview', 'searchreplace','source','fullscreen'
                    ]

                ]";
    }

    /**
     * 预加载UEditor的JS函数
     * 
     * 如何阻止div标签自动转换为p标签:http://fex-team.github.io/ueditor/#qa-allowDivToP
     * 
     * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID
     * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用
     * @param string form_id  在线编辑器所在的Form的ID
     * @param string $configString 配置字符串
     */
    public static function loadJsFunction($textarea_id, $viewObject = null, $form_id = null, $configString = "")
    {
        $is_toolbar_full      = false;
        $info_install_ueditor = "";
        $uc_file = Gc::$nav_root_path . "misc" . DS . "js" . DS . "onlineditor" . DS . "ueditor" . DS . "ueditor.config.js";
        $ue_readme_url = Gc::$url_base . "install" . DS . "README.md";
        if ( !file_exists($uc_file) ) {
            $info_install_ueditor = '<div class=\"alert alert-danger\" role=\"alert\">很遗憾不能正常显示在线编辑器! <a target="_blank" href=\"' . $ue_readme_url . '\" class=\"alert-link\">请下载帮助文档后按要求安装好UEditor</a>.</div>';
        }

        if ( $is_toolbar_full ) {
            UtilJavascript::loadJsContentReady( $viewObject, "
                var ue_{$textarea_id};
                function pageInit_ue_{$textarea_id}()
                {
                    ue_{$textarea_id} = UE.getEditor('{$textarea_id}',{
                        allowDivTransToP: false
                    });
                }
                $('#$textarea_id').before('$info_install_ueditor');
                "
            );
        } else {
            if ( empty($configString) ) {
                $configString = self::toolbar_normal();
            }
            UtilJavascript::loadJsContentReady( $viewObject, "
                var ue_{$textarea_id};
                function pageInit_ue_{$textarea_id}()
                {
                    ue_{$textarea_id} = UE.getEditor('{$textarea_id}', {
                        toolbars: $configString,
                        allowDivTransToP: false
                    });
                    $.edit.ueditorFullscreen('$textarea_id');
                }
                $('#$textarea_id').before('$info_install_ueditor');
                "
            );
        }
    }

}