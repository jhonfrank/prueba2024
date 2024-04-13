<?php

declare(strict_types=1);

namespace Src\Transaction\Domain;

/**
 * Interfaz para el servicio de notificación de Transacción.
 */
interface ITransactionNotification
{
	/**
     * Enviar Notificación.
     * 
	 * @param Transaction $transaction
	 * 
	 * @return bool
	 */
	public function send(Transaction $transaction): bool;
}
