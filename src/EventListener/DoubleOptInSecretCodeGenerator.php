<?php

namespace Kikwik\DoubleOptInBundle\EventListener;


use Doctrine\Persistence\Event\LifecycleEventArgs;
use Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Contracts\Translation\TranslatorInterface;

class DoubleOptInSecretCodeGenerator
{
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


    public function __construct(MailerInterface $mailer, TranslatorInterface $translator, Router $router) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof DoubleOptInInterface) {
            return;
        }

        $entity->setDoubleOptInSecretCode(uniqid(md5($entity->getEmail())));

        // send email
        $email = new TemplatedEmail();
        $email
            ->from('noreply@example.com')
            ->to($entity->getEmail())
            ->subject($this->translator->trans('double_opt_in.email.subject',[],'KikwikDoubleOptInBundle'))
            ->htmlTemplate('@KikwikDoubleOptIn/email/sendSecretCode.html.twig')
            ->context([
                'confirm_url' => $this->router->generate('kikwik_double_opt_in_check',['modelClassB64'=>base64_encode(get_class($entity)),'secretCode'=>$entity->getDoubleOptInSecretCode()],UrlGeneratorInterface::ABSOLUTE_URL),
            ])
        ;
        $this->mailer->send($email);
    }
}