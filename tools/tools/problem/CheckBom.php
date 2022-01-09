<?php

require_once("../../../init.php");
/**
 * 检查清除文件头的Bom
 * @link http://www.qinbin.me/removal-of-the-bom-header-php-file/
 */
class CheckBOMTask
{
    /**
     * 是否需要清除文件头的Bom
     * @var boolean
     */
    public static $isRemoveBom = false;

    public static $checkDir    = "../../../";
    /**
     * 指定文件不进行检查
     * @var array
     */
    private static $exclude_files_check = array(
        '.',
        '..',
        '.git',
        '.vscode',
        '.gitignore',
        ".DS_Store"
    );
    /**
     * 指定文件夹里的文件不进行检查
     * @var array
     */
    private static $exclude_check = array(
        "install/bower_components",
        "install/node_modules",
        "install/vendor",
        "app/html5/bower_components",
        "app/html5/node_modules",
        "home/betterlife/view/default/tmp",
        "home/betterlife/view/bootstrap/tmp",
        "home/betterlife/view/twig/tmp",
        "home/admin/view/default/tmp",
        "home/report/view/default/tmp",
        "home/model/view/default/tmp",
        "docs",
        "misc/css/fonts",
        "misc/js/onlineditor/ueditor",
        "misc/js/onlineditor/ueditor_bak",
        "upload",
        "model",
        "log"
    );
    /**
     * 检查文件头是否有Bom
     * @param mixed $isRemoveBom 是否需要清除文件头的Bom
     */
    public static function run($isRemoveBom = false, $checkDir = "../../../")
    {
        self::$isRemoveBom = $isRemoveBom;
        if (isset($_GET['dir'])) {
            $checkDir = $_GET['dir'];
        }
        if (endWith($checkDir, DS)) {
            $checkDir = substr($checkDir, 0, strlen($checkDir) - strlen(DS));
        }

        for ($i = 0; $i < count(self::$exclude_check); $i++) {
            self::$exclude_check[$i] = CheckBOMTask::$checkDir . self::$exclude_check[$i];
        }
        // print_r(self::$exclude_check); die();

        self::checkdir($checkDir);
        // checkdir($checkDir . "misc/js/");
        // checkdir($checkDir . "core/");
        // checkdir($checkDir . "api/");
        // checkdir($checkDir . "app/");
        // checkdir($checkDir . "docs/");
        // checkdir($checkDir . "log/");
        // checkdir($checkDir . "upload/");
        // checkdir($checkDir . "home/");
        // checkdir($checkDir . "model/");
        // checkdir($checkDir . "install/");
        // checkdir($checkDir . "taglib/");
        // checkdir($checkDir . "tools/");
    }

    private static function checkdir($basedir)
    {
        if ($dh = opendir($basedir)) {
            while (( $file = readdir($dh) ) !== false) {
                if (!in_array($file, self::$exclude_files_check)) {
                // if ($file != '.' && $file != '..' && $file != '.git' && $file != '.vscode' && $file != '.gitignore') {
                    if (!is_dir($basedir . DS . $file)) {
                        // echo "dir file name:  " . $basedir . DS . $file . " <br>";
                        if (self::$isRemoveBom) {
                            echo "filename: " . $basedir . DS . $file . self::checkBOM($basedir . DS . $file) . " <br>";
                        } else {
                            $cb = self::checkBOM($basedir . DS . $file);
                            if ($cb) {
                                echo "filename: " . $basedir . DS . $file . " " . $cb . " <br>";
                            }
                        }
                    } else {
                        $dirname = $basedir . DS . $file;
                        if (!in_array($dirname, self::$exclude_check)) {
                            self::checkdir($dirname);
                        }
                    }
                } else {
                    // echo "filename:  " . $file . " <br>";
                }
            }
            closedir($dh);
        }
    }

    /**
     * 检查清除文件头包含的Bom
     * @param string $filename 要检查的文件
     * @return string
     */
    private static function checkBOM($filename)
    {
        $contents   = file_get_contents($filename);
        $charset[1] = substr($contents, 0, 1);
        $charset[2] = substr($contents, 1, 1);
        $charset[3] = substr($contents, 2, 1);
        if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
            if (self::$isRemoveBom) {
                $rest = substr($contents, 3);
                UtilFileSystem::rewrite($filename, $rest);
                return (", <font color=red>BOM found, automatically removed.</font>");
            } else {
                return (", <font color=red>BOM found.</font>");
            }
        } else {
            //if (self::$isRemoveBom ) return ("BOM Not Found."); else return "";
        }
    }
}

CheckBOMTask::run(CheckBOMTask::$isRemoveBom, CheckBOMTask::$checkDir);
