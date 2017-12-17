<?php

namespace SibirCtfBundle\Controller;

use SibirCtfBundle\Entity\Team;
use SibirCtfBundle\Entity\News;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin-index")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('SibirCtfBundle:News');

        $news = $repository->findAll();

        return $this->render('SibirCtfBundle:admin:index.html.twig', array(
            'news' => $news
        ));
    }

    /**
     * @Route("/news", name="admin-news")
     */
    public function newsAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $news = new News();
        $news->setDate(new \DateTime('now'));

        $form = $this->createForm('SibirCtfBundle\Form\NewsType', $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $news = $form->getData();

            $file = $news->getPoster();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('poster_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $news->setPoster($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('admin-index');
        }

        return $this->render('SibirCtfBundle:admin:news.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/news/{news}/delete", name="admin-news-delete")
     */
    public function newsDeleteAction(Request $request, News $news)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();

        return $this->redirectToRoute('admin-index');
    }

    /**
     * @Route("/news/{news}", name="admin-news-edit")
     */
    public function newsEditAction(Request $request, News $news)
    {
        $news->setPoster(
            new File($this->getParameter('poster_directory').'/'.$news->getPoster())
        );

        $form = $this->createForm('SibirCtfBundle\Form\NewsType', $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $news = $form->getData();

            $file = $news->getPoster();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('poster_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $news->setPoster($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('admin-index');
        }

        return $this->render('SibirCtfBundle:admin:news.html.twig', array(
            'form' => $form->createView(),
            'news' => $news
        ));
    }

    /**
     * @Route("/request", name="admin-request")
     */
    public function requestAction()
    {
        $repository = $this->getDoctrine()->getRepository('SibirCtfBundle:Team');

        $teamsPending = $repository->findBy(array('status' => 0));

        return $this->render('SibirCtfBundle:admin:request.html.twig', array(
            'pending' => $teamsPending
        ));
    }

    /**
     * @Route("/users", name="admin-users")
     */
    public function usersAction()
    {
        $repository = $this->getDoctrine()->getRepository('SibirCtfBundle:User');

        $users = $repository->findAll();

        return $this->render('SibirCtfBundle:admin:users.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @Route("/approved", name="admin-approved")
     */
    public function approvedAction()
    {
        $repository = $this->getDoctrine()->getRepository('SibirCtfBundle:Team');

        $teamsPending = $repository->findBy(array('status' => 1));

        return $this->render('SibirCtfBundle:admin:approved.html.twig', array(
            'pending' => $teamsPending
        ));
    }

    /**
     * @Route("/request/approve/{team}", name="admin-request-approve")
     *
     * @param Request $request
     * @param Team $team
     *
     * @return JsonResponse
     */
    public function approveTeamAction(Request $request, Team $team)
    {
        $em = $this->getDoctrine()->getManager();


        $team->setStatus('1');
        $team->setStatusDescription('Заявка одобрена');

        $mail = $team->getEmail();

        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject($team->getTitle() . ', мы ждем тебя на SibirCTF 2017!')
            ->setFrom('noreply@sibirctf.org')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'SibirCtfBundle:mail:approved.txt.twig',
                    array('team' => $team->getTitle())
                ),
                'text/html'
            );
        $mailer->send($message);


        $em->persist($team);
        $em->flush();

        return new JsonResponse(array('success' => '1'));
    }

    /**
     * @Route("/request/delete/{team}", name="admin-team-delete")
     *
     * @param Request $request
     * @param Team $team
     */
    public function deleteTeamAction(Request $request, Team $team)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $this->getDoctrine()->getRepository('SibirCtfBundle:Member');
        $members = $repo->findBy(array(
            'team' => $team->getId()
        ));

        foreach($members as $m) {
            $em->remove($m);
        }

        $em->flush();

        return $this->redirect('admin-request');
    }
}
