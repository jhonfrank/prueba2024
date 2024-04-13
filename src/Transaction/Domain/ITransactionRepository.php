<?php

declare(strict_types=1);

namespace Src\Transaction\Domain;

use Src\Transaction\Domain\ValueObject\TransactionId;
use Src\Transaction\Domain\ValueObject\TransactionPayerUserId;
use Src\Transaction\Domain\ValueObject\TransactionPayeeUserId;
use Src\User\Domain\User;

/**
 * Interfaz para el repositorio de Transacción.
 */
interface ITransactionRepository
{
	/**
     * Guardar transacción.
     * 
	 * @param Transaction $transaction
	 * 
	 * @return void
	 */
	public function save(Transaction $transaction): void;

	/**
     * Actualizar transacción.
     * 
	 * @param Transaction $transaction
	 * 
	 * @return void
	 */
	public function update(Transaction $transaction): void;

	/**
     * Obtener todas las transacciones.
     * 
	 * @return array
	 */
	public function searchAll(): array;

	/**
     * Obtener una transacción por id.
     * 
	 * @param TransactionId $transactionId
	 * 
	 * @return Transaction
	 */
	public function searchById(TransactionId $transactionId): Transaction;

	/**
     * Obtener las transacciones por usuario que hace el pago.
     * 
	 * @param TransactionPayerUserId $transactionPayerUserId
	 * 
	 * @return array
	 */
	public function searchByPayerUserId(TransactionPayerUserId $transactionPayerUserId): array;

	/**
     * Obtener las transacciones por usuario que recibe el pago.
     * 
	 * @param TransactionPayeeUserId $transactionPayeeUserId
	 * 
	 * @return array
	 */
	public function searchByPayeeUserId(TransactionPayeeUserId $transactionPayeeUserId): array;

	/**
     * Actualizar usuario.
     * 
	 * @param User $user
	 * 
	 * @return void
	 */
    public function updateUser(User $user): void;

	/**
     * Obtener un usuario por id.
     * 
	 * @param TransactionPayerUserId|TransactionPayeeUserId $userId
	 * 
	 * @return User
	 */
	public function searchUserById(TransactionPayerUserId|TransactionPayeeUserId $userId): User;
    
    public function begin_transaction(): void;
    public function commit(): void;
    public function rollback(): void;
    public function lastInsertId(): int;
}