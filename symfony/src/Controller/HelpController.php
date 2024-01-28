<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpController extends AbstractController
{
    /**
     * @Route("/help", name="app_help")
     */
    public function index(LoggerInterface $tcLogger ): Response
    {
        $tcLogger->info('w r here**');
        return $this->render('help/index.html.twig', [
            'controller_name' => 'HelpController',
        ]);
    }
}
