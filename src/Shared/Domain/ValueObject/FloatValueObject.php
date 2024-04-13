<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase para los VO que sean float.
 */
abstract class FloatValueObject
{	
    /**
     * @var float
     */
    private float $value;

	/**
     * Constructor de la clase, se verifica que sea un valor definido y un valor valido.
     * 
	 * @param float $_value
	 */
	public function __construct(float $_value)
	{
		$this->ensureValueIsDefined($_value);
		$this->ensureValueIsValid($_value);

        $this->setValue($_value);
	}
    
	/**
     * Verifica que el valor sea definido, en caso contrario lanza una excepción.
     * 
	 * @param float $value
	 * 
	 * @return void
	 */
	protected function ensureValueIsDefined(float $value): void
	{
		if (is_null($value)) {
			throw new BadRequestException("The value is not defined.");
		}
	}

	/**
     * Contiene la verificación para valores validos, debe ser implementada en la clases hijas.
     * 
	 * @param float $value
	 * 
	 * @return void
	 */
	abstract protected function ensureValueIsValid(float $value): void;

    /**
     * Getter del value.
     * 
     * @return float
     */
	final public function value(): float
	{
		return $this->value;
	}

    /**
     * Setter del value.
     * 
     * @param float $_value
     * 
     * @return void
     */
	final protected function setValue(float $_value): void
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
	 * @return float
	 */
	public static function convertArgumentToPrimitiveType(mixed $_value): float
	{
        if(is_null($_value)){
            throw new BadRequestException("The value is null.");
        }

		$value = match (true) {
			gettype($_value) == 'string' && is_numeric($_value) => (float) $_value,
			gettype($_value) == 'integer' => (float) $_value,
			gettype($_value) == 'double' => $_value,
			default => throw new BadRequestException("The value is not of the accepted type. ($_value)")
		};

		return $value;
	}
}