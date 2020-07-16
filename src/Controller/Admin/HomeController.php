<?php

namespace App\Controller\Admin;

use App\Entity\Component;
use App\Entity\Field;
use App\Entity\Profile;
use App\Entity\Type;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @return Response
     */
    public function index()
    {

        $profile = $this->getDoctrine()->getRepository('App:Profile')->find(1) ?? new Profile();
        $types = $this->getDoctrine()->getRepository('App:Type')->findAll();

        return $this->render('admin/index.html.twig', [
            'profile' => $profile,
            'component' => $profile->getComponents(),
            'types' => $types,
        ]);
    }

    /**
     * @Route("/admin/manage/{slug}", name="admin_manage")
     * @param string $slug
     * @return Response
     */
    public function manageEntities(string $slug = 'Components')
    {
        $entity = $this->getDoctrine()->getRepository('App:'. ucfirst(substr($slug,0,-1)))->findAll();

        return $this->render('admin/manage.html.twig', [
            'types' => $this->getDoctrine()->getRepository('App:Type')->findAll(),
            'table_data' => $entity,
            'slug' => $slug
        ]);
    }

    /**
     * @Route("/admin/ajax/edit/{type}/{id}", name="admin_edit")
     * @param Request $request
     * @param string $type
     * @param int $id
     * @return Response|void
     */
    public function editEntity(Request $request, string $type, int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository('App:' . $type)->findOneBy(['id' => $id]);
        $types = $entityManager->getRepository('App:Type')->findAll();

        return $this->render('admin/edit.html.twig', [
                'slug' => lcfirst($type . 's'),
                'types' => $types,
                'object' => $entity,
                'action' => 'update'
        ]
        );
    }

}
