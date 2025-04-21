<?php

namespace App\Controller;

use App\Entity\Material;
use App\Form\MaterialType;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MaterialController extends AbstractController
{
    #[Route('/material', name: 'app_material')]
    public function index(MaterialRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {

        $material = new Material();
        $formMat = $this->createForm(MaterialType::class, $material);
        $formMat->handleRequest($request);
        if ($formMat->isSubmitted() && $formMat->isValid()) {
            $manager->persist($material);
            $manager->flush();
            return $this->redirectToRoute('app_material');
        }

        return $this->render('material/index.html.twig', [
            'materials' => $repository->findAll(),
            'formMat' => $formMat->createView(),
        ]);
    }
}
