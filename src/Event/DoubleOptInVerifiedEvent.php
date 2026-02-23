<?php

namespace Kikwik\DoubleOptInBundle\Event;

use Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface;
use Symfony\Component\HttpFoundation\Response;

class DoubleOptInVerifiedEvent
{
    /**
     * @var \Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface
     */
    private $object;

    private $response;

    public function __construct(DoubleOptInInterface $object)
    {
        $this->object = $object;
    }

    /**
     * @return \Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface
     */
    public function getObject(): DoubleOptInInterface
    {
        return $this->object;
    }

    /**
     * @return null|Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param null|Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }
}