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

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function check(string $modelClassB64, string $secretCode)
    {
        $modelClass = base64_decode($modelClassB64);

        $object = $this->entityManager->getRepository($modelClass)->findOneByDoubleOptInSecretCode($secretCode);
        if($object)
        {
            /** @var \Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface $object */
            $object->setIsDoubleOptInVerified(true);
            $object->setDoubleOptInVerifiedAt(new \DateTime());
            $object->setDoubleOptInSecretCode(null);
            $this->entityManager->flush();
            $result = 'success';

            $event = new DoubleOptInVerifiedEvent($object);
            $this->eventDispatcher->dispatch($event, 'kikwik.double_opt_in.verified');
        }
        else
        {
            $result = 'danger';
        }

        return $this->render('@KikwikDoubleOptIn/check.html.twig', [
            'result' => $result
        ]);
    }
}