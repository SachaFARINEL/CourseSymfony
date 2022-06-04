<?php

namespace App\Controller;

use App\Entity\Property;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    #[Route('/biens', name: 'property.index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $property = new Property();
        $property->setTitle('Mon premier bien')
            ->setPrice(200000)
            ->setRooms(4)
            ->setBedrooms(3)
            ->setDescription('Une petite description')
            ->setSurface(60)
            ->setFloor(4)
            ->setHeat(1)
            ->setCity('Montpellier')
            ->setAddress('15 Boulevard Gambetta')
            ->setPostalCode('34000');
        $em = $doctrine->getManager();
        $em->persist($property);
        $em->flush();
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);
    }

}
