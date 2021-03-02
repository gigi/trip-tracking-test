<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\TripService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(name="Trip")
 * @Security(name="Bearer")
 */
class TripController
{
    private TripService $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * List user's trips
     */
    public function list(): Response
    {
        return new JsonResponse('list');
    }

    /**
     * Add trip
     */
    public function add(): Response
    {
        return new JsonResponse('add');
    }

    /**
     * Update trip
     */
    public function update(): Response
    {
        return new JsonResponse('update');
    }

    /**
     * Delete trip
     */
    public function delete(): Response
    {
        return new JsonResponse('delete');
    }
}
