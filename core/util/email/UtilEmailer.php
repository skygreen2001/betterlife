<?php

/**
 * -----------| 邮件发送 |-----------
 *
 * 示例:
 *
 *     UtilEmailer::sendEmail("xxx@xxx.com", "xxx", "xxx@xxx.com", "master", "xxx", "联系人: XXX<br/>联系电话: XXXXXXX<br/>内容: XXXXX");
 *
 * 看见阿里云以下内容提示日期: 2021-03-31
 *
 *    如果配置邮箱为阿里云企业云邮箱，而发信服务器为ECS服务器，端口需修改成80: https://help.aliyun.com/document_detail/29449.html?spm=a2c4g.11186623.6.639.6a702be2cwLb6v
 *
 *    注意: ECS 基于安全考虑，目前已禁用 25 端口, 需修改配置: private static $port     = 80;
 *
 * 企业邮箱的POP3、SMTP、IMAP地址是什么？http://mailhelp.mxhichina.com/smartmail/detail.vm?knoId=5871700
 *
 * @category betterlie
 * @package util.email
 */
class UtilEmailer
{
    /**
     * 邮件服务器域名
     */
    private static $host     = "smtp.xxx.xxx.com";
    /**
     * 邮件服务器端口
     */
    private static $port     = 25;
    /**
     * 用户名
     */
    private static $username = "XXXX@bb.com";
    /**
     * 密码
     */
    private static $password = "XXXXX";
    /**
     * 发送邮件
     * @param string $fromaddress 发件人地址
     * @param string $fromname 发件人姓名
     * @param string $toaddress 收件人地址
     * @param string $toname 收件人姓名
     * @param string $subject 邮件标题
     * @param string $content 邮件内容
     */
    public static function sendEmail($fromaddress, $fromname, $toaddress, $toname, $subject, $content)
    {
        require_once("phpmailer/class.phpmailer.php");
        $mail           = new PHPMailer(); //建立邮件发送类
        $mail->Host     = self::$host; //您的企业邮局域名
        $mail->Port     = self::$port; //端口
        $mail->Username = self::$username; //邮局用户名(请填写完整的email地址)
        $mail->Password = self::$password; //邮局密码
        $mail->From     = $fromaddress; //邮件发送者email地址
        $mail->FromName = $fromname;
        $mail->AddAddress($toaddress, $toname); //接收邮件的email信箱("收件人email地址","收件人姓名")
        $mail->Subject  = $subject;//"=?utf-8?B?" . base64_encode($subject) . "?="; //邮件标题
        $mail->Body     = $content; //邮件内容
        $mail->IsHTML(true); //是否使用HTML格式
        $mail->CharSet  = "UTF-8"; //编码格式
        $mail->IsSMTP(); //使用SMTP方式发送
        $mail->SMTPAuth = true; //启用SMTP验证功能
        //$mail->AddAttachment($attach); //添加附件
        //$mail->AddReplyTo("", ""); //回复地址、名称
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息
        $result = array(
            "success" => $mail->Send(),
            "info"    => $mail->ErrorInfo
        );
        LogMe::log(print_pre($result));
        // print_pre($result, true);
        return $result;
    }
}
