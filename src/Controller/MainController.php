<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\CardRepository;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(GameRepository $gameRepository): Response
    {
        $user = $this->getUser();
        if ($user) {
            $actual_game = $gameRepository->findActualGameOfPlayer($user->getId());
        } else {
            $actual_game = NULL;
        }

        return $this->render('main/index.html.twig', [
            'actual_game' => $actual_game,
        ]);
    }
}
