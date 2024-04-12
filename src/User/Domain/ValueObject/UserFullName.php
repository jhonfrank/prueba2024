<?php

declare(strict_types=1);

namespace Src\User\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\StringValueObject;
use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para el ValueObject UserFullname.
 */
final class UserFullname extends StringValueObject
{

	/**
     * Verifica si el valor es un string vacío y lanza una excepción.
     * 
	 * @param string $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsValid(string $value): void
	{
		if ($value === '') {
			throw new BadRequestException("The user full name is empty.");
		}
	}
}