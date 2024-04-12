<?php

declare(strict_types=1);

namespace Src\User\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\StringValueObject;
use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para el ValueObject UserDocumentNumber.
 */
final class UserDocumentNumber extends StringValueObject
{

	/**
     * Verifica si el valor es un string vacío o una longitud diferente a 8, y lanza una excepción.
     * 
	 * @param string $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsValid(string $value): void
	{
		if ($value === '') {
			throw new BadRequestException("The user document number is empty.");
		}
		else if (!ctype_digit($value)) {
			throw new BadRequestException("The user document number is contains letters. ($value)");
		}
		else if (strlen($value) !== 8) {
			throw new BadRequestException("The user document number must be 8 characters long. ($value)");
		}
	}
}