<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\CardRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/card')]
class CardController extends AbstractController
{
    #[Route('/', name: 'app_card_index', methods: ['GET'])]
    public function index(CardRepository $cardRepository): Response
    {
        return $this->render('card/index.html.twig', [
            'cards' => $cardRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_card_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($card);
            $entityManager->flush();

            return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('card/new.html.twig', [
            'card' => $card,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_card_show', methods: ['GET'])]
    public function show(Card $card): Response
    {
        return $this->render('card/show.html.twig', [
            'card' => $card,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_card_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Card $card, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('card/edit.html.twig', [
            'card' => $card,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_card_delete', methods: ['POST'])]
    public function delete(Request $request, Card $card, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $card->getId(), $request->request->get('_token'))) {
            $entityManager->remove($card);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pick/{id_card}/game/{id_game}', name: 'pick_card', methods: ['GET'])]
    public function pickCard(
        $id_card,
        $id_game,
        EntityManagerInterface $entityManager,
        GameRepository $gameRepository,
        CardRepository $cardRepository
    ): Response {
        $user = $this->getUser();

        $card = $cardRepository->find($id_card);
        $actual_game = $gameRepository->find($id_game);

        $actual_game->setPlayerPick($card);
        $actual_game->endGame();
        
        $gameWon = $cardRepository->playerWonGame($actual_game);
        $actual_game->decideWinner($gameWon);

        $entityManager->flush();

        return $this->render('main/index.html.twig', [
            'actual_game' => $actual_game,
        ]);
    }
}
