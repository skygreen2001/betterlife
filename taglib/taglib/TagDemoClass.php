<?php
/**
 * 自定义标签:用于测试
 *
 * @author skygreen <skygreen2001@gmail.com>
 */
class TagDemoClass extends TagClass{

    function setHtml()
    {
        $this->html = "<a href='http://www.baidu.com'>I like search In baidu</a><br/>";
        $attributes = TagClass::getAttributesFormTag( $this->getAttributeDesc() );
        $this->html.="I'm very Fine<br/>" . $this->getContent() . "<br/>";
        foreach ($attributes as $key => $value) {
            $this->html .= $key;
            $this->html .= "-";
            $this->html .= $value . "<br/>";
        }
    }
}
?>
