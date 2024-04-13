<?php

declare(strict_types=1);

namespace Src\User\Application;

use Src\User\Domain\User;
use Src\User\Domain\ValueObject\UserId;
use Src\User\Domain\IUserRepository;

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
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $UserId = new UserId($id);
        return $this->repository->searchById($UserId)->toArray();
    }
}