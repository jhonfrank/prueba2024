<?php

declare(strict_types=1);

namespace Src\Transaction\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\IntValueObject;
use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para el ValueObject TransactionPayeeUserId.
 */
final class TransactionPayeeUserId extends IntValueObject
{

	/**
     * Verifica si el valor es menor a 0 y lanza una excepción.
     * 
	 * @param int $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsValid(int $value): void
	{
		if ($value < 0) {
			throw new BadRequestException("The payee user id is negative.");
		}
	}
}