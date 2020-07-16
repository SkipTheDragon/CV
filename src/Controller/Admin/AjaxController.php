<?php

namespace App\Controller\Admin;

use App\Entity\Component;
use App\Entity\Field;
use App\Entity\Profile;
use App\Entity\Type;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    /**
     * @Route("/admin/ajax/updateProfile", name="admin_update_profile")
     * @param Request $request
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @return Response
     */
    public function updateProfile(Request $request, string $uploadDir, FileUploader $uploader) {
        if ($request->getMethod() == 'POST') {
            $files = [$request->files->get('image') ?? null, $request->files->get('cv') ?? null];
            foreach ($files as $file) {
                if ($file != null) {
                    $filename = $file->getClientOriginalName();
                    $uploader->upload($uploadDir, $file, $filename);
                }
            }

            $profile = $this->getDoctrine()->getRepository('App:Profile')->find(1) ?? new Profile();
            $entityManager = $this->getDoctrine()->getManager();
            $profile->setName($request->get('name'));
            $profile->setRole($request->get('role'));
            $profile->setQuotation($request->get('citat'));
            $profile->setProfileImg('uploads/'.$request->files->get('image')->getClientOriginalName());
            $profile->setCv('uploads/'.$request->files->get('cv')->getClientOriginalName());

            $entityManager->persist($profile);
            $entityManager->flush();
            $this->addFlash('success','Profile updated successfully');

            return $this->goBack($request);
        }
    }

    public function addType(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $data = $request->request->all();
            $type_object = new Type();
            $type_object->setName($data['name']);
            $type_object->setRules($data['fields']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($type_object);
            $entityManager->flush();
            $this->addFlash('success','New Type added successfully');

        }
        return new Response(
            "Noice", Response::HTTP_OK,
            ['content-type' => 'text/plain']
        );
    }

    public function addComponent(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $component = new Component();
            $component->setProfile($this->getDoctrine()->getRepository('App:Profile')->find(1));
            $component->setTitle($request->get('name'));
            $component->setType($this->getDoctrine()->getRepository('App:Type')->findOneBy(['name' => $request->get('types')]));
            $component->setLocation($request->get('location') ?? 'left');
            $component->setPosition( intval($request->get('position')) ?? 1);
            $entityManager = $this->getDoctrine()->getManager();
            $fields = $request->get('fields');
            if (!is_null($fields)) {
                $this->manageFields($request,$component);
            }
            $entityManager->persist($component);
            $entityManager->flush();
            $this->addFlash('success','Component added successfully');

            return $this->goBack($request);
        }
    }
    public function manageFields(Request $request,Component $component)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $fileFields = $request->files->get('fields');

        foreach ($request->get('fields') as $fieldkey => $requestData) {

                $field =  isset($requestData['fieldID']) ? $this->getDoctrine()->getRepository('App:Field')->find($requestData['fieldID']) : new Field();

                // upload files
                if ($request->files->count() > 0) { // check if we have files at all
                    $files = $fileFields[$fieldkey]['data'];
                    foreach ($files as $key => $file) {
                        if ($file) { // check if we have a new file to upload
                            $filename = $file->getClientOriginalName();
                            $file->move('../public/uploads/', $filename);
                            $requestData['data'][$key] = $filename;
                        } elseif($field->getData()['image']) { // keep the old one
                            $requestData['data'][$key] = $field->getData()['image'];
                        }
                    }
                }

                if (!$field->getComponent()) {
                    $field->setComponent($component);
                }

                // update or create field
                if (isset($requestData['fieldID'])) {
                    $componentid = $component->getId();
                    if ($field->getComponent()->getId() == $componentid) {
                        $field->setData($requestData['data'] ?? '');
                        $field->setPosition(1);
                    }
                } else {
                    $field->setData($requestData['data']);
                    $field->setPosition(1);
                    $field->setComponent($component);
                    $entityManager->persist($field);
                }
        }
    }
    public function updateComponent(Request $request, $componentid)
    {
        if ($request->getMethod() == 'POST') {
            $component = $this->getDoctrine()->getRepository('App:Component')->find($componentid);
            $component->setTitle($request->get('name'));
            $component->setType($this->getDoctrine()->getRepository('App:Type')->findOneBy(['name' => $request->get('types')]));
            $component->setLocation($request->get('location') ?? 'left');
            $component->setPosition( intval($request->get('position')) ?? 1);
            $fields = $component->getFields();
            $entityManager = $this->getDoctrine()->getManager();
            if (!is_null($fields)) {
                $this->manageFields($request,$component);
            }
            $entityManager->persist($component);
            $entityManager->flush();
            $this->addFlash('success','Component edited successfully');
            return $this->goBack($request);
        }
    }


//    public function updateType(Request $request) todo maybe add
//    {
//    }

    /**
     * @Route("/admin/ajax/manage/{entity}/{action}/{id}", name="admin_manage_entities")
     * @param Request $request
     * @param string $entity
     * @param string $action
     * @param null $id
     * @return Response|void
     */
    public function entityManageActions(Request $request, $entity = 'Component', $action = 'add', $id = null)
    {
        if ($action == 'update') {
            return $this->{$action . $entity}($request, $id);
        } else {
            return $this->{$action . $entity}($request);
        }
    }


    /**
     * @Route("/admin/ajax/getType/{type}/{page}", name="admin_get_type")
     * @param string $type
     * @param int $page
     * @return Response|void
     */
    public function getType(string $type, int $page = 0)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $type = $entityManager->getRepository('App:Type')->findOneBy(['name' => $type]);

        return $this->render(
            'admin/components/_fieldForm.html.twig', [
            'type' => $type,
            'page' => $page
        ]);
    }

    /**
     * @Route("/admin/ajax/delete/{type}/{id}", name="admin_delete")
     * @param Request $request
     * @param string $type
     * @param int $id
     * @return Response|void
     */
    public function deleteObject(Request $request, string $type, int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $object = $entityManager->getRepository('App:' . $type)->findOneBy(['id' => $id]);
        $entityManager->remove($object);
        $entityManager->flush();
        $this->addFlash('success','Object deleted successfully');

        return $this->goBack($request);
    }

    public function goBack(Request $request) {
        $request->getSession()
            ->getFlashBag()
            ->add('doNotShow', 'Success!');
        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

}
