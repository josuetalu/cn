<?php

namespace App\Controller;

use App\Entity\Bin;
use App\Form\BinType;
use App\Repository\BinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/bin")
 */
class BinController extends AbstractController
{
    /**
     * @Route("/", name="app_bin_index", methods={"GET"})
     */
    public function index(BinRepository $binRepository): Response
    {
        return $this->render('bin/index.html.twig', [
            'bins' => $binRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_bin_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bin = new Bin();
        $form = $this->createForm(BinType::class, $bin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bin);
            $entityManager->flush();

            return $this->redirectToRoute('app_bin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bin/new.html.twig', [
            'bin' => $bin,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_bin_show", methods={"GET"})
     */
    public function show(Bin $bin): Response
    {
        return $this->render('bin/show.html.twig', [
            'bin' => $bin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_bin_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Bin $bin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BinType::class, $bin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bin/edit.html.twig', [
            'bin' => $bin,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_bin_delete", methods={"POST"})
     */
    public function delete(Request $request, Bin $bin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bin_index', [], Response::HTTP_SEE_OTHER);
    }
}
