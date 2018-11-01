<?php
/**
 * 通过SimpleHtmlDom获取网页解析数据
 * Website: http://sourceforge.net/projects/simplehtmldom/
 * help:http://simplehtmldom.sourceforge.net/
 * Author: S.C. Chen <me578022@gmail.com>
 *
 * @author zhouyuepu
 */
class Data_SimpleHtmlDom {
    const USER_AGENT_IE="Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; Tablet PC 2.0)";
    const USER_AGENT_FIREFOX="Mozilla/5.0 (Windows; U; Windows NT 6.1; zh-CN; rv:1.9.1.11) Gecko/20100701 Firefox/3.5.1";
    public static $user_agent=self::USER_AGENT_IE;

    /**
     *
     * @param string $url 获取页面的路径
     * @param $root_url 抓取网站的根路径
     * @return DataObjectList
     */
    public static function sample_beimai($url="https://www.awwwards.com/websites/",$root_url="https://www.awwwards.com/") {
        ini_set('max_execution_time', '999');
        ini_set('user_agent',self::$user_agent);
        // Create DOM from URL or file
        $html = file_get_html($url);
        $items=new DataObjectList();
        // Find all images
            // print_r($html);die();
        foreach($html->find('.js-collectable') as $element) {

            $links=$element->find('a');
            $item=new CarItem();
            // print_r($links[0]);die();

            $item->setImgUrl($links[0]->find('img',0)->src);
            $item->setName($links[0]->alt);
            $item->setDetailUrl($links[1]->href);
            // $item->setDetailUrl($root_url.$links[1]->href);
            $items->add($item);
        }
        return $items;
        //print_r($items);
    }
}
?>
