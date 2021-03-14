<?php


namespace App\Controller;

use App\Service\ApperticeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorldController extends AbstractController
{
    /** @var ApperticeService */
    private $apperticeService;

    public function __construct(ApperticeService $apperticeService)
    {
        $this->apperticeService = $apperticeService;
    }

    /**
     * @Route("/page", name="page_show")
     */
    public function show():Response
    {

    //    $this->apperticeService->findPlace();

        // echo "<pre>";
        //     print_r($this->apperticeService->findPlace());
        // echo "</pre>";

        return $this->json($this->apperticeService->findPlace());
    }
}