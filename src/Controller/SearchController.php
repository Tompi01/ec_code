<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\BookRead;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ["POST"])]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        
        $book = $entityManager->getRepository(BookRead::class)->search($request->request->all()['value']);
        
        // print_r($book);

        $response = new Response(json_encode($book));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}