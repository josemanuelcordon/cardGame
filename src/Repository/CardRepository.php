<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 *
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    //    /**
    //     * @return Card[] Returns an array of Card objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Card
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function pickRandomCard(array $cards): ?Card
    {
        if (empty($cards)) {
            return null;
        }

        $randomIndex = array_rand($cards);
        return $cards[$randomIndex];
        /* return $this->createQueryBuilder('c')
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy('rand')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult(); */
    }

    public function pickBetterCard(array $cards): ?Card
    {
        if (empty($cards)) {
            return null;
        }

        $betterCard = $cards[0];
        foreach ($cards as $card) {
            if ($card->isBetter($betterCard)) {
                $betterCard = $card;
            }
        }
        return $betterCard;
    }

    public function playerWonGame($game): bool
    {
        $playerCard = $game->getPlayerPick();
        $machineCard = $game->getMachinePick();

        return $playerCard->isBetter($machineCard);
    }
}
