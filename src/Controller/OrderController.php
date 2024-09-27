<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Bin;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="app_order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findUndeliveredOrders(),
        ]);
    }

    /**
     * @Route("/new", name="app_order_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            // Récupérer les informations nécessaires
            $lastEmittedPan = $order->getBin()->getLastEmittedPan();
            $totalCard = $order->getCardTotal();
            $lastPanAuthorized = $order->getBin()->getRange2(); // On suppose que cette valeur est stockée quelque part
            $serialNumber = $order->getBin()->getSerial();
    
            // Vérifier si la plage autorise cette commande
            list($isAvailable, $excess) = $this->isAvaibleInRange($lastEmittedPan, $totalCard, $lastPanAuthorized);
    
            if (!$isAvailable) {
                // Si la commande dépasse la plage, afficher un message d'erreur
                $this->addFlash('error', "La commande dépasse la plage autorisée. Excès de {$excess} PAN(s).");
                
                return $this->renderForm('order/new.html.twig', [
                    'order' => $order,
                    'form' => $form,
                ]);
            }
    
            // Si tout est en ordre, continuer l'enregistrement
            $order->setOrderCode("ORD-" . strtoupper(uniqid()));
            $order->setDate(new \DateTime());
            $order->setDelivered(false);
            $order->setOrderDate(new \DateTime());
            $order->setSerial($serialNumber);
    
            // Générer les nouveaux PANs
            $response = $this->generatePanRange($lastEmittedPan, $totalCard, $serialNumber);
            $order->setRange1($response['firstNewPan']);
            $order->setRange2($response['lastNewPan']);
    
            // Mettre à jour le dernier PAN émis dans l'entité Bin
            $order->getBin()->setLastEmittedPan($response['lastNewPan']);
    
            // Persister la commande
            $entityManager->persist($order);
            $entityManager->flush();
    
            // Redirection après succès
            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_order_show", methods={"GET"})
     */
    public function show(Order $order): Response
    {   
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }


    /**
     * @Route("/confirm/{id}", name="app_order_confirmation", methods={"GET"})
     */
    public function confirm(Order $order): Response
    {
        return $this->render('order/confirm.html.twig', [
            'order' => $order,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_order_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_order_delete", methods={"POST"})
     */
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }


    private function generatePanRange($lastIssuedPan, $numberOfNewPans,$serialNumber) {
        // Extraire le numéro de série des 6 premiers chiffres du dernier PAN émis
        //$serialNumber = substr($lastIssuedPan, 0, 6);
        
        // Extraire le compteur actuel (les 10 derniers chiffres du dernier PAN émis)
        $currentCounter = $lastIssuedPan === null ? 0 : (int)substr($lastIssuedPan, 6);
        
        // Calculer le premier et le dernier nouveau PAN
        $firstNewCounter = $currentCounter + 1;
        $lastNewCounter = $currentCounter + $numberOfNewPans;

        // Générer les PANs en concaténant le numéro de série et les compteurs formatés sur 10 chiffres
        $firstNewPan = $serialNumber . str_pad($firstNewCounter, 10, '0', STR_PAD_LEFT);
        $lastNewPan = $serialNumber . str_pad($lastNewCounter, 10, '0', STR_PAD_LEFT);
        
        return [
            'firstNewPan' => $firstNewPan,
            'lastNewPan' => $lastNewPan
        ];
    }

    private function isAvaibleInRange($lastEmittedPan, $numberOfPanOrdered, $lastPanAuthorized)
    {
        // Enlever les 6 premiers chiffres (numéros de série)
        $lastEmittedPan = $lastEmittedPan !== null ? substr($lastEmittedPan, 6) : '0000000000';
        //$lastPanAuthorized = substr($lastPanAuthorized, 6);
    
        // Convertir en entier après avoir enlevé les numéros de série
        $lastEmittedPan = (int) $lastEmittedPan;
        $lastPanAuthorized = (int) $lastPanAuthorized;
    
        // Calculer l'espace disponible
        $spaceAllowed = $lastPanAuthorized - $lastEmittedPan;
    
        // Vérifier si le nombre de PANs demandés dépasse l'espace disponible
        if ($numberOfPanOrdered > $spaceAllowed) {
            // Calculer le dépassement
            $excess = $numberOfPanOrdered - $spaceAllowed;
            return [false, $excess]; // Retourne false et le dépassement
        }
        
        // Si tout est correct, retourne true avec un dépassement de 0
        return [true, 0];
    }
    
}
