<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Range;
use App\Entity\OrderRange;
use App\Entity\Bin;
use App\Form\OrderType;
use App\Form\ConfirmDeliveryType;
use App\Repository\OrderRepository;
use App\Repository\RangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @Route("admin/order")
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
    public function new(Request $request, EntityManagerInterface $entityManager,RangeRepository $rangeRepository): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    

            
            $bin = $order->getBin();
            $total_ordered = $order->getCardTotal();
            $response = $this->isThereSpace($bin,$total_ordered);

            dump($response);
            /*array:6 [▼
                2 => 200
                3 => 150
                4 => 250
                6 => 200
                7 => 50
                "remaining_orders" => 150
            ]*/

            // Si la commande dépasse la plage, afficher un message d'erreur
            if($response['remaining_orders'] > 0)
            {
               
                $this->addFlash('error', "La commande dépasse la plage autorisée. Excès de PAN(s).");
                    return $this->renderForm('order/new.html.twig', [
                        'order' => $order,
                        'form' => $form,
                    ]);
                
            }

            // Récupérer les informations nécessaires
            $serialNumber = $bin->getSerial();

            //Enregistrement de la commande
            $order->setOrderCode("ORD-" . strtoupper(uniqid()));
            $order->setDate(new \DateTime());
            $order->setDelivered(false);
            $order->setOrderDate(new \DateTime());
            $order->setSerial($serialNumber);
            $order->setRange1("null");
            $order->setRange2("null");
            $order->getBin()->setLastEmittedPan("null");
            $entityManager->persist($order);
            $entityManager->flush();
            //--dump($order);


            $tampon = null; 
            array_pop($response);
            foreach ($response as $id_range => $number) {
                $orderRange =  new OrderRange();
                $orderRange->setOrder($order);

                $range = $entityManager->getRepository(Range::class)->find($id_range);
                $orderRange->setRange($range);

                $orderRange->setTotal($number);

                //Generation du pan debut et pan fin
                $last_emitted_pan = $range->getLastEmittedPan();
                if ($last_emitted_pan != null) {
                    $last_emitted_pan = substr($last_emitted_pan, 6);
                    dump("xxxxxxxxxxxxxxxxxx");
                }
                else{
                   

                    $last_emitted_pan = $this->getLastEmittedPanForTheRangeBefore($range->getId(),$rangeRepository);
                    if($last_emitted_pan == null)
                    {
                        $last_emitted_pan = "0000000000";
                    }
                    else{
                        $last_emitted_pan = substr($last_emitted_pan, 6);
                    }
                
                    dump("ce que je veux voir : ".$last_emitted_pan);

                    //$last_emitted_pan = "0000000000";
                    /*if($tampon != null)
                    {
                        $last_emitted_pan = substr($tampon->getLastEmittedPan(), 6); 
                    }
                    else{
                        $last_emitted_pan = "0000000000"; 
                    }*/
                      
                }

                $start_pan =((int) $last_emitted_pan ) + 1;
                $end_pan =  ($start_pan  + $number) - 1;

                $start_pan_str = $bin->getSerial().str_pad($start_pan, 10, '0', STR_PAD_LEFT);
                $end_pan_str = $bin->getSerial().str_pad($end_pan, 10, '0', STR_PAD_LEFT);

                //On ajoute aussi le dernier PAN  a la table range
                $range->setLastEmittedPan($end_pan_str);
                $entityManager->persist($range);
                $entityManager->flush(); 
                /*************************/

                $orderRange->setStartPan( $start_pan_str);
                $orderRange->setEndPan($end_pan_str);

                $entityManager->persist($orderRange);
                $entityManager->flush(); 

               
            }
            $tampon = $range;

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

    private function isThereSpace(Bin $bin, int $total_ordered)
    {
       $output = [];
       $ranges =  $bin->getRanges();

       foreach($ranges as $range)
       {  
            //$end = (int) $range->getEnd();    
            //$start = (int) $range->getStart();
            $available = $range->getAvailableSpace();
            dump("Voici :");
            dump($available);

        
            switch (true) {
                case $total_ordered == $available:
                    $output[$range->getId()] = $total_ordered;
                    $total_ordered = 0;
                    break;
            
                case $total_ordered > $available:
                    $output[$range->getId()] = $available;
                    $total_ordered -= $available;
                    break;
            
                case $total_ordered < $available:
                    $output[$range->getId()] = $total_ordered;
                    $total_ordered = 0;
                    break;
            }


            if($total_ordered <= 0){
                break;
            }
            
       }


       $output = array_filter($output, function($value) {
        return $value !== 0;
       });
    
       $output["remaining_orders"] = $total_ordered;
       return $output;
    }



    public function getLastEmittedPanForTheRangeBefore(int $rangeId, RangeRepository $rangeRepository): ?string
    {
        // Récupère le Range avec l'ID fourni
        $currentRange = $rangeRepository->find($rangeId);
    
        // Vérifie si le Range existe
        if (!$currentRange) {
            return null;
        }
    
        // Cherche le Range précédent qui appartient au même Bin et dont la fin est avant le début du Range actuel
        $previousRange = $rangeRepository->createQueryBuilder('r')
            ->where('r.bin = :bin')
            ->andWhere('r.end < :start')
            ->setParameter('bin', $currentRange->getBin())
            ->setParameter('start', $currentRange->getStart())
            ->orderBy('r.end', 'DESC')
            ->setMaxResults(1)  // On prend le plus proche avant
            ->getQuery()
            ->getOneOrNullResult();
    
        // Vérifie si un Range précédent est trouvé et retourne son lastEmittedPan
        if ($previousRange) {
            return $previousRange->getLastEmittedPan();
        }
    
        // Retourne null si aucun Range précédent n'est trouvé
        return null;
    }
    
    
}
