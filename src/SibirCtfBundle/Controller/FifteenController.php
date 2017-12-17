<?php

namespace SibirCtfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/2015")
 */
class FifteenController extends Controller
{
    /**
     * @Route("/", name="2015")
     */
    public function indexAction()
    {
        return $this->redirect('/');
    }
}
