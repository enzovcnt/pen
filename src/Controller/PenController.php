<?php

namespace App\Controller;

use App\Entity\Pen;
use App\Form\PenType;
use App\Repository\PenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PenController extends AbstractController
{
    #[Route('/', name: 'app_pens')]
    public function index(PenRepository $repository): Response
    {
        return $this->render('pen/index.html.twig', [
            'pens' => $repository->findAll(),
        ]);
    }

    #[Route('/pen/new', name: 'app_pen_new')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {

        $pen = new Pen();
        $form = $this->createForm(PenType::class, $pen);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($pen);
            $manager->flush();
            return $this->redirectToRoute('app_pens');
        }

        return $this->render('pen/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pen/{id}', name: 'app_pen')]
    public function show(Pen $pen): Response
    {

        return $this->render('pen/show.html.twig', [
            'pen' => $pen,
        ]);
    }

    #[Route('/pen/{id}/edit', name: 'app_pen_edit')]
    public function edit(Request $request, Pen $pen, EntityManagerInterface $manager): Response{
        $form = $this->createForm(PenType::class, $pen);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($pen);
            $manager->flush();
            return $this->redirectToRoute('app_pens');
        }
        return $this->render('pen/edit.html.twig', [
            'pen' => $pen,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pen/{id}/delete', name: 'app_pen_delete')]
public function delete(Pen $pen, EntityManagerInterface $manager): Response{

        if($pen->getId() !== null){
            $manager->remove($pen);
            $manager->flush();
        }

        return $this->redirectToRoute('app_pens', ['id' => $pen->getId()]);
    }
}
