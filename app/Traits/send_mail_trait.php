<?php

namespace App\Traits;
use Config;
use Illuminate\Support\Facades\Mail;

trait send_mail_trait
{

    public function sendEmail($mailText, $email, $subject, $fileNameToStore = null)
    {
        $fromName = "Ebtekar Store";
        $fromAddress = "order@ebtekarstore.net";
        $fromPass = "OJpmY?H~=d^+";
        $fromDriver = "smtp";
        $fromHost = "ebtekarstore.net";
        $fromPort = "587";
        $fromType = "tls";

        if ($fromDriver && $fromAddress) {
            Config::set('mail.username', $fromAddress);
            Config::set('mail.password', $fromPass);
            Config::set('mail.host', $fromHost);
            Config::set('mail.driver', $fromDriver);
            Config::set('mail.port', $fromPort);
            Config::set('mail.encryption', $fromType);
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName);

            if ($fileNameToStore != null) {
                $pdf = public_path('uploads/pdf/' . $fileNameToStore);
                Mail::send([], [], function ($message) use ($email, $subject, $mailText, $pdf) {
                    $message->to($email)->subject($subject)->setBody($mailText, 'text/html')->Attach(\Swift_Attachment::fromPath($pdf));
                });
            } else {
                Mail::send([], [], function ($message) use ($email, $subject, $mailText) {
                    $message->to($email)->subject($subject)->setBody($mailText, 'text/html');
                });
            }


            return true;
        } else {
            return false;
        }
    }

}