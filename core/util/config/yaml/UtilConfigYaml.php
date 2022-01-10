<?php

/**
 * -----------| 工具类: 读取Yaml配置文件类 |-----------
 * @category Betterlife
 * @package util.config
 * @subpackage yaml
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class UtilConfigYaml extends UtilConfig
{
    public function load($file)
    {
        if (file_exists($file) == false) {
            return false;
        }
        $this->_settings = Spyc::YAMLLoad($file);
    }

    /**
     * 调用方法
     */
    public static function main()
    {
        $settings = new UtilConfigYaml();
        $settings->load(__DIR__ . DS . 'setting.yaml');
        echo 'Yaml: ' . $settings->get('db.host') . '';
    }
}
