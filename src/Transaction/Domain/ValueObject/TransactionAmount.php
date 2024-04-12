<?php

declare(strict_types=1);

namespace Src\Transaction\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\FloatValueObject;
use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para el ValueObject TransactionAmount.
 */
final class TransactionAmount extends FloatValueObject
{

	/**
     * Verifica si el valor es menor a 0 y lanza una excepción.
     * 
	 * @param float $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsValid(float $value): void
	{
		if ($value< 0) {
			throw new BadRequestException("The transaction amount is negative.");
		}
	}
}