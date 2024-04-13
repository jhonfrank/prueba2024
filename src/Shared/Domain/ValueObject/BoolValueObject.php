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

    /**
	 * Convierte el argumento en el tipo primitivo aceptado por la clase.
     * 
     * @param mixed $_value
     * 
     * @return bool
     */
    public static function convertArgumentToPrimitiveType(mixed $_value): bool
	{
        if(is_null($_value)){
            throw new BadRequestException("The value is null.");
        }

		$value = match (gettype($_value)) {
			'string' => filter_var($_value, FILTER_VALIDATE_BOOLEAN),
			'integer' => (bool) $_value,
			'boolean' => $_value,
			default => throw new BadRequestException("The value is not of the accepted type. ($_value)")
		};

		return $value;
	}
}