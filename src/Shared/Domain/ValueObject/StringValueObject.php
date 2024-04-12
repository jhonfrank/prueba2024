<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para los VO que sean string.
 */
abstract class StringValueObject
{	
    /**
     * @var string
     */
    private string $value;

	/**
     * Constructor de la clase, se verifica que sea un valor definido y un valor valido.
     * 
	 * @param string $_value
	 */
	public function __construct(string $_value)
	{
		$this->ensureValueIsDefined($_value);
		$this->ensureValueIsValid($_value);

        $this->setValue($_value);
	}
    
	/**
     * Verifica que el valor sea definido, en caso contrario lanza una excepción.
     * 
	 * @param string $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsDefined(string $value): void
	{
		if (is_null($value)) {
			throw new BadRequestException("The value is not defined.");
		}
	}

	/**
     * Contiene la verificación para valores validos, debe ser implementada en la clases hijas.
     * 
	 * @param string $value
	 * 
	 * @return void
	 */
	abstract protected function ensureValueIsValid(string $value): void;

    /**
     * Getter del value.
     * 
     * @return string
     */
	final public function value(): string
	{
		return $this->value;
	}

    /**
     * Setter del value.
     * 
     * @param string $_value
     * 
     * @return void
     */
	final protected function setValue(string $_value): void
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