<?php

namespace Kikwik\DoubleOptInBundle\Model;

interface DoubleOptInInterface
{
    public function getEmail(): ?string;

    public function setIsDoubleOptInVerified(bool $doubleOptInVerified);
    public function getIsDoubleOptInVerified(): bool;

    public function setDoubleOptInVerifiedAt(\DateTime $doubleOptInVerifiedAt);
    public function getDoubleOptInVerifiedAt(): ?\DateTime;

    public function setDoubleOptInSecretCode(?string $doubleOptInSecretCode);
    public function getDoubleOptInSecretCode(): ?string;

    public function disableDoubleOptIn();
    public function enableDoubleOptIn();
    public function doubleOptInShouldSendEmail(): bool;
}