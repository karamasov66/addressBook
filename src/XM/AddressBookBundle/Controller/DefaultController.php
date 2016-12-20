<?php

namespace XM\AddressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use XM\AddressBookBundle\Entity\Contact;
use XM\AddressBookBundle\Form\ContactType;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('XMAddressBookBundle::index.html.twig');
    }
    
    public function homeAction()
    {
        $user = $this->getUser();
        $contacts = $user->getContacts();

        return $this->render('XMAddressBookBundle::home.html.twig', array('contacts' => $contacts));
    }

    public function addAction(Request $request)
    {
        $user = $this->getUser();
        $contact = new Contact();
        $form = $this->get('form.factory')->create(ContactType::class, $contact);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $user->addContact($contact);
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Contact bien enregistré.');

            return $this->redirectToRoute('address_book_home');
        }

        return $this->render('XMAddressBookBundle::add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository("XMAddressBookBundle:Contact");
        $contact = $contact->find($id);
        $form = $this->get('form.factory')->create(ContactType::class, $contact);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Contact bien modifié.');

            return $this->homeAction();
        }
        return $this->render('XMAddressBookBundle::edit.html.twig', array('form' => $form->createView()));
    }

    public function singleAction(Request $request)
    {

        $name = $request->query->get('name');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('XMAddressBookBundle:Contact');
        $contact = $repository->findOneBy(
            array('name' => $name)
        );
        
        return $this->render('XMAddressBookBundle::single.html.twig', array('contact' => $contact));
    }

    public function listAction()
    {
        $user = $this->getUser();
        $contacts = $user->getContacts();
        
        return $this->render('XMAddressBookBundle::list.html.twig', array('contacts' => $contacts));
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('XMAddressBookBundle:Contact')->find($id);

        if (null === $contact) {
            throw new NotFoundHttpException("Ce contact n'existe pas");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($contact);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "Le contact a bien été supprimée.");

            return $this->homeAction();
        }

        return $this->render('XMAddressBookBundle::delete.html.twig', array(
            'contact' => $contact,
            'form'   => $form->createView(),
        ));
    }
}
