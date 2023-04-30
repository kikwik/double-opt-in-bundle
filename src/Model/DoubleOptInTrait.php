<?php


namespace Kikwik\DoubleOptInBundle\Model;

use Doctrine\ORM\Mapping as ORM;

trait DoubleOptInTrait
{
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    protected $doubleOptInSecretCode;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $doubleOptInSendedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDoubleOptInVerified = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $doubleOptInVerifiedAt;



    public function setDoubleOptInSecretCode(?string $doubleOptInSecretCode)
    {
        $this->doubleOptInSecretCode = $doubleOptInSecretCode;
    }

    public function getDoubleOptInSecretCode(): ?string
    {
        return $this->doubleOptInSecretCode;
    }

    public function getDoubleOptInSendedAt(): ?\DateTime
    {
        return $this->doubleOptInSendedAt;
    }

    public function setDoubleOptInSendedAt(\DateTime $doubleOptInSendedAt)
    {
        $this->doubleOptInSendedAt = $doubleOptInSendedAt;

        return $this;
    }

    public function setIsDoubleOptInVerified(bool $isDoubleOptInVerified)
    {
        $this->isDoubleOptInVerified = $isDoubleOptInVerified;

        return $this;
    }

    public function getIsDoubleOptInVerified(): bool
    {
        return $this->isDoubleOptInVerified;
    }

    public function setDoubleOptInVerifiedAt(\DateTime $doubleOptInVerifiedAt)
    {
        $this->doubleOptInVerifiedAt = $doubleOptInVerifiedAt;

        return $this;
    }

    public function getDoubleOptInVerifiedAt(): ?\DateTime
    {
        return $this->doubleOptInVerifiedAt;
    }

    /**
     * @var bool    enable or disable email send for this object (useful when importing data)
     */
    protected $doubleOptInShouldSendEmail = true;

    public function doubleOptInShouldSendEmail(): bool
    {
        return $this->doubleOptInShouldSendEmail;
    }

    public function enableDoubleOptIn()
    {
        $this->doubleOptInShouldSendEmail = true;
    }

    public function disableDoubleOptIn()
    {
        $this->doubleOptInShouldSendEmail = false;
    }
}