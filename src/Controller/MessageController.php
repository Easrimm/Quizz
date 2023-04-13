<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message/{id}', name: 'app_message')]
    public function index(int $id, UtilisateurRepository $utilisateurRepository, MessageRepository $messageRepository, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $contact = $utilisateurRepository->findOneById($id);
        $messages = $messageRepository->findByDiscussion($user, $contact);

        if($request->get('ajax') == "envoi"){
            $message = new Message();
            $message
            ->setContenu($request->get('message'))
            ->setEnvoyeur($user)
            ->setDestinataire($contact)
            ->setDatetime(new DateTime('now'));
            $em->persist($message);
            $em->flush();

            array_push($messages, $message);

            return new JsonResponse(['content' => $this->renderView('message/_messages.html.twig', [
                'messages' => $messages
            ])]);
        }

        if($request->get('ajax') == "refresh"){
            return new JsonResponse(['content' => $this->renderView('message/_messages.html.twig', [
                'messages' => $messages
            ])]);
        }

        return $this->render('message/discussion.html.twig', [
            'user' => $user,
            'contact' => $contact,
            'messages' => $messages
        ]);
    }
}
