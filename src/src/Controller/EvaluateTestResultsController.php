<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//@stub
class EvaluateTestResultsController extends AbstractController
{
    #[Route('/evaluate/test/results', name: 'app_evaluate_test_results')]
    public function index(): Response
    {
        //@todo show form to upload an excel file
        return $this->render('evaluate_test_results/index.html.twig', [
            'controller_name' => 'EvaluateTestResultsController',
        ]);
    }
}
