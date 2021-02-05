<?php

namespace Kikwik\DoubleOptInBundle\Model;

interface DoubleOptInInterface
{
    public function getEmail(): ?string;

    public function setDoubleOptInVerifiedAt(\DateTime $doubleOptInVerifiedAt);
    public function getDoubleOptInVerifiedAt(): ?\DateTime;

    public function setDoubleOptInSecretCode(?string $doubleOptInSecretCode);
    public function getDoubleOptInSecretCode(): ?string;

}