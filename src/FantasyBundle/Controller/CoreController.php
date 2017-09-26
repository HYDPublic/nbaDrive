<?php

namespace FantasyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Datetime;

use FantasyBundle\Entity\Pick;

class CoreController extends Controller
{


	/**
	* @Route("/compare/{u}", name="fantasy_core_compare")
	*/
	public function compareAction($u)
	{

		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('UserBundle:User')->findOneByUsername($u);
        $userPicks = $em->getRepository('FantasyBundle:Pick')->getUserPicksByDate($user);
        $userPoints = $em->getRepository('PlayoffBundle:Statsheet')->getUserPicksPoints($userPicks);
        $userStat = $this->getStatPoints($userPoints);

        $me = $em->getRepository('UserBundle:User')->find($this->getUser());
        $mePicks = $em->getRepository('FantasyBundle:Pick')->getUserPicksByDate($me);
        $mePoints = $em->getRepository('PlayoffBundle:Statsheet')->getUserPicksPoints($mePicks);
        $meStat = $this->getStatPoints($mePoints);

        $dates = $em->getRepository('PlayoffBundle:Game')->getByDatesBeforeToday();

        return $this->render('FantasyBundle:Core:compare.html.twig', array(
				'user' 			=> $user,
				'userPicks'		=> $userPicks,
				'userPoints'	=> $userPoints,
				'userStat'		=> $userStat,
				'me'			=> $me,
				'mePicks'		=> $mePicks,
				'mePoints'		=> $mePoints,
				'meStat'		=> $meStat,
				'dates'			=> $dates
			));

	}

	private function getStatPoints($points)
	{
		$stat['total'] = $stat['points'] = $stat['rebonds'] = $stat['assists'] = 0;
		foreach($points as $mp){		
			if($mp!=null){
				$stat['total'] += $mp->getPoints();
				$s = $mp->getStats();
				$stat['points'] += $s['points'];
				$stat['rebonds'] += $s['rebonds'];
				$stat['assists'] += $s['assists'];	
			}
			
		}

		return $stat;
	}

	/**
	* @Route("/pick/{page}", name="fantasy_core_pick")
	*/
	public function pickAction($page=1)
	{

		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

        if ($page < 1) 
            throw new NotFoundHttpException('Page "'.$page.'" does not exist.');

		$em = $this->getDoctrine()->getManager();
		// dump($em->getRepository('PlayoffBundle:Statsheet')->find(20));
		// get user info
		$user = $em->getRepository('UserBundle:User')->find($this->getUser());

		// Get my picks
		$mypicks = $em->getRepository('FantasyBundle:Pick')->getUserPicksByDate($this->getUser());

		// Get Best player by date
		// $bestpicksIds = $em->getRepository('PlayoffBundle:Statsheet')->getBestPickId();
		// $bestpicks = $em->getRepository('PlayoffBundle:Statsheet')->findById($bestpicksIds);
		$bestpicks = $em->getRepository('PlayoffBundle:Statsheet')->getBestPicks();
		// $hola = $em->getRepository('PlayoffBundle:Statsheet')->zeBest();

		$positions = $em->getRepository('PlayoffBundle:Statsheet')->getClassementGlobal($this->getUser());
		// dump($em->getRepository('PlayoffBundle:Statsheet')->find(20));
		// dump($em->getRepository('PlayoffBundle:Statsheet')->find(19));
		// dump($bestpicks);
		// die;

		// Get points
		$mypoints = $em->getRepository('PlayoffBundle:Statsheet')->getUserPicksPoints($mypicks);
		$stat = $this->getStatPoints($mypoints);
		/*
		$stat['total'] = $stat['points'] = $stat['rebonds'] = $stat['assists'] = 0;
		foreach($mypoints as $mp){		
			if($mp!=null){
				$stat['total'] += $mp->getPoints();
				$s = $mp->getStats();
				$stat['points'] += $s['points'];
				$stat['rebonds'] += $s['rebonds'];
				$stat['assists'] += $s['assists'];	
			}
			
		}
		*/
		// Get date games
		$perpage = 10;
		$dates = $em->getRepository('PlayoffBundle:Game')->getByDates($page, $perpage);
		$nbPages = $dates['nbpages'];
		$dates = $dates['results'];
		if ($page > $nbPages) 
            throw $this->createNotFoundException("Page ".$page." does not exist.");
		
		return $this->render('FantasyBundle:Core:index.html.twig', array(
				'user' 		=> $user,
				'dates'		=> $dates,
				'mypicks' 	=> $mypicks,
				'stat'		=> $stat,
				'mypoints'	=> $mypoints,
				'bestpicks'	=> $bestpicks,
				'positions'	=> $positions,
				'page'		=> $page,
				'nbPages'	=> $nbPages,
			));
	}


