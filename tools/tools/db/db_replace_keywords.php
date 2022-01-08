<?php

require_once("../../../init.php");
if (isset($_REQUEST["oldwords"]) && !empty($_REQUEST["oldwords"])) {
    $oldwords = $_REQUEST["oldwords"];
    if (isset($_REQUEST["newwords"]) && !empty($_REQUEST["newwords"])) {
        $newwords = $_REQUEST["newwords"];
    } else {
        $newwords = Gc::$appName;
    }

    $filterTableColumns = UtilDb::keywords_table_columns($oldwords);

    if ($filterTableColumns) {
        echo "存在[{$oldwords}]的表列清单如下<br/>";
        foreach ($filterTableColumns as $key => $columns) {
            echo "表名:$key<br/>";
            foreach ($columns as $column) {
                echo "===$column===<br/>";
            }
        }
        echo "<br/>";

        echo "查询[{$oldwords}]的SQL语句清单如下<br/>";
        foreach ($filterTableColumns as $key => $columns) {
            foreach ($columns as $column) {
                echo "select * from $key where $column like '%$oldwords%';<br/>";
            }
        }
        echo "<br/>";

        echo "将[{$oldwords}]替换成[{$newwords}]的SQL语句清单如下<br/>";
        foreach ($filterTableColumns as $key => $columns) {
            foreach ($columns as $column) {
                echo "update $key set $column  = replace($column,'$oldwords','$newwords');<br/>";
            }
        }
    } else {
        echo "在数据库里没有关键字:[{$oldwords}]";
    }
} else {
    echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
           <html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">';
    echo "<head>" . HH;
    echo UtilCss::form_css() . HH;
    $url_base = UtilNet::urlbase();
    $title    = "替换关键词";
    echo '<style>';
    echo 'label {';
    echo '    width:auto';
    echo '}';
    echo '.btn-confirm {';
    echo '    display: inline-block;';
    echo '    margin: 0 4px;';
    echo '    padding: 4px 12px;';
    echo '    border-radius: 4px;';
    echo '    border-bottom: 4px solid #3aa373;';
    echo '    font-size: 14px;';
    echo '    color: #fff;';
    echo '    background-color: #4fc08d;';
    echo '}';
    echo '.btn-confirm:active, .btn-confirm:focus, .btn-confirm:hover {';
    echo '    color: #fff;';
    echo '    cursor: pointer;';
    echo '    border: 0px;';
    echo '    border-bottom: 4px solid #3aa373;';
    echo '    background: -webkit-linear-gradient(left,#3fe69a, #4fc08d);';
    echo '    box-shadow: 0 4px 8px 0 rgba(186, 210, 199, 0.8);';
    echo '}';
    echo '</style>';
    echo "<script type='text/javascript' src='" . $url_base . "misc/js/util/file.js'></script>";
    echo "</head>";
    echo "<body>";
    echo "<br/><br/><br/><br/><br/><h1 align='center'>$title</h1>";
    echo "<div align='center' height='450'>";
    echo "<form>";
    echo "  <div style='line-height:1.5em;'>";
    echo "      <label>原关键字:</label><input type=\"text\" name=\"oldwords\" value=\"\" id=\"oldwords\" /><br/><br/>";
    echo "      <label>新关键字:</label><input type=\"text\" name=\"newwords\" value=\"\" id=\"newwords\" /><br/><br/>";
    echo "  </div>";
    echo "  <input class='btn-confirm' type='submit' value='确定' /><br/>";
    echo "</form>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
}
