<?php

namespace FantasyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * PickRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PickRepository extends \Doctrine\ORM\EntityRepository
{

	/*
	* Retourne la liste des players pick par un user
	*/
	public function getUserPicksByDate($user)
	{
		$query = $this->createQueryBuilder('p')
			->where('p.user = :user')
			->setParameter('user', $user)	
			->orderBy('p.date')
			->getQuery()
			->getResult()
		;
		$pick = array();
		foreach($query as $q){
			$d = $q->getDate();
			// Doit être du meme format que dans corebundle:core:index.html.twig
			$pick[$d->format('d-m-Y')] = $q;
		}

		return $pick;
	}

	/*
		
		SELECT S.* FROM STATSHEET S, GAME G
			WHERE S.game_id = G.id


	*/


	/*
	* Verifie si un player n'a pas déjà été pick par un user
	* return true/false
	*/
	public function isPlayerAlreadyPick($player, $user)
	{

		$query = $this->createQueryBuilder('p')
			->select('COUNT(p.id)')
			->where('p.user = :user')
			->andWhere('p.player = :player')
			->setParameters( array('user'=>$user, 'player'=>$player) )
			->getQuery()
			->getSingleScalarResult()
		;

		if($query)
			return true;
		return false;


	}


	public function getUserPicksOnlyIds($user, $day)
	{
		$query = $this->createqueryBuilder('p')
			->where('p.user = :user')
			->setParameter('user', $user)	
			->getQuery()
			->getResult()
		;
		$pick['past'] = array();

		foreach($query as $q){
			$d = $q->getDate();

			// Doit être du meme format que dans corebundle:core:index.html.twig
			if($day == $d->format('d-m-Y') )
				$pick['current'] = $q->getPlayer()->getId();
			else
				$pick['past'][] = $q->getPlayer()->getId();
			
		}

		return $pick;
	}

}
