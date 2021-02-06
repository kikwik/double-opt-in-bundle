<?php


namespace Kikwik\DoubleOptInBundle\Model;


trait DoubleOptInTrait
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDoubleOptInVerified = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $doubleOptInVerifiedAt;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    protected $doubleOptInSecretCode;

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

    public function setDoubleOptInSecretCode(?string $doubleOptInSecretCode)
    {
        $this->doubleOptInSecretCode = $doubleOptInSecretCode;
    }

    public function getDoubleOptInSecretCode(): ?string
    {
        return $this->doubleOptInSecretCode;
    }
}