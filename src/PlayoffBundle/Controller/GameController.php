<?php

namespace PlayoffBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\DomCrawler\Crawler;

use PlayoffBundle\Entity\Game;
use PlayoffBundle\Entity\Statsheet;
use PlayoffBundle\Form\GameAddType;
use PlayoffBundle\Form\GameEditType;


class GameController extends Controller
{
	
    /*
    *   nettoie un string
    */
    private function cleanstring($str)
    {

            $str = str_replace('\r', '', $str);
            $str = str_replace('\n', '', $str);
            $str = str_replace('\t', '', $str);
            return trim($str);

    }

    private function datafetch_extract_unit($string, $start, $end) {

        $pos = stripos($string, $start);
        $str = substr($string, $pos);
        $str_two = substr($str, strlen($start));
        $second_pos = stripos($str_two, $end);
        $str_three = substr($str_two, 0, $second_pos);
        $unit = trim($str_three); // remove whitespaces
         
        return $unit;
    }

    private function getStatForGame($urlid)
    {
        $url = 'http://scores.nbcsports.com/nba/boxscore.asp?gamecode='.$urlid;
        // $url = 'http://192.168.0.13/playoff/web/test.html';
        $html = file_get_contents($url);
        $crawler = new Crawler($html);
        $allnodes = $crawler->filter('td.shsMastheadScore');
        $data['score'] = array(
                'ext'   => $allnodes->eq(0)->text(),
                'dom'   => $allnodes->eq(1)->text(),
            );

        $allnodess = $crawler->filter('tr.shsRow0Row, tr.shsRow1Row')->each(function (Crawler $node, $i){
            
            $nodeChildren = $node->children();
            // dump($nodeChildren->eq(0)->text());
            if($nodeChildren->eq(1)->attr('class') == 'shsDNP'){
                $pl = array(
                    'playerid'  => $this->datafetch_extract_unit( $nodeChildren->eq(0)->children()->attr('href'), '/nba/playerstats.asp?id=', '&team='),
                    'stats'     => array(
                        'rebonds'   => 0,
                        'assists'   => 0,
                        'blocks'    => 0,
                        'steals'    => 0,
                        'turnover'  => 0,
                        'points'    => 0,
                    ),
                    'total' => 0,
                );
            }
            else{

                $pl = array(
                    'playerid'  => $this->datafetch_extract_unit( $nodeChildren->eq(0)->children()->attr('href'), '/nba/playerstats.asp?id=', '&team='),
                    'stats'     => array(
                        'rebonds'   => ($nodeChildren->eq(8)->text()+$nodeChildren->eq(7)->text()),
                        'assists'   => (int)$nodeChildren->eq(9)->text(),
                        'blocks'    => (int)$nodeChildren->eq(10)->text(),
                        'steals'    => (int)$nodeChildren->eq(11)->text(),
                        'turnover'  => (int)$nodeChildren->eq(12)->text(),
                        'points'    => (int)$nodeChildren->eq(14)->text(),
                    ), 
                );
                $pl['total'] = $pl['stats']['rebonds'] + $pl['stats']['assists'] + $pl['stats']['points'];
            }
            
            return $pl;

        });

        $data['stats'] = $allnodess;
        
        return $data;
    }


    /**
    * @Route("/admin/statsheet/fetch/{id}", name="admin_playoff_statsheet_fetch")
    */
    public function admStatsheetAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('PlayoffBundle:Game')->findOneByAid($id);

        // On parse uniquement les matchs non déjà parsé (donc pas de score/vainqueur)
        if($game->getWinner() == null) {
            $stats = $this->getStatForGame($id);
            $game->setScore($stats['score']['ext'].'-'.$stats['score']['dom']);
            $winner = ($stats['score']['ext'] > $stats['score']['dom']) ? $game->getTeamExt() : $game->getTeamDom();
            $game->setWinner($winner);
            $em->persist($game);

            foreach($stats['stats'] as $stat){
                $player = $em->getRepository('PlayoffBundle:Player')->findOneByAid($stat['playerid']);
                $ss = new Statsheet();
                $ss->setGame($game);
                $ss->setDay($game->getDay());
                $ss->setPlayer($player);
                $ss->setStats($stat['stats']);
                $ss->setPoints($stat['total']);
                $em->persist($ss);
            }
            $em->flush();
            $loger = $this->get('core_logs');
            $loger->saveLog(array('createdBy'=> $this->getUser(), 'type'=>'Games', 'message'=>'Stats pour le match xxx@xxx ont bien été enregistré' ));
        }
        return $this->redirectToRoute('admin_playoff_games');
    }


    /**
    * @Route("/admin/games", name="admin_playoff_games")
    */
    public function admGamesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository('PlayoffBundle:Game')->findBy(array(), array('date' => 'DESC'));

        return $this->render('PlayoffBundle:Admin:games.html.twig', array(
                'games' => $games,
            ));
    }

    /**
    * @Route("/admin/game/delete/{id}", name="admin_playoff_game_delete")
    */
    public function admGameDeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('PlayoffBundle:Game')->find($id);

        if( $game === null )
            throw $this->createNotFoundException('This Game (id #'.$id.'#) does not exist');

        $em->remove($game);
        $em->flush();

        $loger = $this->get('core_logs');
        $loger->saveLog(array('createdBy'=> $this->getUser(), 'type'=>'Games', 'message'=>'Le match '.$game->getTeamDom()->getName().' @ '.$game->getTeamExt()->getName().' a correctement été déruit.' ));
        
        return $this->redirectToRoute('admin_playoff_games');
    }


    /**
    * @Route("/admin/game/add", name="admin_playoff_game_add")
    */
    public function admGameAddAction(Request $request)
    {
        $game = new Game();
        $form = $this->createForm(GameAddType::class, $game);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            
            //$date = new \Datetime($game->getDateGMT(), new \DateTimeZone('America/New_York'));
            // $date->setTimeZone(new \DateTimeZone('UTC'));
            // On enregistre la dateET !!!
            $date = new \Datetime($game->getDate());
            $game->setDate($date);
            $game->setDay($date);

            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();

            $loger = $this->get('core_logs');
            $loger->saveLog(array('createdBy'=> $this->getUser(), 'type'=>'Game', 'message'=>'Match enregistré' ));
            return $this->redirectToRoute('admin_playoff_games');
        }


        return $this->render('PlayoffBundle:Admin:game_add.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
    * @Route("/admin/game/edit/{id}", name="admin_playoff_game_edit")
    */
    public function admGameEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('PlayoffBundle:Game')->find($id);

        if( $game === null )
            throw $this->createNotFoundException('This Team does not exist');

        $form = $this->createForm(GameEditType::class, $game);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            
            // On cherche le vainqueur
            if( !empty($game->getScore()) ) {
                $score = explode('-', $game->getScore());
                $winner = ( $score[0] > $score[1] ) ? $game->getTeamExt() : $game->getTeamDom() ;
                $game->setWinner($winner);
            }
            $date = $game->getDate();
            $game->setDate($date);
            $game->setDay($date);

            $em->persist($game);
            $em->flush();

            $loger = $this->get('core_logs');
            $loger->saveLog(array('createdBy'=> $this->getUser(), 'type'=>'Game', 'message'=>'Match mis à jour' ));
            return $this->redirectToRoute('admin_playoff_games');
            

        }


        return $this->render('PlayoffBundle:Admin:game_edit.html.twig', array(
                'form' => $form->createView(),
            ));
    }

}
