<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * QotdRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QotdRepository extends EntityRepository
{
    public function getQotd()
    {
        $em = $this->getEntityManager();
        $q1 = $em->createQuery('select count(q.id) qc from AppBundle:Qotd q');
        $res1 = $q1->getSingleResult();

        $r = rand(0, $res1['qc'] - 1);

        $repo = $this->getEntityManager()->getRepository('AppBundle:Qotd');
        $q2 = $repo->createQueryBuilder('q')
                ->setFirstResult($r)
                ->setMaxResults(1)
                ->getQuery();

        $res = $q2->getSingleResult();

        return $res;
    }
}