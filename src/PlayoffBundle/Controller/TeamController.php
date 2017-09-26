<?php

namespace PlayoffBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\File\File;

use PlayoffBundle\Form\TeamEditType;


class TeamController extends Controller
{
	
    /**
    * @Route("/admin/teams", name="admin_playoff_teams")
    */
    public function admTeamsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $teams = $em->getRepository('PlayoffBundle:Team')->findBy(array(), array('conference' => 'ASC', 'rank'=>'ASC'));

        return $this->render('PlayoffBundle:Admin:teams.html.twig', array(
                'teams' => $teams,
            ));
    }


    /**
    * @Route("/admin/team/{id}", name="admin_playoff_team_edit")
    */
    public function admTeamEditAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('PlayoffBundle:Team')->find($id);
        $tmp_logo = $team->getLogo();

        if( $team === null )
            throw $this->createNotFoundException('This Team does not exist');

        $form = $this->createForm(TeamEditType::class, $team, array(

            ));

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            $file = $team->getLogo();
            if( $file ) {
                $filename = $team->getShortname().'.'.$file->guessExtension();
                $file->move($this->getParameter('img_dir_team_logo'),$filename);
                $team->setLogo($filename);
            }
            else
                $team->setLogo($tmp_logo);
            $em->persist($team);
            $em->flush();

            $loger = $this->get('core_logs');
            $loger->saveLog(array('createdBy'=> $this->getUser(), 'type'=>'Team', 'message'=>'Mise à jour de la team '.$team->getName().' #'.$team->getId() ));
            return $this->redirectToRoute('admin_playoff_teams');
        }


        $logo = ( !empty($team->getLogo()) && file_exists($this->getParameter('img_dir_team_logo').'/'.$team->getLogo()) ) ? $team->getLogo() : 'default.jpg';

        return $this->render('PlayoffBundle:Admin:team_edit.html.twig', array(
                'form' => $form->createView(),
                'logo' => $logo,
            ));

    }


}
