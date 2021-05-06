<?php

namespace Kikwik\DoubleOptInBundle\EventListener;


use Doctrine\Persistence\Event\LifecycleEventArgs;
use Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface;
use Kikwik\DoubleOptInBundle\Service\DoubleOptInMailManager;
use Symfony\Component\HttpFoundation\RequestStack;

class DoubleOptInSecretCodeGenerator
{
    /**
     * @var \Kikwik\DoubleOptInBundle\Service\DoubleOptInMailManager
     */
    private $mailManager;
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    public function __construct(DoubleOptInMailManager $mailManager, RequestStack $requestStack)
    {
        $this->mailManager = $mailManager;
        $this->requestStack = $requestStack;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if(is_null($this->requestStack->getCurrentRequest())) {
            return; // do not generate secret code if we are running from CLI
        }

        $entity = $args->getObject();
        if (!$entity instanceof DoubleOptInInterface) {
            return;
        }

        $entity->setDoubleOptInSecretCode(uniqid(md5($entity->getEmail())));
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        if(is_null($this->requestStack->getCurrentRequest())) {
            return; // do not send email if we are running from CLI
        }

        $entity = $args->getObject();
        if (!$entity instanceof DoubleOptInInterface) {
            return;
        }

        if($entity->doubleOptInShouldSendEmail())
        {
            $this->mailManager->sendEmail($entity);
        }
    }
}