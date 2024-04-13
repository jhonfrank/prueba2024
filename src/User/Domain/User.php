<?php

declare(strict_types=1);

namespace Src\User\Domain;

use Src\User\Domain\ValueObject\{UserId, UserFullName, UserDocumentNumber, UserEmail, UserPassword, UserIsMerchant, UserWalletAmount, UserCreatedAt, UserUpdatedAt};
use Src\Shared\Domain\Exception\BadRequestException;
use ReflectionClass;

/**
 * Clase para la entidad User.
 */
final class User
{
	/**
     * Constructor de la clase
     * 
	 * @param UserId $id
	 * @param UserFullName $fullName
	 * @param UserDocumentNumber $documentNumber
	 * @param UserEmail $email
	 * @param UserPassword $password
	 * @param UserIsMerchant $isMerchant
	 * @param UserWalletAmount $walletAmount
	 * @param UserCreatedAt $createdAt
	 * @param UserUpdatedAt $updatedAt
	 */
	private function __construct(
		public UserId $id,
		public UserFullName $fullName,
		public UserDocumentNumber $documentNumber,
		public UserEmail $email,
		public UserPassword $password,
		public UserIsMerchant $isMerchant,
		public UserWalletAmount $walletAmount,
		public UserCreatedAt $createdAt,
		public UserUpdatedAt $updatedAt
	) {
	}

	/**
     * Crea un objeto User a partir de ValueObjects
     * 
	 * @param UserId $id
	 * @param UserFullName $fullName
	 * @param UserDocumentNumber $documentNumber
	 * @param UserEmail $email
	 * @param UserPassword $password
	 * @param UserIsMerchant $isMerchant
	 * @param UserWalletAmount $walletAmount
	 * @param UserCreatedAt $createdAt
	 * @param UserUpdatedAt $updatedAt
	 * 
	 * @return self
	 */
	public static function create(
		UserId $id,
		UserFullName $fullName,
		UserDocumentNumber $documentNumber,
		UserEmail $email,
		UserPassword $password,
		UserIsMerchant $isMerchant,
		UserWalletAmount $walletAmount,
		UserCreatedAt $createdAt,
		UserUpdatedAt $updatedAt
	): self {
		$user = new self($id, $fullName, $documentNumber, $email, $password, $isMerchant, $walletAmount, $createdAt, $updatedAt);

		return $user;
	}

	/**
     * Crea un objeto User a partir de un array.
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

		$categoryArray = self::convertArrayWithPrimitivesTypes($arr);
		
		$user = self::create(
			new UserId($categoryArray['id']),
			new UserFullName($categoryArray['fullName']),
			new UserDocumentNumber($categoryArray['documentNumber']),
			new UserEmail($categoryArray['email']),
			new UserPassword($categoryArray['password']),
			new UserIsMerchant($categoryArray['isMerchant']),
			new UserWalletAmount($categoryArray['walletAmount']),
			new UserCreatedAt($categoryArray['createdAt']),
			new UserUpdatedAt($categoryArray['updatedAt'])
		);

		return $user;
	}

    /**
     * Envia pago.
     * En caso de ser comerciante no puede enviar pago.
     * En caso de no tener saldo suficiente no puede enviar pago.
     * 
     * @param UserWalletAmount $amount
     * 
     * @return void
     */
    public function sendPayment(UserWalletAmount $amount): void{
        if($this->isMerchant->value()){
            throw new BadRequestException("Merchant users can't send payments." . $this->isMerchant->value());
        }
        
        if($amount->value() > $this->walletAmount->value()){
            throw new BadRequestException("The user does not have enough balance.");
        }
        
        $walletAmount = $this->walletAmount->value() - $amount->value();
        $this->walletAmount = new UserWalletAmount($walletAmount);
    }

    /**
     * Recibir pagos.
     * 
     * @param UserWalletAmount $amount
     * 
     * @return void
     */
    public function receivePayment(UserWalletAmount $amount): void{
        $walletAmount = $this->walletAmount->value() + $amount->value();
        $this->walletAmount = new UserWalletAmount($walletAmount);
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
            if(gettype($this->$prop->value()) === "object" && $this->$prop->value() instanceof \DateTime){
                $arr[$prop] = $this->$prop->valueFormatISO();
            }
            else{
                $arr[$prop] = $this->$prop->value();
            }
		}

		return $arr;
	}
}