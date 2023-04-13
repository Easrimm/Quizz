<?php

namespace App\Controller;

use App\Entity\Bannissement;
use App\Form\BannissementFormType;
use App\Repository\BannissementRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(BannissementRepository $br, Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        if($user->getBanRecu()){
            if(!($br->verifBan($user))){
                return $this->redirectToRoute('app_ban');
            }
        }

        if(!($this->isGranted('ROLE_ADMIN'))){
            $this->addFlash('danger', 'Vous n\'êtes pas administrateur');
            return $this->redirectToRoute('app_menu');
        }

        $listNotBan = null;

        if($request->get('ajax') == 'ban'){
            $listNotBan = $utilisateurRepository->searchByNotBan($request->get('pseudo'));

            return new JsonResponse(['content' => $this->renderView('admin/_listNotBan.html.twig', [
                'listNotBan' => $listNotBan
            ])]);
        }

        return $this->render('admin/index.html.twig', [
            'listNotBan' => $listNotBan
        ]);
    }

    #[Route('/admin/bannir/{pseudo}', name: 'app_bannir')]
    public function bannir(Request $request, EntityManagerInterface $em, BannissementRepository $br, String $pseudo, UtilisateurRepository $utilisateurRepository){
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        if($user->getBanRecu()){
            if(!($br->verifBan($user))){
                return $this->redirectToRoute('app_ban');
            }
        }

        if(!($this->isGranted('ROLE_ADMIN'))){
            $this->addFlash('danger', 'Vous n\'êtes pas administrateur');
            return $this->redirectToRoute('app_menu');
        }

        $banni = $utilisateurRepository->findOneByPseudo($pseudo);

        $ban = new Bannissement();
        $ban->setBanni($banni);
        $ban->setBanneur($user);
        $form = $this->createForm(BannissementFormType::class, $ban);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $banni->setBanRecu($ban);
            $em->persist($banni);
            $em->flush();

            $this->addFlash('success', 'Vous avez banni ' . $pseudo);
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/ban/formBan.html.twig', [
            'pseudo' => $pseudo,
            'banForm' => $form->createView()
        ]);
    }

    #[Route('/admin/bannis', name: 'app_bannis')]
    public function currentBan(UtilisateurRepository $ur){
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        if($user->getBanRecu()){
            if(!($br->verifBan($user))){
                return $this->redirectToRoute('app_ban');
            }
        }

        if(!($this->isGranted('ROLE_ADMIN'))){
            $this->addFlash('danger', 'Vous n\'êtes pas administrateur');
            return $this->redirectToRoute('app_menu');
        }

        $bannis = $ur->searchCurrentBan();

        return $this->render('admin/ban/listOfBanned.html.twig', [
            'bannis' => $bannis
        ]);
    }
}
