<?php

/**
 * -----------| 发邮件的类 |-----------
 *
 * 推荐使用UtilEmailer.php
 *
 * 本类已不再维护
 *
 * 本类里PHP方法: mb_ereg 已过时，需找到替换方法才可正常使用
 *
 * 调用UtilEmailSmtp()发邮件
 *
 * ```
 *     $fromaddress = "xxx@xxx.com";
 *     $toaddress   = "xxx@xxx.com";
 *     $mailsubject = "测试邮件";
 *     $mailbody    = "<a href='http://www.sohu.com'>怎么办我想想</a>";
 *     $Email       = new UtilEmailSmtp();
 *     if ($Email->sendmail( $fromaddress, $toaddress, $mailsubject, $mailbody )) {
 *         $mailinfo = "邮件已发送";
 *     } else {
 *         $mailinfo = "发送不成功";
 *     }
 * ```
 * @category betterlife
 * @package util.email
 * @author skygreen
 */
class UtilEmailSmtp extends Util
{
   //private static  $auth=false;

    /* Private static Variables */
    private static $smtp_port  = 25;
    private static $time_out   = 30;
    private static $host_name;
    private static $relay_host = "smtp.xxx.com";
    private static $debug      = false;
    private static $auth       = true;
    private static $user       = "xxx@xxx.com";
    private static $pass       = "XXXXXX";
    //是否邮件是utf-8字符集，如果不是，就是gbk字符集
    private static $is_utf8    = false;
    /* Public Variables */
    public $sock;

    /* Constractor */
    function __construct() {
        $this->sock = false;
    }

    /**
     * 发送邮件
     * @param string $fromaddress 发件人地址
     * @param string $toaddress 收件人地址
     * @param string $cc 抄送
     * @param string $bcc 暗抄送
     * @param string $subject 邮件标题
     * @param string $content 邮件内容
     * @param string $isHtml_mailtype 邮件内容是否html格式，如果不是就是纯文本
     * @param string $additional_headers 附加的邮件头信息
     */
    public function sendmail($fromaddress, $toaddress, $subject = "", $content = "", $isHtml_mailtype = true, $cc = "", $bcc = "", $additional_headers = "")
    {
        $mail_from = $this->get_address( $this->strip_comment( $fromaddress));
        $content   = preg_replace('/(^|(\r\n))(\\.)/', '\\1.\\3', $content);
        if (!self::$is_utf8) {
            if (UtilString::is_utf8( $content)) $content = UtilString::utf82gbk( $content );
        }
        $header = "MIME-Version:1.0" . HH;
        if ($isHtml_mailtype == "HTML") {
            $charset = "gbk";
            if (self::$is_utf8 ) $charset="utf-8";
            $header .= "Content-Type:text/html charset=\"$charset\"" . HH;// charset=\"utf-8\"
        }
        $header .= "To: " . $toaddress . HH;
        if ($cc != "") {
            $header .= "Cc: " . $cc . HH;
        }
        $header .= "Disposition-Notification-To: " . $fromaddress . HH;
        $header .= "From: $fromaddress<" . $fromaddress . ">" . HH;
        if (!self::$is_utf8) {
            if (UtilString::is_utf8( $subject)) $subject_send = UtilString::utf82gbk( $subject );
        }
        $header_echo  = $header;
        $header      .= "Subject: " . $subject_send . HH;
        $header_echo .= "Subject: " . $subject . HH;
        $later        = $additional_headers;
        $later       .= "Date: ".date("r") . HH;
        $later       .= "X-Mailer:By Redhat (PHP/".phpversion().")" . HH;
        list($msec, $sec) = explode(" ", microtime());
        $later       .= "Message-ID: <" . date("YmdHis", $sec) . "." . ( $msec * 1000000 ) . "." . $mail_from . ">" . HH;
        $header      .= $later;
        $header_echo .= $later;
        $TO           = explode(",", $this->strip_comment( $toaddress ));

        if ($cc != "") {
            $TO = array_merge($TO, explode(",", $this->strip_comment( $cc )));
        }

        if ($bcc != "") {
            $TO = array_merge($TO, explode(",", $this->strip_comment( $bcc )));
        }

        $sent = TRUE;
        foreach ($TO as $rcpt_to) {
            $rcpt_to = $this->get_address( $rcpt_to );
            if (!$this->smtp_sockopen( $rcpt_to )) {
                $this->log_write( "Error: Cannot send email to " . $rcpt_to . "" );
                $sent = FALSE;
                continue;
            }
            self::$host_name = UtilNet::hostname();
            if ($this->smtp_send( self::$host_name, $mail_from, $rcpt_to, $header, $content )) {
                $this->log_write( "E-mail has been sent to <" . $rcpt_to . ">" );
            } else {
                $this->log_write( "Error: Cannot send email to <" . $rcpt_to . ">");
                $sent = FALSE;
            }
            fclose($this->sock);
            $this->log_write( "Disconnected from remote host" );
        }
        echo "<br>";
        echo $header_echo;
        return $sent;
    }

    /* Private Functions */

