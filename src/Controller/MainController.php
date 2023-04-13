<?php

namespace App\Controller;

use App\Repository\BannissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    #[Route('/', name: 'app_menu')]
    public function index(BannissementRepository $br): Response
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

        return $this->render('main/index.html.twig',[
                
        ]);
    }
}
