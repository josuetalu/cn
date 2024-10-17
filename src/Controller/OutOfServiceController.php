<?php

namespace App\Controller;

use App\Entity\OutOfService;
use App\Entity\Delivery;
use App\Form\OutOfServiceType;
use App\Repository\OutOfServiceRepository;
use App\Repository\DeliveryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("admin/outofservice")
 */
class OutOfServiceController extends AbstractController
{
    /**
     * @Route("/", name="app_out_of_service_index", methods={"GET"})
     */
    public function index(OutOfServiceRepository $outOfServiceRepository): Response
    {
        return $this->render('out_of_service/index.html.twig', [
            'out_of_services' => $outOfServiceRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_out_of_service_new", methods={"GET", "POST"})
     */
    public function add(int $id, Request $request, OutOfServiceRepository $outOfServiceRepository,DeliveryRepository $deliveryRepository): Response
    {
        
        /*if($delivery)
        {

            $outOfService->setDeliveryCode($delivery->getDeliveryCode());
            $outOfService->setDeliveryDate($delivery->getDeliveryDate());
            $outOfService->setRange1($delivery->getRange1());
            $outOfService->setRange2($delivery->getRange2());
            $outOfService->setTotalCard($delivery->getTotalCard());
            $outOfService->setReason($delivery->getReason());
            $outOfService->setLockDate(N);
            $outOfService->setDelivery($delivery);
        }*/
       
        $outOfService = new OutOfService();
        $delivery = $deliveryRepository->find($id);
        $form = $this->createForm(OutOfServiceType::class, $outOfService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $outOfService->setDeliveryCode($delivery->getDeliveryCode());
            $outOfService->setDeliveryDate($delivery->getDate());
            $outOfService->setRange1($delivery->getRange1());
            $outOfService->setRange2($delivery->getRange2());
            $outOfService->setTotalCard($delivery->getCardTotal());
            $outOfService->setReason("mmmmmmmmmmm");
            $outOfService->setLockDate(new \DateTime());
            $outOfService->setDelivery($delivery);
        
           

            $delivery->setIsActive(false);


            $outOfServiceRepository->add($outOfService, true);
            $deliveryRepository->add($delivery, true); 

            return $this->redirectToRoute('app_out_of_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('out_of_service/new.html.twig', [
            'out_of_service' => $outOfService,
            'form' => $form,
            'delivery' => $delivery
        ]);
    }

    /**
     * @Route("/{id}", name="app_out_of_service_show", methods={"GET"})
     */
    public function show(OutOfService $outOfService): Response
    {
        return $this->render('out_of_service/show.html.twig', [
            'out_of_service' => $outOfService,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_out_of_service_edit", methods={"GET", "POST"})
     */
    /*
    public function edit(Request $request, OutOfService $outOfService, OutOfServiceRepository $outOfServiceRepository): Response
    {
        $form = $this->createForm(OutOfServiceType::class, $outOfService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $outOfServiceRepository->add($outOfService, true);

            return $this->redirectToRoute('app_out_of_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('out_of_service/edit.html.twig', [
            'out_of_service' => $outOfService,
            'form' => $form,
        ]);
    }*/

    /**
     * @Route("/{id}", name="app_out_of_service_delete", methods={"POST"})
     */
    /*
    public function delete(Request $request, OutOfService $outOfService, OutOfServiceRepository $outOfServiceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$outOfService->getId(), $request->request->get('_token'))) {
            $outOfServiceRepository->remove($outOfService, true);
        }

        return $this->redirectToRoute('app_out_of_service_index', [], Response::HTTP_SEE_OTHER);
    }
    */
}