    public function smtp_send($helo, $from, $to, $header, $body = "") {
        if (!$this->smtp_putcmd( "HELO", $helo )) {
            return $this->smtp_error( "sending HELO command" );
        }

        if (self::$auth) {
            if (!$this->smtp_putcmd( "AUTH LOGIN", base64_encode(self::$user) )) {
                return $this->smtp_error( "sending HELO command" );
            }

            if (!$this->smtp_putcmd( "", base64_encode(self::$pass) )) {
                return $this->smtp_error( "sending HELO command" );
            }
        }

        if (!$this->smtp_putcmd("MAIL", "FROM:<" . $from.">")) {
            return $this->smtp_error("sending MAIL FROM command");
        }

        if (!$this->smtp_putcmd("RCPT", "TO:<" . $to . ">")) {
            return $this->smtp_error("sending RCPT TO command");
        }

        if (!$this->smtp_putcmd("DATA")) {
            return $this->smtp_error("sending DATA command");
        }

        if (!$this->smtp_message($header, $body)) {
            return $this->smtp_error("sending message");
        }

        if (!$this->smtp_eom()) {
            return $this->smtp_error("sending <CR><LF>.<CR><LF> [EOM]");
        }

        if (!$this->smtp_putcmd("QUIT")) {
            return $this->smtp_error("sending QUIT command");
        }

        return TRUE;
    }

    public function smtp_sockopen($address) {
        if (empty(self::$relay_host)) {
            return $this->smtp_sockopen_mx($address);
        } else {
            return $this->smtp_sockopen_relay();
        }
    }

    public function smtp_sockopen_relay() {
        $this->log_write("Trying to " . self::$relay_host . ":" . self::$smtp_port);
        $this->sock = @fsockopen(self::$relay_host, self::$smtp_port, $errno, $errstr, self::$time_out);
        if (!( $this->sock && $this->smtp_ok() )) {
            $this->log_write("Error: Cannot connenct to relay host " . self::$relay_host);
            $this->log_write("Error: " . $errstr . " (" . $errno . ")");
            return FALSE;
        }
        $this->log_write("Connected to relay host " . self::$relay_host);
        return TRUE;
        ;
    }

    public function smtp_sockopen_mx($address) {
        $domain = preg_replace('/^.+@([^@]+)$/', '\\1', $address);
        if (!@getmxrr($domain, $MXHOSTS)) {
            $this->log_write("Error: Cannot resolve MX \"" . $domain);
            return FALSE;
        }
        foreach ($MXHOSTS as $host) {
            $this->log_write("Trying to " . $host . ":" . self::$smtp_port);
            $this->sock = @fsockopen($host, self::$smtp_port, $errno, $errstr, self::$time_out);
            if (!($this->sock && $this->smtp_ok())) {
                $this->log_write("Warning: Cannot connect to mx host " . $host);
                $this->log_write("Error: " . $errstr . " (" . $errno . ")");
                continue;
            }
            $this->log_write("Connected to mx host " . $host);
            return TRUE;
        }
        $this->log_write("Error: Cannot connect to any mx hosts (" . implode(", ", $MXHOSTS) . ")");
        return FALSE;
    }

    public function smtp_message($header, $body) {
        fputs($this->sock, $header . HH . $body);
        $this->smtp_debug("> " . str_replace(HH, "\n" . "> ", $header . "\n> " . $body . "\n> "));

        return TRUE;
    }

    public function smtp_eom() {
        fputs($this->sock, HH . "." . HH);
        $this->smtp_debug(". [EOM]\n");

        return $this->smtp_ok();
    }

    public function smtp_ok() {
        $response = str_replace(HH, "", fgets($this->sock, 512));
        $this->smtp_debug($response . "\n");

        if (!mb_ereg("^[23]", $response)) {
            fputs($this->sock, "QUIT" . HH);
            fgets($this->sock, 512);
            $this->log_write("Error: Remote host returned \"" . $response);
            return FALSE;
        }
        return TRUE;
    }

    public function smtp_putcmd($cmd, $arg = "") {
        if (!empty($arg)) {
            if (empty($cmd) ) $cmd = $arg;
            else $cmd = $cmd . " " . $arg;
        }

        fputs($this->sock, $cmd . HH);
        $this->smtp_debug("> " . $cmd . "\n");

        return $this->smtp_ok();
    }

    public function smtp_error($string) {
        $this->log_write("Error: Error occurred while " . $string);
        return FALSE;
    }

    public function log_write($message) {
        $this->smtp_debug($message);
        LogMe::log( $message, EnumLogLevel::ALERT );
    }

    public function strip_comment($address) {
        $comment = "\\([^()]*\\)";
        while (mb_ereg($comment, $address)) {
            $address = preg_replace($comment, '', $address);
        }

        return $address;
    }

    public function get_address($address) {
        $address = preg_replace('/([ \t\r\n])+/', '', $address);
        $address = preg_replace('/^.*<(.+)>.*$/', '\\1', $address);

        return $address;
    }

    public function smtp_debug($message) {
        if ($this->debug) {
            echo $message . "<br>";
        }
    }

    public function get_attach_type($image_tag) {

        $filedata = array();

        $img_file_con = fopen($image_tag, "r");
        unset($image_data);
        $image_data   = "";
        while ($tem_buffer = AddSlashes(fread($img_file_con, filesize($image_tag))))
            $image_data .= $tem_buffer;
        fclose($img_file_con);

        $filedata['context']  = $image_data;
        $filedata['filename'] = basename($image_tag);
        $extension            = substr($image_tag, strrpos($image_tag, "."), strlen($image_tag) - strrpos($image_tag, "."));
        switch ($extension) {
            case ".gif":
                $filedata['type'] = "image/gif";
                break;
            case ".gz":
                $filedata['type'] = "application/x-gzip";
                break;
            case ".htm":
                $filedata['type'] = "text/html";
                break;
            case ".html":
                $filedata['type'] = "text/html";
                break;
            case ".jpg":
                $filedata['type'] = "image/jpeg";
                break;
            case ".tar":
                $filedata['type'] = "application/x-tar";
                break;
            case ".txt":
                $filedata['type'] = "text/plain";
                break;
            case ".zip":
                $filedata['type'] = "application/zip";
                break;
            default:
                $filedata['type'] = "application/octet-stream";
                break;
        }
        return $filedata;
    }

}
