<?php

namespace Esokia\Bundle\ContactBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Esokia\Bundle\ContactBundle\Entity\Contact;
use Esokia\Bundle\ContactBundle\Form\ContactType;

/**
 * Contact controller.
 *
 */
class ContactController extends Controller
{

    /**
     * Lists all Contact entities.
     * 
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('EsokiaContactBundle:Contact')->findAllQuery($request->query->get('sort'));
  
        
         $paginator  = $this->get('knp_paginator');
         $pagination = $paginator->paginate(
                $query,
                $request->query->get('page', 1)/*page number*/,
                5/*limit per page*/
            );
        
        
        $deleteForms = array();
        foreach ($pagination as $entity) {
              $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
          }
        
        return $this->render('EsokiaContactBundle:Contact:index.html.twig', array(
            'deleteForms' => $deleteForms,
            'pagination' => $pagination
        ));
    }
    
    
    
    
    /**
     * Create a new contact
     * Save it in DB
     * Send email to the website admin
     * 
     * 
     * @param Request $request
     * @return type
     */
    public function createAction(Request $request)
    {
        $entity = new Contact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setIp($request->getClientIp());
            $em->persist($entity);
            $em->flush();
            
            $this->sendContactEmail($entity);
            
            //add message for the user
            $request->getSession()->getFlashBag()->add(
                       'success',
                       $this->get('translator')->trans('Your email has been sent')
                   );
            
           // redirect to empty form
            return $this->redirect($this->generateUrl('EsokiaContactBundle_contact_new'));
            
            
        }

        return $this->render('EsokiaContactBundle:Contact:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    
    
    
    /**
     * send a contact to the default site email
     * This method use Swift Maile wich a sender agnostic library
     * Always use Swift to send email with Symfony
     * Always use the 'part' method to create a TXT version of emails you will send 
     *
     * @param Contact $contact
     */
    protected function sendContactEmail(Contact $contact){
        
        
             $message = \Swift_Message::newInstance();
             $message->setSubject($this->container->getParameter('site.name').' - new contact')
                        ->setFrom($this->container->getParameter('site.email'))
                        ->setTo($this->container->getParameter('site.email'))
                     ->setBody(
                            $this->renderView(
                                 'EsokiaContactBundle:Email:email.html.twig',
                                 array(
                                     'contact'=> $contact,
                                     )
                             ),
                              'text/html'
                         )

                        ->addPart($this->renderView(
                                        'EsokiaContactBundle:Email:email.txt.twig',
                                        array(
                                            'contact'=> $contact,
                                            )
                                        ), 'text/plain');

           return $this->get('mailer')->send($message);
    }







    /**
     * Creates a form to create a Contact entity.
     *
     * @param Contact $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Contact $entity)
    {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('EsokiaContactBundle_contact_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Send'));

        return $form;
    }

    /**
     * Displays a form to create a new Contact entity.
     * @Cache(expires="+1 month", public=true, vary={"Cookie", "Accept-Encoding", "User-Agent"})
     */
    public function newAction()
    {
  
        $entity = new Contact();
        $form   = $this->createCreateForm($entity);

        return $this->render('EsokiaContactBundle:Contact:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EsokiaContactBundle:Contact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EsokiaContactBundle:Contact:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EsokiaContactBundle:Contact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EsokiaContactBundle:Contact:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Contact entity.
    *
    * @param Contact $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Contact $entity)
    {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('contact_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Contact entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EsokiaContactBundle:Contact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('contact_edit', array('id' => $id)));
        }

        return $this->render('EsokiaContactBundle:Contact:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Contact entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EsokiaContactBundle:Contact')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Contact entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('contact'));
    }

    /**
     * Creates a form to delete a Contact entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr'=>array('class'=>'btn btn-danger')))
            ->getForm()
        ;
    }
}
