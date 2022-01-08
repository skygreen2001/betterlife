<?php

/**
 * -----------| 在这里实现自定义模块库的加载 |-----------
 * @TODO 现无自定义模块库
 * @category betterlife
 * @package library
 * @author skygreen
 */
class Library_Loader
{
    /**
     * @var 加载库的标识
     */
    const SPEC_ID   = "id";
    /**
     * @var 加载库的名称
     */
    const SPEC_NAME = "name";
    /**
     * @var yes:加载，no:不加载；如果不定义则代表该库由逻辑自定义开关规则。
     */
    const SPEC_OPEN = "open";
    /**
     * @var 加载库的方法
     */
    const SPEC_INIT = "init";
    /**
     * @var 是否必须加载的
     */
    const SPEC_REQUIRED = "required";
    /**
     * @var 是否加载: 是
     */
    const OPEN_YES = "true";
    /**
     * @var 是否加载: 否
     */
    const OPEN_NO  = "false";
    /**
     * 加载库的规格Xml文件名。
     */
    const FILE_SPEC_LOAD_LIBRARY = "load.library.xml";
    /**
     * 加载库遵循以下规则:
     * 1.加载的库文件应该都放在library目录下以加载库的名称为子目录的名称内
     * 2.是否加载库由load.library.xml文件相关规范说明决定。
     * 3.name:加载库的名称，要求必须是英文和数字。
     * 4.init:加载库的方法，一般库有一个头文件，该方法由库提供者定义在本文件内。
     * 5.open:是否加载库。true:加载，false:不加载；如果不定义则代表该库由逻辑自定义开关规则。
     * 6.required:是否必须加载的，如无定义，则根据open定义加载库。
     */
    public static function load_run()
    {
        $spec_library = UtilXmlSimple::fileXmlToArray( dirname(__FILE__) . DS . self::FILE_SPEC_LOAD_LIBRARY );
        foreach ($spec_library["resourceLibrary"] as $block) {
            $blockAttr = $block[Util::XML_ELEMENT_ATTRIBUTES];
            if (array_key_exists(self::SPEC_REQUIRED, $blockAttr)) {
                if (strtolower($blockAttr[self::SPEC_REQUIRED]) == 'true') {
                    $method = $blockAttr[self::SPEC_INIT];
                    if (method_exists(__CLASS__, $method)) {
                        self::$method();
                    }
                }
            } else {
                if (array_key_exists(self::SPEC_OPEN, $blockAttr)) {
                    if (strtolower($blockAttr[self::SPEC_OPEN])==self::OPEN_YES) {
                        $method = $blockAttr[self::SPEC_INIT];
                        if (method_exists(__CLASS__, $method)) {
                            self::$method();
                        }
                    }
                }
            }
        }
    }

    /**
     * 第三方库路径定义为: Gc::$nav_root_path . "install" . DS . "library" . DS 
     * @return string 返回库所在目录路径
     */
    private static function dir_library()
    {
        return Gc::$nav_root_path . Config_F::ROOT_INSTALL . DS . Config_F::ROOT_LIBRARY . DS;
    }

    /**
     * 加载通信模块
     *
     */
    public static function load_communication()
    {
        //加载数据处理方案的所有目录进IncludePath
        $communication_root_dir = self::dir_library() . "communication" . DS;
        echo "load communication: 加载通信模块, 路径: " . $communication_root_dir; 
        // //加载模块里所有的文件
        // load_module( Config_F::ROOT_LIBRARY, $communication_root_dir, "webservice" );
        // load_module( Config_F::ROOT_LIBRARY, $communication_root_dir . "webservice" . DS, "nusoap" );
        die();
    }

    /**
     * 加载条形码功能: 引用第三方开源功能
     * @link http://www.barcodephp.com
     */
    public static function load_barcode()
    {
        // 加载数据处理方案的所有目录进IncludePath
        $barcode_root_dir = self::dir_library() . "barcode" . DS;
        echo "load barcode: 加载条形码功能, 路径: " . $barcode_root_dir; 
        // require_once(self::dir_library() . "barcode" . DS . "UtilBarCode.php");
        // $barcode_root_dir .= "class" . DS;
        // //加载模块里所有的文件
        // load_module( Config_F::ROOT_LIBRARY, $barcode_root_dir ); 
        die();
    }
}
