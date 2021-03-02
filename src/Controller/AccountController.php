<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Login;
use App\Exception\RegistrationException;
use App\Request\RegistrationRequestType;
use App\Service\AccountService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security as SecurityComponent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @OA\Tag(name="Account")
 */
class AccountController
{
    private AccountService $service;

    public function __construct(AccountService $service)
    {
        $this->service = $service;
    }

    /**
     * Register user
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns generated jwt token if registered succesfully",
     *     @OA\JsonContent(
     *        ref=@Model(type=App\Entity\Login::class)
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Invalid request",
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *        ref=@Model(type=RegistrationRequestType::class)
     *     )
     * )
     * @throws RegistrationException
     */
    public function register(
        RegistrationRequestType $registration,
        JWTTokenManagerInterface $jwtManager
    ): Response {
        $user = $this->service->create(
            $registration->getUsername(),
            $registration->getEmail(),
            $registration->getPassword()
        );

        $login = new Login($jwtManager->create($user));

        return new JsonResponse($login);
    }

    /**
     * Update account
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns if update success",
     * )
     * @OA\Response(
     *     response=400,
     *     description="Invalid request",
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *        ref=@Model(type=RegistrationRequestType::class)
     *     )
     * )
     *
     * @Security(name="Bearer")
     */
    public function update(RegistrationRequestType $registration, SecurityComponent $security): Response
    {
        /** @var UserInterface $user */
        $user = $security->getUser();
        $this->service->updateAccount($user, $registration);

        return new JsonResponse();
    }

    /**
     * Delete account
     *
     * @Security(name="Bearer")
     */
    public function delete(SecurityComponent $security): Response
    {
        /** @var UserInterface $user */
        $user = $security->getUser();
        $this->service->deleteAccount($user);

        return new JsonResponse();
    }
}
