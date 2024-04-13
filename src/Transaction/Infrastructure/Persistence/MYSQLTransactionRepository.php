<?php

declare(strict_types=1);

namespace Src\Transaction\Infrastructure\Persistence;

use Src\Shared\Infrastructure\Persistence\MySQLDatabase;
use Src\Shared\Domain\Exception\NotFoundException;
use Src\Transaction\Domain\ITransactionRepository;
use Src\Transaction\Domain\Transaction;
use Src\Transaction\Domain\ValueObject\TransactionId;
use Src\Transaction\Domain\ValueObject\TransactionPayerUserId;
use Src\Transaction\Domain\ValueObject\TransactionPayeeUserId;
use Src\User\Domain\User;
use PDO;

class MySQLTransactionRepository extends MySQLDatabase implements ITransactionRepository
{
    public function save(Transaction $transaction): void
    {
        $sql = '
            INSERT INTO transaction(amount, payerUserId, payeeUserId, isNotified, createdAt, updatedAt)
            VALUES(:amount, :payerUserId, :payeeUserId, :isNotified, :createdAt, :updatedAt);
        ';
        
        $params = [
            ':amount' => [$transaction->amount->value(), PDO::PARAM_STR],
            ':payerUserId' => [$transaction->payerUserId->value(), PDO::PARAM_INT],
            ':payeeUserId' => [$transaction->payeeUserId->value(), PDO::PARAM_INT],
            ':isNotified' => [$transaction->isNotified->value(), PDO::PARAM_BOOL],
            ':createdAt' => [$transaction->createdAt->valueFormatISO(), PDO::PARAM_STR],
            ':updatedAt' => [$transaction->updatedAt->valueFormatISO(), PDO::PARAM_STR]
        ];        

        $this->exec($sql, $params);
    }

    public function update(Transaction $transaction): void
    {
        $sql = '
            UPDATE transaction
            SET
                amount = :amount,
                payerUserId = :payerUserId,
                payeeUserId = :payeeUserId,
                isNotified = :isNotified,
                updatedAt = :updatedAt
            WHERE id = :id;
        ';
        
        $params = [
            ':amount' => [$transaction->amount->value(), PDO::PARAM_STR],
            ':payerUserId' => [$transaction->payerUserId->value(), PDO::PARAM_INT],
            ':payeeUserId' => [$transaction->payeeUserId->value(), PDO::PARAM_INT],
            ':isNotified' => [$transaction->isNotified->value(), PDO::PARAM_BOOL],
            ':updatedAt' => [$transaction->updatedAt->valueFormatISO(), PDO::PARAM_STR],
            ':id' => [$transaction->id->value(), PDO::PARAM_INT]
        ];        

        $this->exec($sql, $params);
    }

    public function searchAll(): array
    {
        $sql = '
            SELECT
                t.id,
                t.amount,
                t.payerUserId,
                t.payeeUserId,
                t.isNotified,
                t.createdAt,
                t.updatedAt
            FROM transaction t
            ORDER BY t.createdAt ASC;
        ';

        $rows = $this->select($sql);

        $transaction = array_map(fn($item): array => Transaction::createFromArray($item)->toArray(), $rows);

        return $transaction;
    }

    public function searchById(TransactionId $transactionId): Transaction
    {
        $sql = '
            SELECT
                t.id,
                t.amount,
                t.payerUserId,
                t.payeeUserId,
                t.isNotified,
                t.createdAt,
                t.updatedAt
            FROM transaction t
            WHERE t.id = :id;
        ';

        $params = [
            ':id' => [$transactionId->value(), PDO::PARAM_INT]
        ];

        $rows = $this->select($sql, $params);

        if(empty($rows)){
            throw new NotFoundException('Transaction not exists.');
        }

        $transaction = Transaction::createFromArray($rows[0]);

        return $transaction;
    }

    public function searchByPayerUserId(TransactionPayerUserId $transactionPayerUserId): array
    {
        $sql = '
            SELECT
                t.id,
                t.amount,
                t.payerUserId,
                t.payeeUserId,
                t.isNotified,
                t.createdAt,
                t.updatedAt
            FROM transaction t
            WHERE t.payerUserId = :id;
        ';

        $params = [
            ':id' => [$transactionPayerUserId->value(), PDO::PARAM_INT]
        ];

        $rows = $this->select($sql, $params);

        $transaction = array_map(fn($item): array => Transaction::createFromArray($item)->toArray(), $rows);

        return $transaction;
    }

    public function searchByPayeeUserId(TransactionPayeeUserId $transactionPayeeUserId): array
    {
        $sql = '
            SELECT
                t.id,
                t.amount,
                t.payerUserId,
                t.payeeUserId,
                t.isNotified,
                t.createdAt,
                t.updatedAt
            FROM transaction t
            WHERE t.payeeUserId = :id;
        ';

        $params = [
            ':id' => [$transactionPayeeUserId->value(), PDO::PARAM_INT]
        ];

        $rows = $this->select($sql, $params);

        $users = array_map(fn($item): array => Transaction::createFromArray($item)->toArray(), $rows);

        return $users;
    }
    
    public function updateUser(User $user): void
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
    
    public function searchUserById(TransactionPayerUserId|TransactionPayeeUserId $userId): User
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
}