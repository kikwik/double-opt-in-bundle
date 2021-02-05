<?php

namespace Kikwik\DoubleOptInBundle\Model;

interface DoubleOptInInterface
{
    public function getEmail(): ?string;

    public function setEmailVerifiedAt(\DateTime $emailVerifiedAt);
    public function getEmailVerifiedAt(): ?\DateTime;

    public function setDoubleOptInSecretCode(?string $doubleOptInSecretCode);
    public function getDoubleOptInSecretCode(): ?string;

}