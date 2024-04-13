<?php

declare(strict_types=1);

namespace Src\Transaction\Infrastructure\Auth;

use Src\Transaction\Domain\ITransactionAuth;
use Src\Transaction\Domain\Transaction;

/**
 * Clase para el servicio de autorización externo de Transacción.
 */
final class PaymentAuth implements ITransactionAuth
{
	public function auth(Transaction $transaction): bool
    {
        $res = json_decode(file_get_contents($_ENV['URL_TRANSACTION_AUTH']), true);
        if(!empty($res) && $res['message'] === "Autorizado"){
            return true;
        }

        return false;
    }
}