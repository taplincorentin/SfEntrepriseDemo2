<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
   /* public function index(EntityManagerInterface $entityManager): Response
    {
        $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }*/

    public function index(EntrepriseRepository $entrepriseRepository): Response{
        $entreprises = $entrepriseRepository->findBy(/*["ville" => 'Strasbourg'],*/[], ["raisonSociale" => "ASC"]);
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    
    #[Route('/entreprise/new', name: 'new_entreprise')]
    #[Route('/entreprise/{id}/edit', name: 'edit_entreprise')]
    public function new_edit(Entreprise $entreprise = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$entreprise){
            $entreprise = new Entreprise();
        }
 
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entreprise = $form->getData();

            $entityManager->persist($entreprise); //PDO prepare
            $entityManager->flush(); //PDO execute

            return $this->redirectToRoute('app_entreprise');
        }

        return $this->render('entreprise/new.html.twig', [
            'formAddEntreprise' => $form,
        ]);
    }

    #[Route('/entreprise/{id}/delete', name: 'delete_entreprise')]
    public function delete(Entreprise $entreprise, EntityManagerInterface $entityManager) {
        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute('app_entreprise');
    }

    #[Route('/entreprise/{id}', name: 'show_entreprise')]
    public function show(Entreprise $entreprise): Response {
        
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }
}
