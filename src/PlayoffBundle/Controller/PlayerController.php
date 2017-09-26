<?php

namespace PlayoffBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use PlayoffBundle\Entity\Player;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PlayerController extends Controller
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

    private function getPlayers($urlid)
    {
        $url = 'http://scores.nbcsports.com/nba/teamstats.asp?teamno='.$urlid.'&type=stats';
        $html = file_get_contents($url);
        $html = $this->datafetch_extract_unit($html, '<h2 class="shsTableTitle">Per Game Team Stats</h2>', '<br><table style=');

        $crawler = new Crawler($html);
        $allnodes = $crawler->filter('tr.shsRow1Row,tr.shsRow0Row')->each(function (Crawler $node, $i){
           
            $nodeChildren = $node->children();
            $pl = array(
                'name'  => $nodeChildren->eq(0)->children()->text(),
                'aid'   => $this->datafetch_extract_unit( $nodeChildren->eq(0)->children()->attr('href'), '/nba/playerstats.asp?id=', '&team='),
                'stats' => array(
                    'games'     => (int)$nodeChildren->eq(1)->text(),
                    'minutes'   => (float)$nodeChildren->eq(2)->text(),
                    'rebonds'   => ($nodeChildren->eq(6)->text()+$nodeChildren->eq(7)->text()),
                    'assists'   => (float)$nodeChildren->eq(8)->text(),
                    'steals'    => (float)$nodeChildren->eq(9)->text(),
                    'blocks'    => (float)$nodeChildren->eq(10)->text(),
                    'turnover'  => (float)$nodeChildren->eq(11)->text(),
                    'points'    => (float)$nodeChildren->eq(13)->text(),
                ),
            );
            
            return $pl;
        });

       return $allnodes;  

    }
	
    /**
    * @Route("/admin/players/{id}/{page}", name="admin_playoff_players", requirements={"page":"\d+", "id":"\d+|all"})
    */
    public function admPlayersAction($id, $page=1)
    {
        if ($page < 1) 
            throw new NotFoundHttpException('Page "'.$page.'" does not exist.');

        $perpage = $this->getParameter('nb_page_admin_players'); // nombre de joueur a afficher par page. Envoyer dans les parametres ?
        
        $em = $this->getDoctrine()->getManager();
        $players = $em->getRepository('PlayoffBundle:Player')->getPlayers($id, $page, $perpage);
        $nbPages = ceil(count($players) / $perpage);

        if ($page > $nbPages) 
            throw $this->createNotFoundException("Page ".$page." does not exist.");


        return $this->render('PlayoffBundle:Admin:players.html.twig', array(
                'players'   => $players,
                'nbPages'   => $nbPages,
                'page'      => $page,
                'idteam'    => $id
            ));
        
    }


    /**
    * @Route("/admin/players/fetch/{id}", name="admin_playoff_players_fetch")
    */
    public function admPlayersFetchAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('PlayoffBundle:Team')->find($id);

        if( $request->isMethod('POST') ) {
            $js = $request->request->get('form');
            $data = json_decode($js['jsontab']);
        }
        else 
            $data = $this->getPlayers($team->getAid());

        $form = $this->createFormBuilder()
            ->add('jsontab',   TextType::class , array('data' => json_encode($data) ))
            ->add('save',       SubmitType::class)
            ->getForm();
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            foreach($data as $player){
                $pl = new Player();
                $pl->setName($player->name);
                $pl->setAid($player->aid);
                $pl->setTeam($team);
                $pl->setSeasonStats($player->stats);
                $em->persist($pl);
            }

            $em->flush();
            $loger = $this->get('core_logs');
            $loger->saveLog(array('createdBy'=> $this->getUser(), 'type'=>'Players', 'message'=>'Joueurs pour l\'équipe '.$team->getName().' ont été ajouté via un fetch #NBCSPORTS' ));
            return $this->redirectToRoute('admin_playoff_teams');
        }

        return $this->render('PlayoffBundle:Admin:players_fetch.html.twig', array(
                'players'   => $data,
                'team'      => $team,
                'form'      => $form->createView(),
            ));

    }



}
