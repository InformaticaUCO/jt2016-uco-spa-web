<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;


use Doctrine\Common\Collections\Criteria;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class FolderRepository extends EntityRepository implements FolderRepositoryInterface
{
    public function findOneActiveFolderBySlug($slug)
    {
        $qb = $this->getAllActiveFoldersQuery()
            ->andWhere('o.slug = :slug')
            ->setParameter('slug', $slug)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findAllPaginated(int $limit, int $page, array $sorting = [])
    {
        $queryBuilder = $this->getAllActiveFoldersQuery();

        return $this->createPager($queryBuilder, $limit, $page, $sorting);
    }
    
    public function getAllActiveFoldersQuery()
    {
        $qb = $this->createQueryBuilder('o');
        $query = $qb
            ->leftJoin('o.owner', 'owner')
            ->leftJoin('owner.organization', 'organization')
            ->where(
                $qb->expr()->orX(
                    'o.isPermanent = :true',
                    'o.expiresAt > :now'
                )
            )
            ->andWhere('organization.enabled = :true')
            ->setParameter('true', true)
            ->setParameter('now', new \DateTime())
        ;

        return $query;
    }
}
