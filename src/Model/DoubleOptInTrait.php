<?php


namespace Kikwik\DoubleOptInBundle\Model;


trait DoubleOptInTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $emailVerifiedAt;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    protected $doubleOptInSecretCode;

    public function setEmailVerifiedAt(\DateTime $emailVerifiedAt)
    {
        $this->emailVerifiedAt = $emailVerifiedAt;

        return $this;
    }

    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function setDoubleOptInSecretCode(?string $doubleOptInSecretCode)
    {
        $this->doubleOptInSecretCode = $doubleOptInSecretCode;
    }

    public function getDoubleOptInSecretCode(): ?string
    {
        return $this->doubleOptInSecretCode;
    }
}