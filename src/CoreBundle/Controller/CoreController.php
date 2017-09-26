<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Validator\Constraints\DateTime;

class CoreController extends Controller
{
	

    /**
     * @Route("/", name="core_home")
     */
    public function indexAction()
    {

    	$date_nba = new \DateTime('2017-04-14 21:30');
    	$date_now = new \DateTime('now');

    	$interval = $date_nba->diff($date_now);
        if(!$interval->invert)
            $interval = $date_now->diff($date_now);

        return $this->render('CoreBundle:Core:home.html.twig', array(
        		'countdown' => $interval,
        	));
    }


    /**
     * @Route("/admin", name="core_admin_home")
     */
    public function adminAction()
    {

        return $this->render('CoreBundle:Admin:home.html.twig');
    }


    /**
    * @Route("/admin/logs/{page}", name="admin_core_logs", requirements={"page":"\d+"})
    */
    public function admLogsAction($page=1)
    {
        if ($page < 1) 
            throw new NotFoundHttpException('Page "'.$page.'" does not exist.');

        $perpage = $this->getParameter('nb_page_admin');
        $em = $this->getDoctrine()->getManager();
        $logs = $em->getRepository('CoreBundle:Log')->getLogs($page,$perpage);
        $nbPages = ceil(count($logs) / $perpage);

        if ($page > $nbPages) 
            throw $this->createNotFoundException("Page ".$page." does not exist.");

        return $this->render('CoreBundle:Admin:logs.html.twig', array(
                'logs' => $logs,
                'nbPages'   => $nbPages,
                'page'      => $page,
            ));
    }


}
