<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $doctrine = $this->getDoctrine();
        $profile = $doctrine->getRepository('App:Profile')->find(1);

        return $this->render('home/index.html.twig', [
            'profile' => $profile,
        ]);
    }

    /**
     * @Route("/projects", name="home_projects")
     */
    public function projects()
    {
        $doctrine = $this->getDoctrine();
        $profile = $doctrine->getRepository('App:Profile')->find(1);

        return $this->render('home/index.html.twig', [
            'profile' => $profile,
            'asd' => ''
        ]);
    }
}
