<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 11.09.17
 * Time: 0:00
 */

namespace SibirCtfBundle\Controller;

use SibirCtfBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class CabinetController extends Controller
{

    /**
     * @Route("/cabinet", name="cabinet")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function cabinetAction()
    {
        $user = $this->getUser();
        if ($user->getMember()) {
            $team = $user->getMember()->getTeam();
        }
        else {
            $team = false;
        }

        $steps = array();

        if (empty($user->getArrivalDescription()) && $user->getMember()) {
            $steps[] = '<a href="'. $this->generateUrl('cabinet-team-arrival') .'">Написать</a> время прибытия, чтобы мы могли тебя встретить (для иногородних участников)';
        }

        return $this->render('SibirCtfBundle:cabinet:index.html.twig', [
            'user' => $user,
            'team' => $team,
            'steps' => $steps
        ]);
    }

    private function transliterate($st) {
        $st = strtr($st,
            "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ",
            "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE"
        );
        $st = strtr($st, array(
            'ё'=>"yo",    'х'=>"h",  'ц'=>"ts",  'ч'=>"ch", 'ш'=>"sh",
            'щ'=>"shch",  'ъ'=>'',   'ь'=>'',    'ю'=>"yu", 'я'=>"ya",
            'Ё'=>"Yo",    'Х'=>"H",  'Ц'=>"Ts",  'Ч'=>"Ch", 'Ш'=>"Sh",
            'Щ'=>"Shch",  'Ъ'=>'',   'Ь'=>'',    'Ю'=>"Yu", 'Я'=>"Ya",
        ));
        return $st;
    }

    /**
     * @Route("/cabinet/arrival", name="cabinet-team-arrival")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function teamArrivalAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm('SibirCtfBundle\Form\MemberArrivalType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailer = $this->get('mailer');

            if ($user->getMember()) {
                $text = "Участник ".$user->getMember()->getFio()." из команды ".$user->getMember()->getTeam()->getTitle();
                $text .= "<br /> ".$form->get('arrival_description')->getData();

            }
            else {
                $text = $user->getMail().'<br>'.$form->get('arrival_description')->getData();
            }
            $message = $mailer->createMessage()
                ->setSubject('Информация о приезде')
                ->setFrom('noreply@sibirctf.org')
                ->setTo('info@sibirctf.org')
                ->setBody($text, 'text/html');
            $mailer->send($message);

            $em->persist($user);
            $em->flush();
        }

        return $this->render('SibirCtfBundle:cabinet:team-arrival.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cabinet/team/edit", name="cabinet-team-edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function teamEditAction(Request $request)
    {
        $user = $this->getUser();

        if (!$user->getMember()->getCaptain()) {
            return $this->redirectToRoute('cabinet');
        }

        $form = $this->createForm('SibirCtfBundle\Form\MemberArrivalType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
        }

        return $this->render('SibirCtfBundle:cabinet:team-arrival.html.twig', [
            'form' => $form->createView()
        ]);
    }
}