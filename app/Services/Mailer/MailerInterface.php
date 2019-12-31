<?php

namespace Fully\Services;

/**
 * Class MailInterface.
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
interface MailerInterface
{
    public function send($view, $email, $subject, $data = array());
    public function queue($view, $email, $subject, $data = array());
}
