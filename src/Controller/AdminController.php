<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/adminView', name: 'admin_view')]
    public function adminPanel(
        UserRepository $userRepository,
        GameRepository $gameRepository
    ): Response {
        $allUsers = $userRepository->findAll();
        foreach ($allUsers as $user) {
            $gamesWon = $gameRepository->findGamesWon($user->getId());
            $gamesLost = $gameRepository->findGamesLost($user->getId());

            $user->setWonGame($gamesWon);
            $user->setLostGame($gamesLost);
        }
        $bestUser = $userRepository->findBestPlayer();
        $morePlayed = $userRepository->findMorePlayedPlayer();

        return $this->render('adminPanel/index.html.twig', [
            'users' => $allUsers,
            'best_user' => $bestUser,
            'more_played' => $morePlayed
        ]);
    }
}
