<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\AuthException;
use App\Exception\TripException;
use App\Request\CreateTripRequest;
use App\Request\SearchTripRequest;
use App\Service\TripService;
use DateTime;
use DateTimeInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security as SecurityComponent;

/**
 * @OA\Tag(name="Trip")
 * @Security(name="Bearer")
 */
class TripController
{
    private TripService $tripService;
    private SecurityComponent $security;

    public function __construct(SecurityComponent $security, TripService $tripService)
    {
        $this->tripService = $tripService;
        $this->security = $security;
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
     *      name="start",
     *      in="query",
     *      description="Range start",
     *      required=false,
     *      @OA\Schema(
     *          type="string",
     *          format="date"
     *      )
     *  )
     * @OA\Parameter(
     *      name="end",
     *      in="query",
     *      description="Range end",
     *      required=false,
     *      @OA\Schema(
     *          type="string",
     *          format="date"
     *      )
     *  )
     * @OA\Parameter(
     *      name="country",
     *      in="query",
     *      description="3 letters country code ",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *  )
     *
     * @param Request $request
     * @param SecurityComponent $security
     * @return Response
     * @throws AuthException
     */
    public function list(
        Request $request,
        SecurityComponent $security
    ): Response {

        $startDate = $request->query->has('start')
            ? DateTime::createFromFormat('Y-m-d', $request->query->get('start'))
            : null;

        $endDate = $request->query->has('end')
            ? DateTime::createFromFormat('Y-m-d', $request->query->get('end'))
            : null;

        $trips = $this->tripService->search(
            $this->getUser(),
            $request->query->get('country'),
            $startDate ?? null,
            $endDate ?? null
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
     * @throws AuthException
     */
    public function add(CreateTripRequest $request, SecurityComponent $security): Response
    {
        $trip = $this->tripService->addTrip(
            $this->getUser(),
            $request->getCountryCode(),
            $request->getStartDate(),
            $request->getEndDate(),
            $request->getNotes()
        );

        return new JsonResponse($trip);
    }

    /**
     * Update trip
     * @param int $id
     * @return Response
     */
    public function update(int $id): Response
    {
        // TODO implement
        return new JsonResponse('Not implemented yet');
    }

    /**
     * Get user's trip by id
     *
     * @OA\Response(
     *     response=200,
     *     description="If trip found",
     *     @OA\JsonContent(
     *        ref=@Model(type=App\Entity\Trip::class)
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Trip not found",
     * )
     * @param int $id
     * @return Response
     * @throws TripException
     * @throws AuthException
     */
    public function get(int $id): Response
    {
        $trip = $this->tripService->getOne($this->getUser(), $id);
        return new JsonResponse($trip);
    }

    /**
     * Delete trip
     *
     * @OA\Response(
     *     response=404,
     *     description="Trip not found",
     * )
     * @OA\Response(
     *     response=200,
     *     description="If trip found"
     * )
     * @param int $id
     * @return Response
     * @throws AuthException
     * @throws TripException
     */
    public function delete(int $id): Response
    {
        $this->tripService->delete($this->getUser(), $id);
        return new JsonResponse();
    }

    /**
     * @return User
     * @throws AuthException
     */
    private function getUser(): User
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthException('User must be instance of App\Entity\User');
        }

        return $user;
    }
}
