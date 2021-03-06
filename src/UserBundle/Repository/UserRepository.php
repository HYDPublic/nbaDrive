<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

	/* GET LIST OF ALL USERS WITH PAGINATION */
	public function getUsers($page, $nbPerPage)
	{
		$query = $this->createQueryBuilder('u')
			->orderBy('u.username', 'ASC')
			->getQuery()
		;

		$query
			->setFirstResult( ($page-1) * $nbPerPage )
			->setMaxResults($nbPerPage)
		;

		return new Paginator($query, true);
	}

}
