<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class GameController extends AbstractController
{

    #[Route('/create/{difficulty}', name: 'start_game')]
    public function createGame(bool $difficulty, GameRepository $gameRepository, CardRepository $cardRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $actual_game = new Game();
        $actual_game->setPlayer($user);
        $actual_game->setDifficulty("easy");

        if ($difficulty) {
            $actual_game->setDifficulty("difficult");
        }

        $cards = $cardRepository->findAll();
        $picksNumber = 3;

        while ($actual_game->getPlayerCards()->count() < $picksNumber) {
            $card = $cardRepository->pickRandomCard($cards);

            $cards = array_filter($cards, function ($cardElement) use ($card) {
                return !$cardElement->equals($card);
            });

            $actual_game->addPlayerCard($card);
        }

        while ($actual_game->getMachineCards()->count() < $picksNumber) {
            $card = $cardRepository->pickRandomCard($cards);

            $cards = array_filter($cards, function ($cardElement) use ($card) {
                return !$cardElement->equals($card);
            });

            $actual_game->addMachineCard($card);
        }

        $machineCardsOptions = $actual_game->getMachineCards()->toArray();
        if ($actual_game->getDifficulty() == "easy") {
            $machinePick = $cardRepository->pickRandomCard($machineCardsOptions);
        } else {
            $machinePick = $cardRepository->pickBetterCard($machineCardsOptions);
        }
        $actual_game->setMachinePick($machinePick);

        $entityManager->persist($actual_game);
        $entityManager->flush();

        return $this->render('main/index.html.twig', [
            'actual_game' => $actual_game,
        ]);
    }
}
