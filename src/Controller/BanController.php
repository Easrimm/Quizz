<?php

namespace App\Controller;

use App\Repository\BannissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BanController extends AbstractController
{
    #[Route('/ban', name: 'app_ban')]
    public function index(BannissementRepository $br): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        if($br->verifBan($user)){
            $this->addFlash('success', 'Vous n\'êtes pas banni.');
            $this->redirectToRoute('app_menu');
        }

        $ban = $user->getBanRecu();

        return $this->render('admin/ban/accueilBan.html.twig', [
            'ban' => $ban,
            'user' => $user
        ]);
    }
}
