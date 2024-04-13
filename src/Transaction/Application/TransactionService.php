<?php

declare(strict_types=1);

namespace Src\Transaction\Application;

use Src\Transaction\Domain\Transaction;
use Src\Transaction\Domain\ValueObject\TransactionId;
use Src\Transaction\Domain\ITransactionRepository;
use Src\Transaction\Domain\ValueObject\TransactionPayerUserId;
use Src\Transaction\Domain\ValueObject\TransactionPayeeUserId;

/**
 * Clase del service para Transaction.
 */
final class TransactionService
{
    /**
     * @var ITransactionRepository
     */
    private ITransactionRepository $repository;

    /**
     * Constructor de la clase.
     * 
     * @param ITransactionRepository $_repository
     */
    public function __construct(ITransactionRepository $_repository) {
        $this->repository = $_repository;
    }

    /**
     * Crear transacción.
     * 
     * @param array $transactionArray
     * 
     * @return void
     */
    public function create(array $transactionArray): void
    {
        $transaction = Transaction::createFromArray($transactionArray);
        $this->repository->save($transaction);
    }

    /**
     * Mostrar todas las transacciones.
     * 
     * @return array
     */
    public function getAll(): array
    {
        return $this->repository->searchAll();
    }

    /**
     * Mostrar una transacción por id.
     * 
     * @param int $id
     * 
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $transactionId = new TransactionId($id);
        return $this->repository->searchById($transactionId)->toArray();
    }
    

	/**
     * Mostrar las transacciones por usuario que hace el pago.
     * 
	 * @param int $transactionPayerUserId
	 * 
	 * @return array
	 */
	public function getByPayerUserId(int $payerUserId): array
    {
        $transactionPayerUserId = new TransactionPayerUserId($payerUserId);
        return $this->repository->searchByPayerUserId($transactionPayerUserId);
    }

	/**
     * Mostrar las transacciones por usuario recibe el pago.
     * 
	 * @param int $transactionPayeeUserId
	 * 
	 * @return array
	 */
	public function getByPayeeUserId(int $payeeUserId): array
    {
        $transactionPayeeUserId = new TransactionPayeeUserId($payeeUserId);
        return $this->repository->searchByPayeeUserId($transactionPayeeUserId);
    }
}