<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Release;
use App\Form\ReleaseType;
use App\Repository\ReleaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReleaseController extends AbstractController
{
    #[Route('/release', name: 'app_release_index', methods: ['GET'])]
    public function index(ReleaseRepository $releaseRepository): Response
    {
        return $this->render('release/index.html.twig', [
            'releases' => $releaseRepository->findAll(),
        ]);
    }

    #[Route('/artist/{id}/release/new', name: 'app_artist_release_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Artist $artist): Response
    {
        $release = new Release();
        $release->setArtist($artist);
        $form = $this->createForm(ReleaseType::class, $release);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($release);
            $entityManager->flush();

            return $this->redirectToRoute('app_release_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('release/new.html.twig', [
            'release' => $release,
            'form' => $form,
        ]);
    }

    #[Route('/release/{id}', name: 'app_release_show', methods: ['GET'])]
    public function show(Release $release): Response
    {
        return $this->render('release/show.html.twig', [
            'release' => $release,
        ]);
    }

    #[Route('/release/{id}/edit', name: 'app_release_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Release $release, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReleaseType::class, $release);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_release_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('release/edit.html.twig', [
            'release' => $release,
            'form' => $form,
        ]);
    }

    #[Route('/release/{id}', name: 'app_release_delete', methods: ['POST'])]
    public function delete(Request $request, Release $release, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$release->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($release);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_release_index', [], Response::HTTP_SEE_OTHER);
    }
}
