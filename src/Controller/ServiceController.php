<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
    #[Route('/showService/{name}', name: 'app_showService')]
    public function showservice($name): Response
    {
        return $this->render('service/showService.html.twig', [  
            'name'=> $name,
        ]);        
}   
    #[Route('/go', name:'app_goToIndex')]
    public function goToIndex(): Response  {
        return $this->redirectToRoute('app_home');
}
}
