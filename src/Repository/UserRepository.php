<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findBestPlayer(): ?User
    {
        $result = $this->createQueryBuilder('u')
            ->select('PARTIAL u.{id}', 'COUNT(g.id) AS wins')
            ->leftJoin('u.games', 'g')
            ->andWhere('g.won = :isWon')
            ->setParameter('isWon', 1)
            ->groupBy('u.id', 'u.username')
            ->orderBy('wins', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()[0];

        return $result;
    }

    public function findMorePlayedPlayer(): ?User
    {
        $result = $this->createQueryBuilder('u')
            ->select('PARTIAL u.{id}', 'COUNT(g.id) AS games_played')
            ->leftJoin('u.games', 'g')
            ->groupBy('u.id', 'u.username')
            ->orderBy('games_played', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()[0];

        return $result;
    }
}
