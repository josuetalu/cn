<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Delivery;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @Route("admin/delivery")
 */
class DeliveryController extends AbstractController
{
    /**
     * @Route("/", name="app_delivery_index", methods={"GET"})
     */
    public function index(DeliveryRepository $deliveryRepository): Response
    {
        return $this->render('delivery/index.html.twig', [
            'deliveries' => $deliveryRepository->findAll([], ['id'=> 'DESC']),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_delivery_new", methods={"GET", "POST"})
     */
    public function new(int $id,Request $request, EntityManagerInterface $em): Response
    {
        $order = new Order();
        $orderRepository = $em->getRepository(Order::class);
        $order = $orderRepository->find($id);
        

        $deliveryCode = "DY-".strtoupper(uniqid());
        $delivery = new Delivery();
        $delivery->setDeliveryCode($deliveryCode);
        $delivery->setDate(new \DateTime());
        $delivery->setRange1($order->getRange1());
        $delivery->setRange2($order->getRange2());
        $delivery->setCardTotal($order->getCardTotal());
        $em->persist($delivery);
        $em->flush();

        //Donner le statut verifié a la commande associé à cette livraison

        $order->setDelivered(true);
        $em->persist($order);
        $em->flush();
        

        return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/{id}", name="app_delivery_show", methods={"GET"})
     */
    public function show(Delivery $delivery): Response
    {
        return $this->render('delivery/show.html.twig', [
            'delivery' => $delivery,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_delivery_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery/edit.html.twig', [
            'delivery' => $delivery,
            'form' => $form,
        ]);
    }


    /*public function confirm(Request $request, EntityManagerInterface $em, int $id_order): Response
    {
        $order = $em->getRepository(Order::class)->find($id_order);

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        return $this->render('delivery/confirm.html.twig', [
            'oerder' => $order,
        ]);
    }*/


    /**
     * @Route("/{id}", name="app_delivery_delete", methods={"POST"})
     */
    public function delete(Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$delivery->getId(), $request->request->get('_token'))) {
            $entityManager->remove($delivery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);
    }
}
