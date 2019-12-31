<?php

namespace App\Services;

use Mail;

/**
 * Class Mailer
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
class Mailer implements MailerInterface
{
    public function send($view, $email, $subject, $data = array())
    {
        Mail::send($view, $data, function ($message) use ($email, $subject) {

            $message->from('laven9696@gmail.com');
            $message->to($email)->subject($subject);
        });
    }

    public function queue($view, $email, $subject, $data = array())
    {
        Mail::queue($view, $data, function ($message) use ($email, $subject) {

            $message->from('laven9696@gmail.com');
            $message->to($email)->subject($subject);
        });
    }
}
