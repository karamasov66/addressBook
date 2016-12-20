<?php

namespace XM\UserBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUserWithContacts($limit)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.contacts', 'contacts')
            ->addSelect('contacts')
        ;
        $qb->setMaxResults($limit);


        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}
