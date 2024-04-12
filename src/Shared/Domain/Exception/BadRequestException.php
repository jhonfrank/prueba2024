<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

Use Exception;

/**
 * Clase para manejar los errores que se generen en el dominio por un request incorrecto o no válido.
 */
class BadRequestException extends Exception{

    /**
     * Contructor de la clase.
     * 
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = '', int $code = 0) {
        parent::__construct($message, $code);
        $this->message = "$message";
        $this->code = $code;
    }
}