<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private $mailer;

    public function __construct()
    {
        $conf = require_once __DIR__.'/../../conf.php';

        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->recipientPassword = $conf['recipient_host'];
        $this->mailer->SMTPAuth = true;
        $this->recipientEmail = $conf['recipient_email'];
        $this->recipientPassword = $conf['recipient_password'];
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = 587;
        $this->mailer->setFrom('from@example.com', 'Votre Nom');
    }

    public function sendEmail($to, $subject, $body)
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
    