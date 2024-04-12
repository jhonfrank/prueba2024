<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para los VO que sean int.
 */
abstract class IntValueObject
{	
    /**
     * @var int
     */
    private int $value;

	/**
     * Constructor de la clase, se verifica que sea un valor definido.
     * 
	 * @param int $_value
	 */
	public function __construct(int $_value)
	{
		$this->ensureValueIsDefined($_value);
		$this->ensureValueIsValid($_value);

        $this->setValue($_value);
	}
    
	/**
     * Verifica que el valor sea definido, en caso contrario lanza una excepción.
     * 
	 * @param int $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsDefined(int $value): void
	{
		if (is_null($value)) {
			throw new BadRequestException("The value is not defined.");
		}
	}

	/**
     * Contiene la verificación para valores validos, debe ser implementada en la clases hijas.
     * 
	 * @param int $value
	 * 
	 * @return void
	 */
	abstract protected function ensureValueIsValid(int $value): void;

    /**
     * Getter del value.
     * 
     * @return int
     */
	final public function value(): int
	{
		return $this->value;
	}

    /**
     * Setter del value.
     * 
     * @param int $_value
     * 
     * @return void
     */
	final protected function setValue(int $_value): void
	{
		$this->value = $_value;
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
		return $this->value() === $other->value();
	}
}