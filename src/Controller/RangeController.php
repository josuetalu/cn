<?php

namespace App\Controller;

use App\Entity\Range;
use App\Entity\Bin;
use App\Form\BinRangeType;
use App\Repository\RangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("admin/range")
 */

class RangeController extends AbstractController
{
    /**
     * @Route("/visualize/{binid}", name="app_range_index", methods={"GET"})
     */
    public function view(int $binid,RangeRepository $rangeRepository,EntityManagerInterface $em): Response
    {
        $bin = $em->getRepository(Bin::class)->find($binid);
        $ranges = $bin->getRanges();
        dump($ranges);
        return $this->render('range/index.html.twig', [
            'ranges' => $ranges,
            'bin' => $bin,
        ]);
    }

    /**
     * @Route("/new/{id_bin}", name="app_range_new", methods={"GET", "POST"})
     */
    public function new(int $id_bin, Request $request, RangeRepository $rangeRepository,EntityManagerInterface $em): Response
    {

        $bin = $em->getRepository(Bin::class)->find($id_bin);

        $ranges = $bin->getRanges();
        $last_range = null;
        if(!$ranges->isEmpty())
        {
            $last_range = $ranges->last();
        }
        $startOfNextRange = $last_range ? ((int) $last_range->getEnd()) + 1 : null;
        if ($startOfNextRange !== null) {
            $startOfNextRange = str_pad((string)$startOfNextRange, 10, '0', STR_PAD_LEFT);
        }
        else{
            $startOfNextRange = "0000000001";
        }


            
        $range = new Range();
        $form = $this->createForm(BinRangeType::class,$range, [
            'bin' => $bin, // Vous pouvez aussi passer d'autres propriétés si nécessaire
            'startOfNextRange' => $startOfNextRange
        ]);

       

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);
            $range->setBin($bin);
            $range->setCreatedAt(new \DateTimeImmutable);
            $rangeRepository->add($range, true);

            return $this->redirectToRoute('app_range_index', ['binid' => $bin->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('range/new.html.twig', [
            'range' => $range,
            'form' => $form,
            'bin' => $bin
        ]);
    }

    /**
     * @Route("/{id}", name="app_range_show", methods={"GET"})
     */
    public function show(Range $range): Response
    {
        return $this->render('range/show.html.twig', [
            'range' => $range,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_range_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Range $range, RangeRepository $rangeRepository): Response
    {
        $form = $this->createForm(RangeType::class, $range);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rangeRepository->add($range, true);

            return $this->redirectToRoute('app_range_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('range/edit.html.twig', [
            'range' => $range,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_range_delete", methods={"POST"})
     */
    public function delete(Request $request, Range $range, RangeRepository $rangeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$range->getId(), $request->request->get('_token'))) {
            $rangeRepository->remove($range, true);
        }

        return $this->redirectToRoute('app_range_index', [], Response::HTTP_SEE_OTHER);
    }
}
