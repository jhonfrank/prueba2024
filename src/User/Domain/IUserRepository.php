<?php

declare(strict_types=1);

namespace Src\User\Domain;

use Src\User\Domain\ValueObject\UserId;

interface IUserRepository
{
	/**
     * Guardar usuario.
     * 
	 * @param User $transaction
	 * 
	 * @return void
	 */
    public function save(User $user): void;

	/**
     * Actualizar usuario.
     * 
	 * @param User $transaction
	 * 
	 * @return void
	 */
	public function update(User $user): void;

	/**
     * Obtener todos los usuarios.
     * 
	 * @param User $transaction
	 * 
	 * @return array
	 */
	public function searchAll(): array;

	/**
     * Obtener un usuario por id.
     * 
	 * @param UserId $transactionId
	 * 
	 * @return User
	 */
	public function searchById(UserId $productId): User;
}