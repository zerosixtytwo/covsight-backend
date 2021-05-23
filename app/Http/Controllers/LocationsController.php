<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Locations;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Http\ResponseFactory;
use Laravel\Lumen\Routing\Controller;

class LocationsController extends Controller
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * LocationsController constructor.
     * @param ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Return all location codes and names.
     *
     * @return JsonResponse
     */
    public function serveLocations(): JsonResponse
    {
        $locations = Locations::getLocations();

        return $this->responseFactory->json($locations);
    }

    /**
     * Serve details of all countries.
     *
     * @return JsonResponse
     */
    public function serveGlobalDetails(): JsonResponse
    {
        $details = Locations::getGlobalDetails();

        return $this->responseFactory->json($details);
    }

    /**
     * Serve latest details of a specific location.
     *
     * @param $location
     * @return JsonResponse
     */
    public function serveDetails($location): JsonResponse
    {
        $details = Locations::getAllDetailsForLocation($location);
        if (!$details) {
            $ret = [
                "error"     =>  true,
                "message"   =>  "Details not found."
            ];

            return $this->responseFactory->json($ret, 404);
        }

        return $this->responseFactory->json($details);
    }

    /**
     * Serve all details of a specific location.
     *
     * @param $location
     * @return JsonResponse
     */
    public function serveHistory($location): JsonResponse
    {
        $details = Locations::getHistoryForLocation($location);
        if (!$details) {
            $ret = [
                "error"     =>  true,
                "message"   =>  "Details not found."
            ];

            return $this->responseFactory->json($ret, 404);
        }

        return $this->responseFactory->json($details);
    }
}
