<?php

declare(strict_types=1);

namespace Src\User\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\DateTimeValueObject;
use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para el ValueObject UserCreateAt.
 */
final class UserCreatedAt extends DateTimeValueObject
{
}