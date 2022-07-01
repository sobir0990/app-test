<?php


namespace common\components;


use yii\base\BaseObject;

class Mail extends BaseObject
{

    // private static $url = "host1734609.hostland.pro"; //"http://industry-hunter.zzz.com.ua";
    public function sendOne($email, $subject, $message){

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "To: $email\r\n";
        $headers .= "From: Industry Hunter <sobirmailru095@gmail.com>";


        return $this->MailSmtp ($email, $subject, $message, $headers, true);
    }

    public function sendMore($emails, $subject, $message){
        foreach($emails as $email){
            $this->sendOne($email, $subject, $message);
            sleep(10);
        }
    }


    public function MailSmtp($reciever, $subject, $content, $headers, $debug = 0) {

        $smtp_server = 'ssl://smtp.gmail.com'; // адрес SMTP-сервера
        $smtp_port = 465; // порт SMTP-сервера
        $smtp_user = 'sobirmailru095@gmail.com'; // Имя пользователя для авторизации на SMTP-сервере
        $smtp_password = 'tqcfuqVRdr9cN29r1'; // Пароль для авторизации на SMTP-сервере
        $mail_from = $smtp_user; // Ящик, с которого отправляется письмо

        $sock = fsockopen($smtp_server,$smtp_port,$errno,$errstr,30);

        $str = fgets($sock,512);
        if (!$sock) {
            printf("Socket is not created\n");
            exit(1);
        }



        $this->smtp_msg($sock, "HELO " . $_SERVER['SERVER_NAME']);
        $this->smtp_msg($sock, "AUTH LOGIN");
        $this->smtp_msg($sock, base64_encode($smtp_user));
        $this->smtp_msg($sock, base64_encode($smtp_password));
        $this->smtp_msg($sock, "MAIL FROM: <" . $mail_from . ">");
        $this->smtp_msg($sock, "RCPT TO: <" . $reciever . ">");
        $this->smtp_msg($sock, "DATA");

        $headers = "Subject: " . $subject . "\r\n" . $headers;

        $data = $headers . "\r\n\r\n" . $content . "\r\n.";

        $this->smtp_msg($sock, $data);
        $this->smtp_msg($sock, "QUIT");

        fclose($sock);

    }


    private function smtp_msg($sock, $msg) {

        if (!$sock) {
            printf("Broken socket!\n");
            exit(1);
        }

        if (isset($_SERVER['debug']) && $_SERVER['debug']) {
            printf("Send from us: %s<BR>", nl2br(htmlspecialchars($msg)));
        }
        fputs($sock, "$msg\r\n");
        $str = fgets($sock, 512);
        if (!$sock) {
            printf("Socket is down\n");
            exit(1);
        }
        else {
            if (isset($_SERVER['debug']) && $_SERVER['debug']) {
                printf("Got from server: %s<BR>", nl2br(htmlspecialchars($str)));
            }

            $e = explode(" ", $str);
            $code = array_shift($e);
            $str = implode(" ", $e);

            if ($code > 499) {
                printf("Problems with SMTP conversation.<BR><BR>Code %d.<BR>Message %s<BR>", $code, $str);
                exit(1);
            }
        }
    }


}
