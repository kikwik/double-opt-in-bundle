<?php

namespace Kikwik\DoubleOptInBundle\Event;

use Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface;

class DoubleOptInVerifiedEvent
{
    /**
     * @var \Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface
     */
    private $object;

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


}