<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UserBundle\Form\UserEditType;

class UserController extends Controller
{

	/**
     * @Route("/admin/users/{page}", name="admin_users_list", requirements={"page":"\d+"})
     */
	public function admUsersAction($page=1)
	{
		if ($page < 1) 
            throw new NotFoundHttpException('Page "'.$page.'" does not exist.');

        $perpage = $this->getParameter('nb_page_admin');
		$em = $this->getDoctrine()->getManager();
		$users = $em->getRepository('UserBundle:User')->getUsers($page,$perpage);
        $nbPages = ceil(count($users) / $perpage);

        if ($page > $nbPages) 
            throw $this->createNotFoundException("Page ".$page." does not exist.");

		return $this->render('UserBundle:Admin:users.html.twig', array(
        		'users' => $users,
        		'nbPages'   => $nbPages,
                'page'      => $page,
        	));
	}

	/**
     * @Route("/admin/user/{id}", name="admin_user_edit")
     */
	public function admUserEditAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('UserBundle:User')->find($id);

		if( $user === null )
			throw $this->createNotFoundException('Cette utilisateur n\'existe pas');

		foreach($this->container->getParameter('security.role_hierarchy.roles') as $r=>$rc){
			$roles[$r] = $r;
		}
		$form = $this->createForm(UserEditType::class, $user, array('roles'=>$roles));

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

			
			$data = $request->request->all();
			dump($data['user_edit']['roles']);
			$user->setRoles($data['user_edit']['roles']);

			$em->persist($user);
			$em->flush();

			$loger = $this->get('core_logs');
			$loger->saveLog(array('createdBy'=> $this->getUser(), 'type'=>'User', 'message'=>'utilisateur lambda a correctement été modifié'));

			return $this->redirectToRoute('admin_users_list');
		}

		return $this->render('UserBundle:Admin:user_edit.html.twig', array(
        		'form' => $form->createView(),
        	));
	}

}
