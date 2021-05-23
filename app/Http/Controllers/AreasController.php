<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Areas;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Http\ResponseFactory;
use Laravel\Lumen\Routing\Controller;

class AreasController extends Controller
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * AreasController constructor.
     * @param ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function serveAreas(): JsonResponse
    {
        $areas = Areas::getAreas();

        return $this->responseFactory->json($areas);
    }
}
