<?php

declare(strict_types=1);

namespace Src\Transaction\Application;

use Src\Shared\Domain\Exception\BadRequestException;
use Src\Transaction\Domain\Transaction;
use Src\Transaction\Domain\ValueObject\TransactionId;
use Src\Transaction\Domain\ITransactionRepository;
use Src\Transaction\Domain\ITransactionAuth;
use Src\Transaction\Domain\ITransactionNotification;
use Src\Transaction\Domain\ValueObject\TransactionIsNotified;
use Src\Transaction\Domain\ValueObject\TransactionPayerUserId;
use Src\Transaction\Domain\ValueObject\TransactionPayeeUserId;
use Src\User\Domain\ValueObject\UserWalletAmount;
use Throwable;

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
     * @var ITransactionAuth
     */
    private ITransactionAuth $auth;

    /**
     * @var ITransactionNotification
     */
    private ITransactionNotification $notification;

    /**
     * Constructor de la clase.
     * 
     * @param ITransactionRepository $_repository
     */
    public function __construct(ITransactionRepository $_repository, ITransactionAuth $_auth, ITransactionNotification $_notification) {
        $this->repository = $_repository;
        $this->auth = $_auth;
        $this->notification = $_notification;
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

        $payerUser = $this->repository->searchUserById($transaction->payerUserId);
        $payeeUser = $this->repository->searchUserById($transaction->payeeUserId);

        $amount = new UserWalletAmount($transaction->amount->value());

        # 0. Validar el saldo del usuario
        $payerUser->validateBalance($amount);
     
        try{

            # 1. Inicio de la transacción
            $this->repository->begin_transaction();
            
            # 2. Generar el registro de la transacción
            $this->repository->save($transaction);            
            $transaction->id = new TransactionId($this->repository->lastInsertId());

            # 3. Actualizar saldo de la billetera del usuario que envía el pago
            $payerUser->sendPayment($amount);
            $this->repository->updateUser($payerUser);

            # 4. Actualizar saldo de la billetera del usuario que recibe el pago
            $payeeUser->receivePayment($amount);
            $this->repository->updateUser($payeeUser);

            # 5. Consulta de autorizacion externa
            if(!$this->auth->auth($transaction)){
                throw new BadRequestException('Unauthorized transaction by external service.');
            }

            # 6. Confirmar la transacción
            $this->repository->commit();           

        }
        catch(Throwable $e){
            $this->repository->rollback();
            throw new BadRequestException('The transaction has not been processed. ' . $e->getMessage());
        }
        
        # 6. Enviar notificación, actualizar el estado si se llega a enviar.
        if($this->notification->send($transaction)){
            $transaction->isNotified = new TransactionIsNotified(true);
            $this->repository->update($transaction);
        }
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
     * @return array
     */
    public function getById(int $id): array
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