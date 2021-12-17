<?php
use PhpOffice\PhpSpreadsheet\Cell\DataType;
/**
 * -----------| 功能:处理Excel相关的事宜方法 |-----------
 * 
 * PhpSpreadsheet's documentation: https://phpspreadsheet.readthedocs.io
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilExcel extends Util
{
    /**
     * 将数组转换成Excel文件
     * 示例:
     * 
     *     1. 直接下载:UtilExcel::arraytoExcel($arr_output_header,$regions,"regions.xlsx",true);
     *     2. 保存到本地指定路径:
     * @param array $arr_output_header 头信息数组
     * @param array $excelarr          需要导出的数据的数组
     * @param string $outputFileName   输出文件路径
     * @param bool $isDirectDownload   是否直接下载。默认是否，保存到本地文件路径
     * @param bool $isExcel2007        是否使用 Excel2007
     */
    public static function arraytoExcel($arr_output_header, $excelarr, $outputFileName = null, $isDirectDownload = false, $isExcel2007 = false)
    {
        $pv = (float) phpversion();
        if ( $pv <= 5.5 ) {
            UtilExcelOld::arraytoExcel( $arr_output_header, $excelarr, $outputFileName, $isDirectDownload, $isExcel2007 );
            die();
        }
        UtilFileSystem::createDir( dirname($outputFileName) );
        $objActSheet = array ();
        $objExcel    = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        self::addSuffixInfo($objExcel);

        if ( $isExcel2007 ) {
            if ( !function_exists("zip_open") ) { LogMe::log( "后台下载功能需要Zip模块支持,名称:php_zip<br/>", EnumLogLevel::ALERT ); die(); }
            $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objExcel);
            $objWriter->setOffice2003Compatibility(true);
        }else{
            $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xls($objExcel);
        }
        $objExcel->setActiveSheetIndex(0);
        $objActsheet = $objExcel->getActiveSheet();

        //获取表头
        $i = 1;
        if ( $arr_output_header ) {
            $column = 'A';
            foreach ($arr_output_header as $key => $value)
            {
                if ( $column > 'A' ) $value = str_replace(array('标识', '编号', '主键'), "", $value);
                $objActsheet->setCellValue($column . $i, $value);
                $column++;
            }
            $i++;
        }
        //获取表内容
        if ( !empty($excelarr) ) {
            if ( is_object($excelarr) ) $excelarr = array($excelarr);
            foreach ($excelarr as $record)
            {
                $column = 'A';
                foreach ($arr_output_header as $key => $value)
                {
                    if ( is_array($record) ) {
                        // $objActsheet->setCellValue($column . $i, $record[$key]);
                        // 列以文本格式导出，如身份证号、银行卡号即使全部是数字，也以文本格式导出。
                        $objActsheet->setCellValueExplicit($column . $i, $record[$key], DataType::TYPE_STRING);
                    } else {
                        // $objActsheet->setCellValue($column . $i, $record->$key);
                        // 列以文本格式导出，如身份证号、银行卡号即使全部是数字，也以文本格式导出。
                        $objActsheet->setCellValueExplicit($column . $i, $record->$key, DataType::TYPE_STRING);
                        // $objActsheet->getStyle($column . $i)->getNumberFormat()->setFormatCode("@");
                    }
                    // $objActsheet->getStyle($column . $i)->getNumberFormat()->setFormatCode("@");

                    $column++;
                }
                $i++;
            }
        }

        if ( empty($outputFileName) ) {
            if ( $isExcel2007 ) $outputFileName = date("YmdHis") . ".xlsx"; else $outputFileName = date("YmdHis") . ".xls";
        } else {
            if ( $isExcel2007 ) {
                if ( endWith($outputFileName, ".xls") ) $outputFileName  = str_replace(".xls", ".xlsx", $outputFileName);
            } else {
                if ( endWith($outputFileName, ".xlsx") ) $outputFileName = str_replace(".xlsx", ".xls", $outputFileName);
            }
        }

        if ( $isDirectDownload ) {
            $outputFileName = basename($outputFileName);
            ob_end_clean();
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            Header("Content-Disposition:attachment;filename=" . $outputFileName);
            //header('Content-Disposition:inline;filename="'.$outputFileName.'"');
            header("Content-Transfer-Encoding: binary");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
            $objWriter->save('php://output');
        } else {
            //导出到服务器
            //$outputFileName=UtilString::utf82gbk($outputFileName);
            $objWriter->save($outputFileName);
        }
        $objExcel->disconnectWorksheets();
        unset($objExcel);
    }

    /**
     * Excel 日期时间转换Php认知的日期时间格式
     * @link http://hi.baidu.com/greenxm/item/80f8f0ce0004bbd297445243
     * @param mixed $days
     * @param mixed $time
     */
    public static function exceltimtetophp($days, $time = false)
    {
        if ( is_numeric($days) )
        {
            $jd        = GregorianToJD(1, 1, 1970);
            $gregorian = JDToGregorian($jd + intval($days) - 25569);
            $myDate    = explode('/', $gregorian);
            $myDateStr = str_pad($myDate[2], 4, '0', STR_PAD_LEFT) . "-" . str_pad($myDate[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($myDate[1], 2, '0', STR_PAD_LEFT) . ($time ? " 00:00:00" : '');
            return $myDateStr;
        }
        return $days;
    }

    /**
     * 从Excel文件获取行数据转换成数组
     * @param string $importFileName 导入Excel文件名称(包括完整的文件路径)
     * @param array $arr_import_header 头信息数组
     * @param string $sheetIndexOrName Excel文件sheet序号或者sheet名称
     * 测试用例:
     * ```
     *     $blog_path = Gc::$upload_path . "blog.xls";
     *     $arr_import_header = Service::fieldsMean( Blog::tablename() );
     *     $content = UtilExcel::exceltoArray($blog_path, $arr_import_header);
     *     $content = UtilExcel::exceltoArray($blog_path, $arr_import_header, 1);
     *     $content = UtilExcel::exceltoArray($blog_path, $arr_import_header, "blog");
     *     $content = UtilExcel::exceltoArray($blog_path, $arr_import_header, "博客");
     *     print_pre($content, true);
     * ```
     * 
     * @return array
     */
    public static function exceltoArray($importFileName, $arr_import_header, $sheetIndexOrName = 0)
    {
        $pv = (float) phpversion();
        if ( $pv <= 5.5 ) {
            UtilExcelOld::exceltoArray( $importFileName, $arr_import_header );
            die();
        }
        $result   = null;
        $filetype = explode('.', $importFileName);
        $filetype = end($filetype);

        if ( empty($importFileName) )
        {
            LogMe::log( '路径或文件名有错！' );
            return null;
        }
        if ( $filetype == 'xls' || $filetype == 'xlsx' )
        {
            if ( $filetype == 'xls' ) {
                $PHPReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $PHPReader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $PHPExcel  = $PHPReader->load($importFileName);
            if ( !$PHPExcel ) {
                LogMe::log( '请确保Excel格式正确！' );
                return null;
            }

            if ($PHPExcel->getSheetCount() <= 0) {
                LogMe::log( '请确保Excel存在数据！' );
                LogMe::log( print_pre( $PHPExcel ) );
                return null;
            }
            try
            {
                $PHPReader->setReadDataOnly(true);
                // LogMe::log( print_pre( $PHPExcel ) );
                if ( is_numeric($sheetIndexOrName) ) {
                    $currentSheet = $PHPExcel->getSheet( $sheetIndexOrName );
                } else {
                    $currentSheet = $PHPExcel->getSheetByName( $sheetIndexOrName );
                }
                if ( !$currentSheet ) {
                    return array();
                }

                //取得excel的sheet
                $allColumn    = $currentSheet->getHighestColumn(); //表中列数
                $allRow       = $currentSheet->getHighestRow(); //表中行数
            }
            catch(Exception $e)
            {
                LogMe::log( $e );
                return null;
            }

            $num_tempcol       = alphatonumber($allColumn);
            $currentColumn     = 'A';
            $num_currentColumn = alphatonumber($currentColumn);
            //从Excel文档中获取头信息
            for ($num_currentColumn; $num_currentColumn <= $num_tempcol; $num_currentColumn++) {
                $address  = $currentColumn."1";
                $header[] = trim($currentSheet->getCell($address)->getValue());
                $currentColumn++;
            }
            $arr_import_header = array_flip($arr_import_header);
            $arr_head          = array();
            foreach ($header as $value) {
                if ( empty($value) ) continue;
                if ( !array_key_exists($value, $arr_import_header) ) {
                    $key_words = array('标识', '编号', '主键');
                    foreach ($key_words as $key_word) {
                        if ( array_key_exists($value . $key_word, $arr_import_header) ) {
                            $value = $value . $key_word;
                            break;
                        }
                    }
                   if ( !array_key_exists($value,$arr_import_header) ) $arr_head[] = $value;
                }
                if ( !in_array($value, $arr_head) && array_key_exists($value, $arr_import_header) ) $arr_head[] = $arr_import_header[$value];
            }

            //从Excel文档中获取所有内容
            for ($currentRow = 2, $i = 1; $currentRow <= $allRow; $currentRow++, $i++)
            {
                $num_tempcol       = alphatonumber($allColumn);
                $currentColumn     = 'A';
                $num_currentColumn = alphatonumber($currentColumn);
                for ($num_currentColumn; $num_currentColumn <= $num_tempcol; $num_currentColumn++)
                {
                    $address      = $currentColumn . $currentRow;
                    $result[$i][] = trim($currentSheet->getCell($address)->getValue());
                    ++$currentColumn;
                }
            }

            //将头信息数组作为键，内容数组作为Value；获取可转化为数据对象的数组
            if ( $result ) {
                $result_tmp = array();
                foreach ($result as $value) {
                    $count_k = count($arr_head);
                    $count_v = count($value);
                    if ( $count_v > $count_k ) {
                        for ($i = $count_k; $i < $count_v; $i++) {
                            unset($value[$i]);
                        }
                    }
                    $result_tmp[] = array_combine($arr_head, $value);
                }
                $result = $result_tmp;
            }
        }
        return $result;
    }

    /**
     * 添加Excel文档附加信息
     * @param @mixed $spreadsheet Excel文档
     */
    private static function addSuffixInfo($spreadsheet) {
        $spreadsheet->getProperties()
                    ->setCreator('skygreen2001')
                    ->setLastModifiedBy("skygreen2001")
                    ->setTitle(Gc::$site_name)
                    ->setSubject(Gc::$site_name)
                    ->setDescription(Gc::$site_name)
                    ->setKeywords(Gc::$site_name)
                    ->setCategory(Gc::$site_name);
    }

    /**
     * 将数组转换成CSV文件
     * 示例:
     * 
     *     1. 直接下载:UtilExcel::arraytoCsv($arr_output_header, $regions, "regions.csv", true);
     *     2. 保存到本地指定路径
     * 
     * @param array $arr_output_header 头信息数组
     * @param array $excelarr 需要导出的数据的数组
     * @param string $outputFileName 输出文件路径
     * @param bool $isDirectDownload 是否直接下载。默认是否，保存到本地文件路径
     */
    public static function arraytoCsv($arr_output_header, $excelarr, $outputFileName = null, $isDirectDownload = false)
    {
        //打开PHP文件句柄,php://output 表示直接输出到浏览器,$outputFileName表示输出到指定路径文件下
        if ( $isDirectDownload ) {
            $fp = fopen("php://output", 'w');
        } else {
            $fp = fopen($outputFileName, 'w');
        }
        
        if ( $isDirectDownload ) {
            $outputFileName = basename($outputFileName);
            ob_end_clean();
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Type: application/vnd.ms-excel');
            Header("Content-Disposition:attachment;filename=" . $outputFileName);
            //header('Content-Disposition:inline;filename="'.$outputFileName.'"');
            header("Content-Transfer-Encoding: binary");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
        }

        //输出Excel列名信息
        foreach ($arr_output_header as $key => $value) {
            //CSV的Excel支持GBK编码，一定要转换，否则乱码
            $arr_output_header[$key] = iconv('utf-8', 'gbk', $value);
        }

        //将数据通过fputcsv写到文件句柄
        fputcsv($fp, $arr_output_header);

        //计数器
        $num = 0;

        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;

        foreach ($excelarr as $key => $row) {
            $num++;

             //刷新一下输出buffer，防止由于数据过多造成问题
             if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }

            foreach ($row as $ikey => $value) {
                $row[$ikey] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }

        fclose($fp);
    }
    /**
     * 从CSV文件获取行数据转换成数组
     * 
     * @param string $importFileName 导入CSV文件名称(包括完整的文件路径)
     * @param array $arr_import_header 头信息数组
     * 参数示例如下:
     * ```
     *      $arr_import_header = array(
     *          "a1" => "列头1",
     *          "a2" => "列头2",
     *          "a3" => "列头3",
     *          "a4" => "列头4",
     *          "a5" => "列头5",
     *          "a6" => "列头6"
     *      );
     * ```
     * 说明
     * 
     *     CSV文件第一行是列头说明，即每一列的文字说明，数据应从第二列开始
     * 测试用例:
     * ```
     *     $blog_path = Gc::$upload_path . "blog.xls";
     *     $arr_import_header = Service::fieldsMean( Blog::tablename() );
     *     $content = UtilExcel::csvtoArray($blog_path, $arr_import_header);
     *     print_pre($content, true);
     * ```
     * @return array
     */
    public static function csvtoArray($importFileName, $arr_import_header = null)
    {
        $result   = null;
        $handle = fopen($importFileName, 'r');
        if ( !$handle ) {
            return  '文件打开失败';
            LogMe::log( "文件打开失败:" . $importFileName ); die();
        }

        if ( !empty($arr_import_header) ) {
            // $arr_head = array_flip($arr_import_header);

            $arr_import_header = array_flip($arr_import_header);
            $arr_head          = array();
        }

        // 读取几行，默认全部读取
        $line   = 0;
        // 从第几行开始读，默认从第一行读取
        $offset = 0;
        $i = 0;
        $j = 0;
        $result = [];
        while ( $data = fgetcsv($handle) ) {
            //小于偏移量则不读取,但$i仍然需要自增
            if ( $i < $offset && $offset ) {
                $i++;
                continue;
            }
            //大于读取行数则退出
            if ( $i > $line && $line ) {
                break;
            }
            if ( !empty($arr_import_header) ) {
                $header = array();
            }
            foreach ($data as $key => $value) {
                $content = iconv("gbk","utf-8//IGNORE",$value);//转化编码
                if ( $j > 0 ) {
                    $result[$j][] = $content;
                } else {
                    if ( !empty($arr_import_header) ) {
                        $header[] = $content;
                    }
                }
            }

            if ( !empty($arr_import_header) ) {
                foreach ($header as $value) {
                    if ( empty($value) ) continue;
                    if ( !array_key_exists($value, $arr_import_header) ) {
                        $key_words = array('标识', '编号', '主键');
                        foreach ($key_words as $key_word) {
                            if ( array_key_exists($value . $key_word, $arr_import_header) ) {
                                $value = $value . $key_word;
                                break;
                            }
                        }
                        if ( !array_key_exists($value, $arr_import_header) ) $arr_head[] = $value;
                    }
                    if ( !in_array($value, $arr_head) && array_key_exists($value, $arr_import_header) ) $arr_head[] = $arr_import_header[$value];
                }
            }
            $i++;
            $j++;
        }

        //将头信息数组作为键，内容数组作为Value；获取可转化为数据对象的数组
        if ( $result && !empty($arr_import_header) ) {
            $result_tmp = array();
            foreach ($result as $value) {
                $count_k = count($arr_head);
                $count_v = count($value);
                if ( $count_v > $count_k ) {
                    for ($i = $count_k; $i < $count_v; $i++) {
                        unset($value[$i]);
                    }
                }
                $result_tmp[] = array_combine($arr_head, $value);
            }
            $result = $result_tmp;
        }
        return $result;
    }
}
