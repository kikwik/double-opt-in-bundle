<?php

namespace Kikwik\DoubleOptInBundle\Service;

use Doctrine\Persistence\ManagerRegistry;
use Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Contracts\Translation\TranslatorInterface;

class DoubleOptInMailManager
{
    /**
     * @var string
     */
    private $senderEmail;
    /**
     * @var \Symfony\Component\Mailer\MailerInterface
     */
    private $mailer;
    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;
    /**
     * @var \Symfony\Component\Routing\Router
     */
    private $router;
    /**
     * @var string
     */
    private $senderName;
    /**
     * @var ManagerRegistry
     */
    private $doctrine;


    public function __construct(string $senderEmail, string $senderName, MailerInterface $mailer, TranslatorInterface $translator, Router $router, ManagerRegistry $doctrine)
    {
        $this->senderEmail = $senderEmail;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->router = $router;
        $this->senderName = $senderName;
        $this->doctrine = $doctrine;
    }

    public function sendEmail(DoubleOptInInterface $entity)
    {
        // send email
        $fromAddress = New Address($this->senderEmail, $this->senderName);
        $email = new TemplatedEmail();
        $email
            ->from($fromAddress)
            ->to($entity->getEmail())
            ->subject($this->translator->trans('double_opt_in.email.subject',[],'KikwikDoubleOptInBundle'))
            ->htmlTemplate('@KikwikDoubleOptIn/email/sendSecretCode.html.twig')
            ->context([
                'confirm_url' => $this->router->generate('kikwik_double_opt_in_check',['modelClassB64'=>base64_encode(get_class($entity)),'secretCode'=>$entity->getDoubleOptInSecretCode()],UrlGeneratorInterface::ABSOLUTE_URL),
                'object' => $entity,
            ])
        ;
        $this->mailer->send($email);

        $entity->setDoubleOptInSendedAt(new \DateTime());
        $this->doctrine->getManager()->persist($entity);
        $this->doctrine->getManager()->flush();
    }
}