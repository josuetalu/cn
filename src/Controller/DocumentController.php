<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Action;
use App\Entity\Step;
use App\Form\DocumentType;
use App\Form\ActionType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{

    private $security;

    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->entityManager =  $em;
    }


    /**
     * @Route("/", name="app_document_index", methods={"GET"})
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_document_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DocumentRepository $documentRepository): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentRepository->add($document, true);

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_document_show", methods={"GET","POST"})
     */
    public function show(int $id, Document $document, Request $request): Response
    {
        $action = new Action();
        
        // Récupération des données nécessaires
        $user = $this->security->getUser(); 
        $step = $document->getStep(); 
        $establishment = $step->getEstablishment(); 
        $user_establishment = $user->getProfile()->getEstablishment();

        // Vérifier si l'utilisateur peut agir sur ce document
        $canAct = $user_establishment === $establishment;
        
        // Création du formulaire avec les informations nécessaires
        $form = $this->createForm(ActionType::class, $action, [
            'user' => $user,
            'step' => $step,
            'establishment' => $establishment,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Process files from Dropzone if files are uploaded
            /*$uploadedFiles = $request->files->get('files', []);
            foreach ($uploadedFiles as $uploadedFile) {
                if ($uploadedFile->isValid()) {
                    $filename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                    $uploadedFile->move($this->getParameter('action_files'), $filename);
                    
                    // Créer une nouvelle entité pour le fichier (si vous avez une entité pour cela)
                    $documentFile = new DocumentFile();
                    $documentFile->setFilename($filename);
                    $documentFile->setOriginalFilename($uploadedFile->getClientOriginalName());
                    $documentFile->setDocument($document);
                    $this->entityManager->persist($documentFile);
                }
            }*/

            // Si la soumission est pour valider le document
            if ($request->request->has('submit_validate')) {
                if ($form->isValid()) {
                    $action->setDocument($document);
                    $action->setUser($user);
                    $action->setStep($step);
                    $action->setEstablishment($establishment);
                    $action->setCreatedAt(new \DateTimeImmutable());
                    $action->setValidated(true);

                    $this->entityManager->persist($action);
                    $this->entityManager->flush();

                    // Passer à l'étape suivante
                    $nextStep = $this->getNextStep($step);
                    if ($nextStep) {
                        $document->setStep($nextStep);
                        $document->setIsBack(false);
                    } else {
                        throw new \Exception("Aucune étape trouvée pour la prochaine position.");
                        $this->addFlash('step_message', 'Le document a été validé avec succès');
                    }
                    $this->entityManager->persist($document);
                    $this->entityManager->flush();

                    // Redirection ou message de succès
                    return $this->redirectToRoute('app_document_show');
                } else {
                    // Gestion des erreurs de validation
                    $this->addFlash('error', 'Le formulaire contient des erreurs.');
                }
            } elseif ($request->request->has('submit_resend')) {
                // Processus pour refuser le document (step précédent)
                $action->setDocument($document);
                $action->setUser($user);
                $action->setStep($step);
                $action->setEstablishment($establishment);
                $action->setCreatedAt(new \DateTimeImmutable());
                $action->setValidated(false);

                $this->entityManager->persist($action);
                $this->entityManager->flush();

                // Revenir à l'étape précédente
                $previousStep = $this->getPreviousStep($step);
                if ($previousStep) {
                    $document->setStep($previousStep);
                    $document->setIsBack(true);
                } else {
                    throw new \Exception("Aucune étape trouvée pour la position précédente.");
                }
                $this->entityManager->persist($document);
                $this->entityManager->flush();

                // Redirection ou message de refus
                return $this->redirectToRoute('app_document_show');
            }
        }


        $actions = $document->getActions();
        return $this->renderForm('document/show.html.twig', [
            'document' => $document,
            'form' => $form,
            'canAct' => $canAct,
            'actions' => $actions
        ]);
    }

    // Méthode utilitaire pour trouver la prochaine étape
    private function getNextStep(Step $currentStep): ?Step
    {
        $new_position = (int) $currentStep->getPosition() + 1;
        $stepRepository = $this->entityManager->getRepository(Step::class);
        $nextStep = $stepRepository->findOneBy(['position' => $new_position]);

        return $nextStep;
    }

    // Méthode utilitaire pour trouver l'étape précédente
    private function getPreviousStep(Step $currentStep): ?Step
    {
        $new_position = (int) $currentStep->getPosition() - 1;
        $stepRepository = $this->entityManager->getRepository(Step::class);
        $previousStep = $stepRepository->findOneBy(['position' => $new_position]);

        return $previousStep;
    }


    /**
     * @Route("/{id}/edit", name="app_document_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Document $document, DocumentRepository $documentRepository): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentRepository->add($document, true);

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }




























    /**
     * @Route("/{id}", name="app_document_delete", methods={"POST"})
     */
    public function delete(Request $request, Document $document, DocumentRepository $documentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $documentRepository->remove($document, true);
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }




}
