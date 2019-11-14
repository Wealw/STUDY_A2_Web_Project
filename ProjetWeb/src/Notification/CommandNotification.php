<?php


namespace App\Notification;

use App\Entity;
use Swift_Mailer;
use Twig\Environment;

class CommandNotification
{

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notify($text)
    {
        $message = (new \Swift_Message(''))
            ->setFrom('projetweeb@gmail.com')
            ->setTo('hugodegrossi@gmail.com')
            ->setBody($text);
        $this->mailer->send($message);
    }


}