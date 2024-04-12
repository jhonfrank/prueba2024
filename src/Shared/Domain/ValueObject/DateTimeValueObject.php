<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use DateTime;
use Src\Shared\Domain\Exception\BadRequestException;

abstract class DateTimeValueObject
{
	const DATE_TIME_FORMAT = "Y-m-d H:i:s";

    /**
     * @var DateTime
     */
    private DateTime $value;

	/**
     * Constructor de la clase, se verifica que sea un valor definido y un valor valido.
     * 
	 * @param DateTime $_value
	 */
    public function __construct(DateTime $_value)
	{
		$this->ensureValueIsDefined($_value);

        $this->setValue($_value);
	}
    
	/**
     * Verifica que el valor sea definido, en caso contrario lanza una excepción.
     * 
	 * @param DateTime $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsDefined(DateTime $value): void
	{
		if (is_null($value)) {
			throw new BadRequestException("The value is not defined.");
		}
	}

    /**
     * Getter del value.
     * 
     * @return DateTime
     */
	final public function value(): DateTime
	{
		return $this->value;
	}

    /**
     * Setter del value.
     * 
     * @param DateTime $_value
     * 
     * @return void
     */
	final protected function setValue(DateTime $_value): void
	{
		$this->value = $_value;
	}

	/**
     * Convierte el value en un string de formato estandar.
     * 
	 * @return string
	 */
	public function valueFormatISO(): string
	{
		return $this->value()->format(self::DATE_TIME_FORMAT);
	}

    /**
     * Compara el VO con otro.
     * 
     * @param self $other
     * 
     * @return bool
     */
	final public function equals(self $other): bool
	{
		return $this->valueFormatISO() === $other->valueFormatISO();
	}

}