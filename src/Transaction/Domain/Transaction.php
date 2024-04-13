<?php

declare(strict_types=1);

namespace Src\Transaction\Domain;

use Src\Transaction\Domain\ValueObject\{TransactionId, TransactionAmount, TransactionPayerUserId, TransactionPayeeUserId, TransactionIsNotified, TransactionCreatedAt, TransactionUpdatedAt};
use Src\Shared\Domain\Exception\BadRequestException;
use ReflectionClass;

/**
 * Clase para la entidad Transaction.
 */
final class Transaction
{
	/**
     * Constructor de la clase
     * 
	 * @param TransactionId $id
	 * @param TransactionAmount $amount
	 * @param TransactionPayerUserId $payerUserId
	 * @param TransactionPayeeUserId $payeeUserId
	 * @param TransactionIsNotified $isNotified
	 * @param TransactionCreatedAt $createdAt
	 * @param TransactionUpdatedAt $updatedAt
	 */
	private function __construct(
		public TransactionId $id,
		public TransactionAmount $amount,
		public TransactionPayerUserId $payerUserId,
		public TransactionPayeeUserId $payeeUserId,
		public TransactionIsNotified $isNotified,
		public TransactionCreatedAt $createdAt,
		public TransactionUpdatedAt $updatedAt
	) {
	}

	/**
     * Crea un objeto Transaction a partir de ValueObjects
     * 
	 * @param TransactionId $id
	 * @param TransactionAmount $amount
	 * @param TransactionPayerUserId $payerUserId
	 * @param TransactionPayeeUserId $payeeUserId
	 * @param TransactionIsNotified $isNotified
	 * @param TransactionCreatedAt $createdAt
	 * @param TransactionUpdatedAt $updatedAt
	 * 
	 * @return self
	 */
	public static function create(
		TransactionId $id,
		TransactionAmount $amount,
		TransactionPayerUserId $payerUserId,
		TransactionPayeeUserId $payeeUserId,
		TransactionIsNotified $isNotified,
		TransactionCreatedAt $createdAt,
		TransactionUpdatedAt $updatedAt
	): self {
		$transaction = new self($id, $amount, $payerUserId, $payeeUserId, $isNotified, $createdAt, $updatedAt);

		return $transaction;
	}

	/**
     * Crea un objeto Transaction a partir de un array.
     * 
	 * @param array $arr
	 * 
	 * @return self
	 */
	public static function createFromArray(array $arr): self
	{
		$props = array_keys(self::getArrayMapperPropertiesClass());
				
		foreach ($props as $prop) {
			if (!in_array($prop, array_keys($arr))) {
				throw new BadRequestException("Have missing values. ($prop)");
			}
		}

		$transactionArray = self::convertArrayWithPrimitivesTypes($arr);
		
		$transaction = self::create(
            new TransactionId($transactionArray['id']),
            new TransactionAmount($transactionArray['amount']),
            new TransactionPayerUserId($transactionArray['payerUserId']),
            new TransactionPayeeUserId($transactionArray['payeeUserId']),
            new TransactionIsNotified($transactionArray['isNotified']),
            new TransactionCreatedAt($transactionArray['createdAt']),
            new TransactionUpdatedAt($transactionArray['updatedAt']),
		);

		return $transaction;
	}

    /**
     * Convierte un array generico a un array con los tipos requeridos por cada propiedad de la clase.
     * 
     * @param array $arr
     * 
     * @return array
     */
    public static function convertArrayWithPrimitivesTypes(array $arr) : array
	{
		$arrayMapperPropertiesClass = self::getArrayMapperPropertiesClass();
		
		foreach (array_keys($arr) as $prop) {
			if (in_array($prop, array_keys($arrayMapperPropertiesClass))){
				$arr[$prop] = $arrayMapperPropertiesClass[$prop]::convertArgumentToPrimitiveType($arr[$prop]);
			}
		}

		return $arr;
	}

	/**
     * Obtiene un array con los valores de las propiedades de la clase y el nombre de la clase.
     * 
	 * @return array
	 */
	public static function getArrayMapperPropertiesClass() : array
	{		
		$class = new ReflectionClass(self::class);

		$properties = $class->getProperties();
		
		$arrayMapperPropertiesClass = array_combine(
			array_map(fn($prop): string => $prop->getName(), $properties),
			array_map(fn($prop): string => $prop->getType()->getName(), $properties)
		);

		return $arrayMapperPropertiesClass;
	}

	/**
     * Exporta las propiedades al formato Array con los tipo primitivos.
     * 
	 * @return array
	 */
	public function toArray(): array
	{
		$arrayMapperPropertiesClass = self::getArrayMapperPropertiesClass();

		foreach (array_keys($arrayMapperPropertiesClass) as $prop) {
			$arr[$prop] = $this->$prop->value();
		}

		return $arr;
	}
}