<?php

declare(strict_types=1);

namespace Src\User\Infrastructure\Persistence;

use Src\Shared\Infrastructure\Persistence\MySQLDatabase;
use Src\Shared\Domain\Exception\NotFoundException;
use Src\User\Domain\IUserRepository;
use Src\User\Domain\User;
use Src\User\Domain\ValueObject\UserId;
use Src\User\Domain\ValueObject\UserDocumentNumber;
use Src\User\Domain\ValueObject\UserEmail;
use PDO;

class MySQLUserRepository extends MySQLDatabase implements IUserRepository
{
    public function save(User $user): void
    {
        $sql = '
            INSERT INTO user(fullName, documentNumber, email, password, isMerchant, walletAmount, createdAt, updatedAt)
            VALUES(:fullName, :documentNumber, :email, :password, :isMerchant, :walletAmount, :createdAt, :updatedAt);
        ';
        
        $params = [
            ':fullName' => [$user->fullName->value(), PDO::PARAM_STR],
            ':documentNumber' => [$user->documentNumber->value(), PDO::PARAM_STR],
            ':email' => [$user->email->value(), PDO::PARAM_STR],
            ':password' => [$user->password->value(), PDO::PARAM_STR],
            ':isMerchant' => [$user->isMerchant->value(), PDO::PARAM_BOOL],
            ':walletAmount' => [$user->walletAmount->value(), PDO::PARAM_STR],
            ':createdAt' => [$user->createdAt->valueFormatISO(), PDO::PARAM_STR],
            ':updatedAt' => [$user->updatedAt->valueFormatISO(), PDO::PARAM_STR]
        ];        

        $this->exec($sql, $params);
    }

    public function update(User $user): void
    {
        $sql = '
            UPDATE user
            SET
                fullName = :fullName,
                documentNumber = :documentNumber,
                email = :email,
                password = :password,
                isMerchant = :isMerchant,
                walletAmount = :walletAmount,
                updatedAt = :updatedAt
            WHERE id = :id;
        ';

        $params = [
            ':fullName' => [$user->fullName->value(), PDO::PARAM_STR],
            ':documentNumber' => [$user->documentNumber->value(), PDO::PARAM_STR],
            ':email' => [$user->email->value(), PDO::PARAM_STR],
            ':password' => [$user->password->value(), PDO::PARAM_STR],
            ':isMerchant' => [$user->isMerchant->value(), PDO::PARAM_BOOL],
            ':walletAmount' => [$user->walletAmount->value(), PDO::PARAM_STR],
            ':updatedAt' => [$user->updatedAt->valueFormatISO(), PDO::PARAM_STR],
            ':id' => [$user->id->value(), PDO::PARAM_INT]
        ];

        $this->exec($sql, $params);
    }

    public function searchAll(): array
    {
        $sql = '
            SELECT
                u.id,
                u.fullName,
                u.documentNumber,
                u.email,
                u.password,
                u.isMerchant,
                u.walletAmount,
                u.createdAt,
                u.updatedAt
            FROM user u
            ORDER BY u.createdAt ASC;
        ';

        $rows = $this->select($sql);

        $users = array_map(fn($item): array => User::createFromArray($item)->toArray(), $rows);

        return $users;
    }

    public function searchById(UserId $userId): User
    {
        $sql = '
            SELECT
                u.id,
                u.fullName,
                u.documentNumber,
                u.email,
                u.password,
                u.isMerchant,
                u.walletAmount,
                u.createdAt,
                u.updatedAt
            FROM user u
            WHERE u.id = :id;
        ';

        $params = [
            ':id' => [$userId->value(), PDO::PARAM_INT]
        ];

        $row = $this->select($sql, $params);

        if(empty($row)){
            throw new NotFoundException('User not exists.');
        }

        $user = User::createFromArray($row[0]);

        return $user;
    }

	public function searchByDocumentNumber(UserDocumentNumber $userDocumentNumber): array
    {        
        $sql = '
            SELECT
                u.id,
                u.fullName,
                u.documentNumber,
                u.email,
                u.password,
                u.isMerchant,
                u.walletAmount,
                u.createdAt,
                u.updatedAt
            FROM user u
            WHERE u.documentNumber = :documentNumber;
        ';

        $params = [
            ':documentNumber' => [$userDocumentNumber->value(), PDO::PARAM_STR]
        ];

        $rows = $this->select($sql, $params);

        $users = array_map(fn($item): array => User::createFromArray($item)->toArray(), $rows);

        return $users;
    }

	public function searchByEmail(UserEmail $userEmail): array
    {        
        $sql = '
            SELECT
                u.id,
                u.fullName,
                u.documentNumber,
                u.email,
                u.password,
                u.isMerchant,
                u.walletAmount,
                u.createdAt,
                u.updatedAt
            FROM user u
            WHERE u.email = :email;
        ';

        $params = [
            ':email' => [$userEmail->value(), PDO::PARAM_STR]
        ];

        $rows = $this->select($sql, $params);

        $users = array_map(fn($item): array => User::createFromArray($item)->toArray(), $rows);

        return $users;
    }

}