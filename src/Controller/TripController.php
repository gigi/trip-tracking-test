<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\TripException;
use App\Request\CreateTripRequest;
use App\Request\SearchTripRequest;
use App\Service\TripService;
use DateTimeInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security as SecurityComponent;

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
     * List user's filtered trips
     *
     * @OA\Response(
     *     response=200,
     *     description="Filtered trips",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\Trip::class))
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Invalid request",
     * )
     * @OA\Parameter(
     *      name="dateStart",
     *      in="query",
     *      description="Range start",
     *      required=false,
     *      @OA\Schema(
     *          type="string",
     *          format="date"
     *      )
     *  )
     *
     * @param Request $request
     * @param SecurityComponent $security
     * @param DateTimeInterface|null $startDate
     * @param DateTimeInterface|null $endDate
     * @return Response
     */
    public function list(
        Request $request,
        SecurityComponent $security,
        ?DateTimeInterface $startDate,
        ?DateTimeInterface $endDate
    ): Response {
        /** @var User $user */
        $user = $security->getUser();
        $trips = $this->tripService->search(
            $user,
            $request->query->get('countryCode'),
            $startDate,
            $endDate
        );

        return new JsonResponse($trips);
    }

    /**
     * Add trip
     *
     * @OA\Response(
     *     response=200,
     *     description="If trip added",
     *     @OA\JsonContent(
     *        ref=@Model(type=App\Entity\Trip::class)
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Invalid request",
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *        ref=@Model(type=App\Request\CreateTripRequest::class)
     *     )
     * )
     * @param CreateTripRequest $request
     * @param SecurityComponent $security
     * @return Response
     * @throws TripException
     */
    public function add(CreateTripRequest $request, SecurityComponent $security): Response
    {
        /** @var User $user */
        $user = $security->getUser();
        $trip = $this->tripService->addTrip(
            $user,
            $request->getCountryCode(),
            $request->getStartDate(),
            $request->getEndDate(),
            $request->getNotes()
        );

        return new JsonResponse($trip);
    }

    /**
     * Update trip
     */
    public function update(): Response
    {
        // TODO implement
        return new JsonResponse('update');
    }

    /**
     * Delete trip
     */
    public function delete(): Response
    {
        // TODO implement
        return new JsonResponse('delete');
    }
}
