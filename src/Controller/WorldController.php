<?php


namespace App\Controller;


use App\Service\ApperticeService;
use App\Service\TeacherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorldController extends AbstractController
{
    /** @var ApperticeService */
    private $apperticeService;

    /** @var TeacherService */
    private $teacherService;

    public function __construct(ApperticeService $apperticeService, TeacherService $teacherService)
    {
        $this->apperticeService = $apperticeService;
        $this->teacherService = $teacherService;
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

//        return $this->apperticeService->findPlace();

        $a = $this->teacherService->getTeachers();

//        echo "<pre>";
//        print_r($a);
//        echo "</pre>";

        return new Response(json_encode($a));
    }
}