	/**
	* @Route("/choose/{day}", name="fantasy_core_pickday")
	*/
	public function picdayAction($day)
	{

		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

		$date = new \Datetime($day);
		$em = $this->getDoctrine()->getManager();

		// get my pick for this day
		$mypicks = $em->getRepository('FantasyBundle:Pick')->getUserPicksOnlyIds($this->getUser(),$day);

		// get all games
		$games = $em->getRepository('PlayoffBundle:Game')->findByDay($date);
		if(!count($games))
			return $this->redirectToRoute('fantasy_core_pick');	

		// On compare a la date ET vu que les horaires de matchs sont en ET.
		$nowdate = new \Datetime("now", new \DateTimeZone('America/New_York'));

		foreach($games as $g){

			// La date est automatiquement dans la timezone du serveur. On corrige cela a l'assignant a la timezone ET.
			$gameETdate = new \Datetime($g->getDate()->format('Y/m/d H:i'), new \DateTimeZone('America/New_York'));
			if($gameETdate < $nowdate){
				return $this->redirectToRoute('fantasy_core_pick');
			}

			$t[] = $g->getTeamExt();
			$t[] = $g->getTeamDom();
		}

		// dump($t);
		// get all players for the team in question
		
		$players = $em->getRepository('PlayoffBundle:Player')->getPlayersByTeamWithoutPicks($t, $mypicks['past']);
		// dump($players);

		return $this->render('FantasyBundle:Core:pick.html.twig', array(
				'games'		=> $games,
				'players'	=> $players,
				'day'		=> $day,
				'mypicks'	=> $mypicks
			));
	}

	/**
	* @Route("/select/{day}/{id}", name="fantasy_core_selectpick")
	*/
	public function selectAction($day,$id)
	{
		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $player = $em->getRepository('PlayoffBundle:Player')->find($id);

        // Verification a faire ?
        $dateDay = new \Datetime($day);
        if( !$em->getRepository('PlayoffBundle:Game')->isPlayerInGame($dateDay,$player) ){
        	$this->addFlash('error', 'Le joueur sélectionné ne joue pas le '.$day.' ... ');
        	return $this->redirectToRoute('fantasy_core_pickday', array('day'=>$day));
        }
        if( $em->getRepository('FantasyBundle:Pick')->isPlayerAlreadyPick($player, $this->getUser()) ){
        	$this->addFlash('error', 'Vous avez déjà sélectionné ce joueur pour une autre journée. Pas de doublon !!! Putain de tricheur ...');
        	return $this->redirectToRoute('fantasy_core_pickday', array('day'=>$day));
        }

        $pick = $em->getRepository('FantasyBundle:Pick')->findOneBy(array('user'=>$this->getUser(), 'date'=>$dateDay));
        if(!$pick){
        	$pick = new Pick();
			$pick->setDate($dateDay);
			$pick->setUser($this->getUser());
        }
        $pick->setPlayer($player);

		$em->persist($pick);
		$em->flush();

		return $this->redirectToRoute('fantasy_core_pick');        
	}

}
