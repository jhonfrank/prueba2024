<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para los VO que sean bool.
 */
abstract class BoolValueObject
{	
    /**
     * @var bool
     */
    private bool $value;

	/**
     * Constructor de la clase, se verifica que sea un valor definido y un valor valido.
     * 
	 * @param bool $_value
	 */
	public function __construct(bool $_value)
	{
		$this->ensureValueIsDefined($_value);

        $this->setValue($_value);
	}
    
	/**
     * Verifica que el valor sea definido, en caso contrario lanza una excepción.
     * 
	 * @param bool $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsDefined(bool $value): void
	{
		if (is_null($value)) {
			throw new BadRequestException("The value is not defined.");
		}
	}

	/**
     * Contiene la verificación para valores validos, debe ser implementada en la clases hijas.
     * 
	 * @param bool $value
	 * 
	 * @return void
	 */
	abstract protected function ensureValueIsValid(bool $value): void;

    /**
     * Getter del value.
     * 
     * @return bool
     */
	final public function value(): bool
	{
		return $this->value;
	}

    /**
     * Setter del value.
     * 
     * @param bool $_value
     * 
     * @return void
     */
	final protected function setValue(bool $_value): void
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