<?php
require_once ("../../../../../init.php");
require_once ("../../../../../core/autocode/AutoCodeAction.php");

if (isset($_REQUEST["type"])&&!empty($_REQUEST["type"])){
    $type=$_REQUEST["type"];
}else{
    if ($_REQUEST["type"]==0){
        $type=0;
    }else{
        $type=2;
    }
}
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
    $save_dir=$_REQUEST["save_dir"];
    AutoCodeView::$save_dir =$save_dir;
    AutoCodeView::$type     =$type;
    AutoCodeView::$showReport="";
    AutoCodeView::$showReport.=AutoCodeFoldHelper::foldEffectReady();
    AutoCodeView::$showReport.="<br/>";
    AutoCodeView::$showReport.=AutoCodeFoldHelper::foldbeforeaction();
    AutoCodeView::AutoCode();
    AutoCodeView::$showReport.="<br/>";
    AutoCodeView::$showReport.=AutoCodeFoldHelper::foldafteraction();
    echo AutoCodeView::$showReport;
}  else {
    AutoCodeView::UserInput($type);
}

?>
