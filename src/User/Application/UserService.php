<?php

declare(strict_types=1);

namespace Src\User\Application;

use Src\User\Domain\User;
use Src\User\Domain\ValueObject\UserId;
use Src\User\Domain\ValueObject\UserDocumentNumber;
use Src\User\Domain\ValueObject\UserEmail;
use Src\User\Domain\IUserRepository;
use Src\Shared\Domain\Exception\BadRequestException;

/**
 * Clase del service para User.
 */
final class UserService
{
    /**
     * @var IUserRepository
     */
    private IUserRepository $repository;

    /**
     * Constructor de la clase.
     * 
     * @param IUserRepository $_repository
     */
    public function __construct(IUserRepository $_repository) {
        $this->repository = $_repository;
    }

    /**
     * Crear usuario.
     * 
     * @param array $userArray
     * 
     * @return void
     */
    public function create(array $userArray): void
    {
        $user = User::createFromArray($userArray);

        $user->generateHashPassword();

        $usersByDocumentNumber = $this->getByDocumentNumber($user->documentNumber->value());

        if(!empty($usersByDocumentNumber)){
            throw new BadRequestException('The document number is already registered. (' . $user->documentNumber->value() . ')');
        }

        $usersByEmail = $this->getByEmail($user->email->value());

        if(!empty($usersByEmail)){
            throw new BadRequestException('The email is already registered. (' . $user->email->value() . ')');
        }

        $this->repository->save($user);
    }

    /**
     * Actualizar usuario.
     * 
     * @param array $userArray
     * 
     * @return void
     */
    public function update(array $userArray): void
    {
        $userArrayBD = $this->getById($userArray['id']);

        $userArrayRQ = User::convertArrayWithPrimitivesTypes($userArray);

        $userArray = array_replace($userArrayBD, $userArrayRQ);

        $user = User::createFromArray($userArray);
        
        $this->repository->update($user);
    }

    /**
     * Obtener todos los usuarios.
     * 
     * @return array
     */
    public function getAll(): array
    {
        return $this->repository->searchAll();
    }

    /**
     * Obtener un usuario por id.
     * 
     * @param int $id
     * 
     * @return array
     */
    public function getById(int $id): array
    {
        $userId = new UserId($id);
        return $this->repository->searchById($userId)->toArray();
    }

    /**
     * Obtener usuarios por número de documento.
     * 
     * @param string $documentNumber
     * 
     * @return array
     */
    public function getByDocumentNumber(string $documentNumber): array
    {
        $userDocumentNumber = new UserDocumentNumber($documentNumber);
        return $this->repository->searchByDocumentNumber($userDocumentNumber);
    }

    /**
     * Obtener usuarios por email.
     * 
     * @param string $email
     * 
     * @return array
     */
    public function getByEmail(string $email): array
    {
        $userEmail = new UserEmail($email);
        return $this->repository->searchByEmail($userEmail);
    }
}