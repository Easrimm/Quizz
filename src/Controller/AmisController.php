<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AmisController extends AbstractController
{
    #[Route('/contacts', name: 'app_amis')]
    public function index(UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->getUser()){
            $amis = ($this->getUser())->getAmis();

            //On vérifie si on a une requête AJAX et on exécute notre requête SQL
            $ajoutAmis = null;
            if($request->get('ajax') == 'recherche' && $request->get('pseudo') != null){
                $ajoutAmis = $utilisateurRepository->findBySearchFriend($this->getUser(), $request->get('pseudo'));
                
                return new JsonResponse(['content' => $this->renderView('amis/_ajoutAmis.html.twig', [
                    'ajoutAmis' => $ajoutAmis
                ])]);
            }

            if($request->get('ajax') == 'ajoutami'){
                $ami = $utilisateurRepository->findOneByPseudo($request->get('pseudo'));
                $user = $this->getUser();
                $user->addAmi($ami);
                $amis = $user->getAmis();
                $entityManager->persist($user);
                $entityManager->flush();

                return new JsonResponse(['content' => $this->renderView('amis/_listeAmis.html.twig', [
                    'amis' => $amis,
                ])]);
            }

            if($request->get('ajax') == 'suppressionami'){
                $ami = $utilisateurRepository->findOneByPseudo($request->get('pseudo'));
                $user = $this->getUser();
                $user->removeAmi($ami);
                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->render('amis/liste.html.twig', [
                'amis' => $amis,
                'ajoutAmis' => $ajoutAmis
            ]);
        }
        else{
            $this->addFlash('danger','Vous devez être connecté pour accèder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    }
}
