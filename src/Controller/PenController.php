<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Pen;
use App\Form\CommentType;
use App\Form\ImageType;
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
    public function show(Pen $pen, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$pen)
        {
            return $this->redirectToRoute('app_pens');
        }

        $comments = new Comment();
        $formComment = $this->createForm(CommentType::class, $comments);
        $formComment->handleRequest($request);
        if($formComment->isSubmitted() && $formComment->isValid()){
            $comments->setTime(new \DateTime());
            $comments->setContent($pen);
            $manager->persist($comments);
            $manager->flush();
            return $this->redirectToRoute('app_pen', ['id' => $pen->getId()]);
        }


        return $this->render('pen/show.html.twig', [
            'pen' => $pen,
            'formComment' => $formComment->createView(),
            'comments' => $comments,
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

    #[Route('/pen/image', name: 'app_pen_image')]
    public function addImage(Pen $pen, Request $request, EntityManagerInterface $manager): Response{
        $image = new Image();

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $image->setPen($pen);
            $manager->persist($pen);
            $manager->flush();
            return $this->redirectToRoute('app_pen_image');
        }
        return $this->render('pen/image.html.twig', [
            'pen' => $pen,
            'formImage' => $form->createView(),
        ]);
    }
}
