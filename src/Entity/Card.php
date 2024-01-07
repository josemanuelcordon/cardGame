<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 255)]
    private ?string $suit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getSuit(): ?string
    {
        return $this->suit;
    }

    public function setSuit(string $suit): static
    {
        $this->suit = $suit;

        return $this;
    }

    public function equals(Card $card)
    {
        if (!$card instanceof self) {
            return false;
        }

        return $this->getNumber() === $card->getNumber() && $this->getSuit() === $card->getSuit();
    }

    public function isBetter(Card $card)
    {
        $cardNumbersOrdered = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 1];

        $thisCardWeight = array_search($this->number, $cardNumbersOrdered);
        $cardWeight = array_search($card->getNumber(), $cardNumbersOrdered);

        return $thisCardWeight >= $cardWeight;
    }
}
