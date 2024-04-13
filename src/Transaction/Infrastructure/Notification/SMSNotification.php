<?php

declare(strict_types=1);

namespace Src\Transaction\Infrastructure\Notification;

use Src\Transaction\Domain\ITransactionNotification;
use Src\Transaction\Domain\Transaction;

/**
 * Clase para el servicio de notificación SMS de Transacción.
 */
final class SMSNotification implements ITransactionNotification
{
	public function send(Transaction $transaction): bool
    {
        $res = json_decode(file_get_contents($_ENV['URL_TRANSACTION_NOTIFICATION']), true);
        if(!empty($res) && $res['message'] == true){
            return true;
        }

        return false;
    }
}