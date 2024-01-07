<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Card::class)]
    #[ORM\JoinTable(name: "game_machine_cards")]
    private Collection $machineCards;

    #[ORM\ManyToMany(targetEntity: Card::class)]
    #[ORM\JoinTable(name: "game_player_cards")]
    private Collection $playerCards;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Card $machinePick = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Card $playerPick = null;

    #[ORM\Column(length: 255)]
    private ?string $difficulty = null;

    #[ORM\Column("finished")]
    private ?bool $isFinished = false;

    #[ORM\Column("won")]
    private ?bool $won = false;
    public function __construct()
    {
        $this->machineCards = new ArrayCollection();
        $this->playerCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getMachineCards(): Collection
    {
        return $this->machineCards;
    }

    public function addMachineCard(Card $machineCard): static
    {
        if (!$this->machineCards->contains($machineCard)) {
            $this->machineCards->add($machineCard);
        }

        return $this;
    }

    public function removeMachineCard(Card $machineCard): static
    {
        $this->machineCards->removeElement($machineCard);

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getPlayerCards(): Collection
    {
        return $this->playerCards;
    }

    public function addPlayerCard(Card $playerCard): static
    {
        if (!$this->playerCards->contains($playerCard)) {
            $this->playerCards->add($playerCard);
        }

        return $this;
    }

    public function removePlayerCard(Card $playerCard): static
    {
        $this->playerCards->removeElement($playerCard);

        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getMachinePick(): ?Card
    {
        return $this->machinePick;
    }

    public function setMachinePick(?Card $machinePick): static
    {
        $this->machinePick = $machinePick;

        return $this;
    }

    public function getPlayerPick(): ?Card
    {
        return $this->playerPick;
    }

    public function setPlayerPick(?Card $playerPick): static
    {
        $this->playerPick = $playerPick;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function isFinished(): ?bool
    {
        return $this->isFinished;
    }

    public function endGame(): static
    {
        $this->isFinished = true;

        return $this;
    }

    public function isWon()
    {
        return $this->won;
    }
    public function decideWinner(bool $won)
    {
        $this->won = $won;
    }
}
