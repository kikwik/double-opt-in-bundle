<?php

namespace Kikwik\DoubleOptInBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Kikwik\DoubleOptInBundle\Event\DoubleOptInVerifiedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoubleOptInController extends AbstractController
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var bool
     */
    private $removeSecretCodeAfterVerification;

    public function __construct(bool $removeSecretCodeAfterVerification, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->removeSecretCodeAfterVerification = $removeSecretCodeAfterVerification;
    }

    public function check(string $modelClassB64, string $secretCode)
    {
        $modelClass = base64_decode($modelClassB64);

        $object = $this->entityManager->getRepository($modelClass)->findOneByDoubleOptInSecretCode($secretCode);
        if($object)
        {
            /** @var \Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface $object */
            $object->setIsDoubleOptInVerified(true);
            if(!$object->getDoubleOptInVerifiedAt())
            {
                $object->setDoubleOptInVerifiedAt(new \DateTime());
            }
            if($this->removeSecretCodeAfterVerification)
            {
                $object->setDoubleOptInSecretCode(null);
            }
            $this->entityManager->flush();
            $result = 'success';

            $event = new DoubleOptInVerifiedEvent($object);
            $this->eventDispatcher->dispatch($event, 'kikwik.double_opt_in.verified');
            if($event->getResponse())
            {
                return $event->getResponse();
            }
        }
        else
        {
            $result = 'danger';
        }

        return $this->render('@KikwikDoubleOptIn/check.html.twig', [
            'result' => $result,
            'object' => $object,
        ]);
    }
}
