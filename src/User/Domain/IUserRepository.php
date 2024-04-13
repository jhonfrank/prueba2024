<?php

declare(strict_types=1);

namespace Src\User\Domain;

use Src\User\Domain\ValueObject\UserDocumentNumber;
use Src\User\Domain\ValueObject\UserEmail;
use Src\User\Domain\ValueObject\UserId;

interface IUserRepository
{
	/**
     * Guardar usuario.
     * 
	 * @param User $user
	 * 
	 * @return void
	 */
    public function save(User $user): void;

	/**
     * Actualizar usuario.
     * 
	 * @param User $user
	 * 
	 * @return void
	 */
	public function update(User $user): void;

	/**
     * Obtener todos los usuarios.
	 * 
	 * @return array
	 */
	public function searchAll(): array;

	/**
     * Obtener un usuario por id.
     * 
	 * @param UserId $userId
	 * 
	 * @return User
	 */
	public function searchById(UserId $userId): User;

	/**
     * Obtener usuarios por numero de documento.
     * 
	 * @param UserDocumentNumber $userDocumentNumber
	 * @param UserEmail $userEmail
	 * 
	 * @return array
	 */
	public function searchByDocumentNumber(UserDocumentNumber $userDocumentNumber): array;

	/**
     * Obtener usuarios por email.
     * 
	 * @param UserDocumentNumber $userDocumentNumber
	 * @param UserEmail $userEmail
	 * 
	 * @return array
	 */
	public function searchByEmail(UserEmail $userEmail): array;
}