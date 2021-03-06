<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ContactNotification
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var Environment
     */
    private Environment $renderer;

    public function __construct(MailerInterface $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function notify(Contact $contact)
    {
        $email = (new Email())
            ->from('noreply@agence.fr')
            ->to('contact@agence.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($contact->getMail())
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Agence : ' . $contact->getProperty()->getTitle())
            ->text('Sending emails is fun again!')
            ->html($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact
            ]), 'text/html');

        $this->mailer->send($email);
    }
}