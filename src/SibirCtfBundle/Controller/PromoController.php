<?php

namespace SibirCtfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/promo")
 */
class PromoController extends Controller
{
    /**
     * @Route("/i_ll_be_there", name="promo_approved")
     */
    public function approvedAction()
    {
        return $this->render('SibirCtfBundle:promo:approved.html.twig');
    }
}
