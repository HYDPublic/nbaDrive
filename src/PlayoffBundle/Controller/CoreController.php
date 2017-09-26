<?php

namespace PlayoffBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

use PlayoffBundle\Entity\Game;
use PlayoffBundle\Entity\Statsheet;



class CoreController extends Controller
{

	/**
	* @Route("/admin" , name="admin_dashboard")
	*/
	public function admIndexAction()
	{
		$em = $this->getDoctrine()->getManager();

		// liste games that has no winners yet and that i've been played.
		$games = $em->getRepository('PlayoffBundle:Game')->getGamesPlayedButNoWinnerYet();

		$missingGames = $em->getRepository('PlayoffBundle:Game')->getGamesMissingByRounds();

		return $this->render('PlayoffBundle:Admin:dashboard.html.twig', array(
				'games'			=> $games,
				'missingGames' 	=> $missingGames,
            ));
	}

}
