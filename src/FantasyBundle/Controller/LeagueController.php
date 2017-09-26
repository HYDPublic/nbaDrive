<?php

namespace FantasyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use FantasyBundle\Entity\Talk;
use FantasyBundle\Entity\League;
use FantasyBundle\Form\LeagueAddType;
use FantasyBundle\Form\LeagueJoinType;
use FantasyBundle\Form\TalkAddType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LeagueController extends Controller
{

    /**
     * @Route("/leagues/{page}", name="fantasy_leagues", requirements={"page":"\d+"})
     */
    public function indexAction(Request $request, $page=1)
    {
    	if ($page < 1) 
            throw new NotFoundHttpException('Page "'.$page.'" does not exist.');

        $perpage = $this->getParameter('nb_page_default');
    	$em = $this->getDoctrine()->getManager();

        $myleagues = ( $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) ? $em->getRepository('FantasyBundle:League')->getJoinedLeagues($this->getUser()->getId()) : null;
    	$leagues = $em->getRepository('FantasyBundle:League')->getLeagues($page,$perpage, $myleagues);
    	$nbPages = ceil( count($leagues) / $perpage );

        $form = $this->get('form.factory')->createNamedBuilder('formLeague')
            ->add('id',     TextType::class)
            ->add('password', PasswordType::class, array('required'=>false,))
            ->add('save',   SubmitType::class)
            ->getForm();

        if($request->isMethod('POST') && $request->request->has('formLeague') && $form->handleRequest($request) ){
            
            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $isValid = true;
                $data = $form->getData();
                $league = $em->getRepository('FantasyBundle:League')->find($data['id']);
                if( $league->getPrivate() && $league->getPassword() != $data['password'] )
                    $isValid = false;
                $idu = $this->getUser()->getId();
                foreach($league->getUsers() as $us){
                    if($us->getId() == $idu){
                        $isValid = false; break;
                    }
                }   
                $league->addUser($this->getUser());
                if($isValid) {
                    $em->persist($league);
                    $em->flush();
                    $this->addFlash('success', 'Vous avez rejoint la ligue '.$league->getName());
                    return $this->redirectToRoute('fantasy_league_detail', array('id'=>$league->getId(), 'slug'=>$league->getSlug()) );
                }
                else
                    $this->addFlash('error', 'Verifier si le password, si nécessaire, est correct. Ou vous avez peut être déjà rejoint cette ligue.');
            }
            else
                $this->addFlash('error', 'Vous devez être connecter pour rejoindre une ligue.');
            
        }


        return $this->render('FantasyBundle:League:index.html.twig', array(
        		'myleagues'	=> $myleagues,
                'leagues' 	=> $leagues,
                'nbPages'   => $nbPages,
                'page'      => $page,
                'form'      => $form->createView(),
            ));
    }


   	/**
     * @Route("/league/add", name="fantasy_league_add")
     */ 
    public function addAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

    	$league = new League();
    	$form = $this->createForm(LeagueAddType::class, $league);

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

    		$data = $request->request->all();
    		$datastats = array(
    				'points'	=> $data['league_add']['stats_points'],
    				'rebonds'	=> $data['league_add']['stats_rebonds'],
    				'assists'	=> $data['league_add']['stats_assists'],
    				'double'	=> $data['league_add']['stats_double'],
    				'triple'	=> $data['league_add']['stats_triple']
    			);

    		$league->setPointsPerStats($datastats);
    		$league->setOwner($this->getUser());
    		$league->setUniqueId(hash('sha1', $league->getName().rand(100,999).uniqid()));
    		if(!$league->getPrivate())
    			$league->setPassword(null);
            $league->addUser($this->getUser());

            $talk = new Talk();
            $talk->setMessage('Welcome to the league ! Now talk. TALK !');
            $talk->setUser($this->getUser());
            $talk->setLeague($league);
            $talk->setCreated(new \Datetime('now'));

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($league);
            $em->persist($talk);
    		$em->flush();

            // On redirige vers la page de la league plutot
            $this->addFlash('success', 'Votre ligue "'.$league->getName().'" a correctement été créer.');
    		return $this->redirectToRoute('fantasy_league_detail', array('id'=>$league->getId(), 'slug'=>$league->getSlug()) );
    	}
  
    	return $this->render('FantasyBundle:League:add.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
    * @Route("/league/edit/{id}", name="fantasy_league_edit")
    */
    public function editAction(Request $request, $id)
    {

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository('FantasyBundle:League')->find($id);

        // On s'assure que ce soit bien le proprio
        if( $this->getUser()->getId() != $league->getOwner()->getId() )
            throw $this->createAccessDeniedException();

        $form = $this->createForm(LeagueAddType::class, $league);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            $data = $request->request->all();
            $datastats = array(
                    'points'    => $data['league_add']['stats_points'],
                    'rebonds'   => $data['league_add']['stats_rebonds'],
                    'assists'   => $data['league_add']['stats_assists'],
                    'double'    => $data['league_add']['stats_double'],
                    'triple'    => $data['league_add']['stats_triple']
                );

            $league->setPointsPerStats($datastats);
            if(!$league->getPrivate())
                $league->setPassword(null);

            $em->persist($league);
            $em->flush();

            // On redirige vers la page de la league plutot
            $this->addFlash('success', 'Votre ligue "'.$league->getName().'" a correctement été modifié.');
            return $this->redirectToRoute('fantasy_league_detail', array('id'=>$league->getId(), 'slug'=>$league->getSlug()) );
        }
  
        return $this->render('FantasyBundle:League:add.html.twig', array(
                'form' => $form->createView(),
            ));


    }


    private function formatClassement($users, $cl)
    {

        foreach($users as $user){
            $d = $user->getId();
            $newUsers[$d] = $user;
        }
        
        foreach($cl as $cId => $c){
            $classm[] = array(
                    'user'      => $newUsers[$cId],
                    'points'    => $c,
                );
            unset($newUsers[$cId]);
        }

        foreach($newUsers as $nu) {
            $classm[] = array(
                    'user'      => $nu,
                    'points'    => 0,
                );  
        }

        return $classm;
    }


    /**
    * @Route("/league/{id}-{slug}", name="fantasy_league_detail")
    */
    public function detailAction($id, $slug)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository('FantasyBundle:League')->find($id);
        $classementBrut = $em->getRepository('PlayoffBundle:Statsheet')->classementOfUSers($league->getUsers());
        $classement = $this->formatClassement($league->getUsers(), $classementBrut);

        return $this->render('FantasyBundle:League:detail.html.twig', array(
                'league'    => $league,
                'classement'    => $classement,
            ));
    }

    /**
    * @Route("/autojoin/{hash}", name="fantasy_league_join_url")
    */
    public function joinLeagueViaURL($hash="")
    {

        if( empty($hash) )
            $this->addFlash('error', 'Sans identifiant/code, impossible de rejoindre une ligue.');

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $isValid = true;
            $em = $this->getDoctrine()->getManager();
            $league = $em->getRepository('FantasyBundle:League')->findOneByUniqueId($hash);
            $idu = $this->getUser()->getId();
            foreach($league->getUsers() as $us){
                if($us->getId() == $idu){
                    $isValid = false; break;
                }
            }
            $league->addUser($this->getUser()); 

            if($isValid) {
                $em->persist($league);
                $em->flush();
                $this->addFlash('success', 'Vous avez rejoint la ligue '.$league->getName());
                return $this->redirectToRoute('fantasy_league_detail', array('id'=>$league->getId(), 'slug'=>$league->getSlug()) );
            }
            else
                $this->addFlash('error', 'Vous êtes sur de ne pas déjà avoir rejoint cette ligue ??!');
        }
        else
            $this->addFlash('error', 'Vous devez être connecter pour rejoindre une ligue.');

        return $this->redirectToRoute('fantasy_leagues');

    }

    /**
    * @Route("/league/reset/{id}", name="fantasy_league_reset_uniqueid")
    */
    public function resetUniqueId($id) {

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository('FantasyBundle:League')->find($id);

        // On s'assure que seul le proprio de la ligue soit capable de reset l'id
        if( $this->getUser()->getId() != $league->getOwner()->getId() )
            throw $this->createAccessDeniedException();

        $uid = hash('sha1', $league->getName().rand(100,999).uniqid());
        $league->setUniqueId($uid);
        $em->flush();

        return new JsonResponse( $uid );

    }

    /**
    * @Route("/talk/{id}", name="fantasy_league_talk")
    */
    public function talkformAction(Request $request, $id)
    {
        $talk = new Talk();
        $form = $this->createForm(TalkAddType::class, $talk);
        $em = $this->getDoctrine()->getManager();


        if ( $request->isXmlHttpRequest() ) {

            $form->handleRequest($request);
            $data = $request->request->all();

            $talk->setMessage($data['msg']);
            $talk->setLeague($em->getRepository('FantasyBundle:League')->find($id));
            $talk->setUser($this->getUser());
            $talk->setCreated(new \Datetime('now'));
            $em->persist($talk);
            $em->flush();
            $response = 'success';
            return new JsonResponse( $response );

        }

        return $this->render('FantasyBundle:League:includeTalk.html.twig', array(
                'form'      => $form->createView(),
            ));
    }

    /**
    * @Route("/ajax/talks/{id}", name="fantasy_ajax_league_talks")
    */
    public function talkAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $talks = $em->getRepository('FantasyBundle:Talk')->getByLeague($id);
        return $this->render('FantasyBundle:League:includeTalks.html.twig', array(
            'talks'      => $talks,
        ));
    }

    /**
    * @Route("/league/quit/{idl}", name="fantasy_league_quit")
    */
    public function quitAction($idl)
    {

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository('FantasyBundle:League')->find($idl);
        $league->removeUser($this->getUser());
        $em->flush();
        $this->addFlash('success', 'Vous avez quitté la ligue '.$league->getName());
        return $this->redirectToRoute('fantasy_leagues');

    }

}
