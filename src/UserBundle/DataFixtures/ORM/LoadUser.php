<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class LoadUser implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

    	$us = array(
    			array('user', 'user@email.com', 'ROLE_USER', 'en', 'ESP'),
    			array('admin', 'admin@email.com', 'ROLE_ADMIN', 'fr', 'FRA'),
    			array('quentin', 'quentin@email.com', 'ROLE_USER', 'en', 'ESP'),
    			array('hugo', 'hugo@email.com', 'ROLE_USER', 'fr', 'FRA'),
    			array('olivia', 'olivia@email.com', 'ROLE_USER', 'en', 'ESP'),
    			array('martine', 'martine@email.com', 'ROLE_USER', 'en', 'ESP'),
    		);

    	$encoder = $this->container->get('security.password_encoder');

    	foreach($us as $u){
    		$user = new User();
    		$user->setUsername($u[0]);
    		$user->setEmail($u[1]);
    		$user->setEnabled(1);
    		$user->setLocale($u[3]);
    		$user->setCountry($u[4]);
    		$user->setRoles(array($u[2]));
    		$user->setSalt(md5(uniqid()));

    		$password = $encoder->encodePassword($user, $u[0]);
        	$user->setPassword($password);
        	
        	$manager->persist($user);

    	}

        $manager->flush();

    }
}