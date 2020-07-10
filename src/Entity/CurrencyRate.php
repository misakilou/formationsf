<?php

namespace App\Entity;

use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyRateRepository::class)
 */
class CurrencyRate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $validityDate;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=6)
     */
    private $rate;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $fromCurrency;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $toCurrency;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValidityDate(): ?\DateTimeInterface
    {
        return $this->validityDate;
    }

    public function setValidityDate(\DateTimeInterface $validityDate): self
    {
        $this->validityDate = $validityDate;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getFromCurrency(): ?string
    {
        return $this->fromCurrency;
    }

    public function setFromCurrency(string $fromCurrency): self
    {
        $this->fromCurrency = $fromCurrency;

        return $this;
    }

    public function getToCurrency(): ?string
    {
        return $this->toCurrency;
    }

    public function setToCurrency(string $toCurrency): self
    {
        $this->toCurrency = $toCurrency;

        return $this;
    }
}
