<?php

namespace Kikwik\DoubleOptInBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DoubleOptInController extends AbstractController
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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