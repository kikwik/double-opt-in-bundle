<?php

namespace Kikwik\DoubleOptInBundle\EventListener;


use Doctrine\Persistence\Event\LifecycleEventArgs;
use Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface;

class DoubleOptInSecretCodeGenerator
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof DoubleOptInInterface) {
            return;
        }

        $entity->setDoubleOptInSecretCode(uniqid(md5($entity->getEmail())));
    }
}