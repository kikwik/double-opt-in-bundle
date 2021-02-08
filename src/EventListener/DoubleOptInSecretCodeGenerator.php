<?php

namespace Kikwik\DoubleOptInBundle\EventListener;


use Doctrine\Persistence\Event\LifecycleEventArgs;
use Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface;
use Kikwik\DoubleOptInBundle\Service\DoubleOptInMailManager;

class DoubleOptInSecretCodeGenerator
{
    /**
     * @var \Kikwik\DoubleOptInBundle\Service\DoubleOptInMailManager
     */
    private $mailManager;

    public function __construct(DoubleOptInMailManager $mailManager) {

        $this->mailManager = $mailManager;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof DoubleOptInInterface) {
            return;
        }

        $entity->setDoubleOptInSecretCode(uniqid(md5($entity->getEmail())));

        $this->mailManager->sendEmail($entity);
    }
}