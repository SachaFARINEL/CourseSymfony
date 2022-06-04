<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private PropertyRepository $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Response
     */
    #[Route('/admin', name: 'admin.property.index')]
    public function index(): Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/index.html.twig', compact('properties'));
    }

    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/admin/property/create', name: 'admin.property.new')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($property);
            $entityManager->flush();
            $this->addFlash('success', 'Bien crée avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Property $property
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/admin/property/{id}', name: 'admin.property.edit', methods: "GET|POST")]
    public function edit(Property $property, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Property $property
     * @param ManagerRegistry $doctrine
     */
    #[Route('/admin/property/{id}', name: 'admin.property.delete', methods: "DELETE")]
    public function delete(Property $property, ManagerRegistry $doctrine, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($property);
            $entityManager->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.property.index');
    }

}
