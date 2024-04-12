<?php

declare(strict_types=1);

namespace Src\User\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\StringValueObject;
use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para el ValueObject UserEmail.
 */
final class UserEmail extends StringValueObject
{

	/**
     * Verifica si el valor es un string vacío o no cumple con la estructura de un email valido, y lanza una excepción.
     * 
	 * @param string $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsValid(string $value): void
	{
		if ($value === '') {
			throw new BadRequestException("The user email is empty.");
		}
		else if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/", $value)) {
			throw new BadRequestException("The user email is not valid. ($value)");
		}
	}
}