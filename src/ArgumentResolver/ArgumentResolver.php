<?php

namespace App\ArgumentResolver;

use App\Validation\AbstractValidator;
use Exception;
use Generator;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class ArgumentResolver implements ValueResolverInterface
{
    private ArgumentMetadata $argument;

    private Request $request;

    private ?AbstractValidator $validator;

    public function __construct(?AbstractValidator $validator = null)
    {
        $this->validator = $validator;
    }

    /**
     * @throws Exception
     */
    protected function validate(array $fields, ?AbstractValidator $validator = null): void
    {
        $validator = is_null($validator) ? $this->validator : $validator;

        if (is_null($validator)) {
            throw new Exception(
                'Отсутствует валидатор. Требуется явная передача в метод или конструктор.',
            );
        }

        /**
         * @var array<string> $errors
         */
        $errors = $validator->validate(requestFields: $fields);

        if (!empty($errors)) {
            $this->throwValidationError(errors: $errors);
        }
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $this->argument = $argument;
        $this->request = $request;

        if (!$this->supports($argument)) return [];

        return $this->handle();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getArgument(): ArgumentMetadata
    {
        return $this->argument;
    }

    public function getJson(): array
    {
        $content = $this->getRequest()->getContent();

        if (empty($content)) {
            return [];
        }

        try {
            return (array) json_decode(
                json: $content,
                associative: true,
                flags: JSON_THROW_ON_ERROR,
            );
        } catch (JsonException $exception) {
            throw new BadRequestHttpException(
                message: 'Недопустимый JSON: ' . $exception->getMessage(),
            );
        }
    }

    public function supports(ArgumentMetadata $argument): bool
    {
        return $this->getSupportsClass() === $argument->getType();
    }

    /**
     * @throws Exception
     */
    protected function throwValidationError(array $errors): void
    {
        $message = 'Ошибки валидации:' . implode('; ', $errors);

        throw new Exception(
            message: $message,
            code: Response::HTTP_BAD_REQUEST,
        );
    }

    abstract public function handle(): Generator;

    abstract public function getSupportsClass(): string;
}
