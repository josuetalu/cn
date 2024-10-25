<?php
// src/Controller/FileController.php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use App\Entity\Delivery;

class FileController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/delivery_file/{id_delivery}", name="get_delivery_file")
     */
    public function getDeliveryFile(int $id_delivery): Response
    {
        $delivery = $this->entityManager->getRepository(Delivery::class)->find($id_delivery);

        if (!$delivery) {
            throw new NotFoundHttpException("L'enregistrement avec cet ID n'existe pas.");
        }

        $filename = $delivery->getSupportingDoc();
        $filePath = $this->getParameter('uploads_directory') . '/' . $filename;

        if (!file_exists($filePath)) {
            throw new FileNotFoundException("Le fichier n'existe pas." . $filePath);
        }

        return new BinaryFileResponse($filePath);
    }
}