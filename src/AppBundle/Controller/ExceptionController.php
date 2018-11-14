<?php

namespace AppBundle\Controller;

use AppBundle\Exception\ValidationException;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends Controller {
    
    // FOS RestBundle
    use ControllerTrait;

    /**
     * 
     * @param Request $request
     * @param type $exception
     * @param DebugLoggerInterface $logger
     * @return type
     */
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null) {

        if ($exception instanceof ValidationException) {
            
            return $this->getView($exception->getStatusCode(), json_decode($exception->getMessage(), true));
        }
        
        if ($exception instanceof HttpException) {

            return $this->getView($exception->getStatusCode(), $exception->getMessage());
        }
        
        return $this->getView(null, "Unexpected error ocurred");
    }

    /**
     * 
     * @param int|null $statusCode
     * @param type $message
     * @return View
     */
    private function getView(?int $statusCode, $message): View {

        $data = [
            "code" => $statusCode ?? 500, //los dos ?? determinan el valor por defecto si la variable no tiene valor
            "message" => $message
        ];
        
        return $this->view($data, $statusCode ?? 500);
    }

}
