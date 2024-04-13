<?php

declare(strict_types=1);

namespace Src\Transaction\Domain;

/**
 * Interfaz para el servicio de autorización externo de Transacción.
 */
interface ITransactionAuth
{
	/**
     * Solicitar autorización.
     * 
	 * @param Transaction $transaction
	 * 
	 * @return bool
	 */
	public function auth(Transaction $transaction): bool;
}