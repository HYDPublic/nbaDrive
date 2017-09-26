<?php

namespace CoreBundle\Dblog;

use	Doctrine\ORM\EntityManagerInterface;
use CoreBundle\Entity\Log;

class Dblog
{

	/**
	* @var EntityManagerInterface
	*/
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function saveLog($data)
	{

		$date = new \Datetime();

		$log = new Log();

		$log->setUser($data['createdBy']);
		$log->setCreated($date);
		$log->setIp($_SERVER['REMOTE_ADDR']);
		$log->setType($data['type']);
		$log->setMessage($data['message']);

		$this->em->persist($log);
		$this->em->flush();
	}

}