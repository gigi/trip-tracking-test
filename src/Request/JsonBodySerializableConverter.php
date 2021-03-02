<?php

declare(strict_types=1);

namespace App\Request;

use App\Exception\ValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class JsonBodySerializableConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $body = $request->getContent();

        $obj = $this->serializer->deserialize($body, $configuration->getClass(), 'json');
        /** @var ConstraintViolationList $violationList */
        $violationList = $this->validator->validate($obj);

        if ($violationList->count() > 0) {
            throw new ValidationException((string)$violationList);
        }

        $request->attributes->set($configuration->getName(), $obj);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        $class = $configuration->getClass();
        if (!is_string($class)) {
            return false;
        }
        $implements = class_implements($class);
        if (false === $implements) {
            return false;
        }
        return in_array(JsonBodySerializableInterface::class, $implements, true);
    }
}
