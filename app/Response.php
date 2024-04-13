<?php

declare(strict_types=1);

namespace App;

/**
 * Clase para gestionar las respuestas de las peticiones a la API.
 */
final class Response
{

    const CODE_OK = 200;
    const CODE_NOT_FOUND = 404;
    const CODE_BAD_REQUEST = 400;
    const CODE_INTERNAL_ERROR = 500;

    /**
     * Constructor de la clase.
     * 
     * @param  int $code
     * @param  array $data
     * @param  string $message
     */
    private function __construct(private int $code, private array $data, private string $message)
    {
    }

    /**
     * Crea response con HTTP Code 200.
     * 
     * @param array $data
     * 
     * @return self
     */
    public static function Ok(array $data, string $message = ''): self
    {
        return new self(self::CODE_OK, $data, $message);
    }

    /**
     * Crea response con HTTP Code 404.
     * 
     * @param string $message
     * 
     * @return self
     */
    public static function NotFound(string $message): self
    {
        return new self(self::CODE_NOT_FOUND, [], $message);
    }

    /**
     * Crea response con HTTP Code 400.
     * 
     * @param string $message
     * 
     * @return self
     */
    public static function BadRequest(string $message): self
    {
        return new self(self::CODE_BAD_REQUEST, [], $message);
    }

    /**
     * Crea response con HTTP Code 500.
     * 
     * @param string $message
     * 
     * @return self
     */
    public static function InternalError(string $message): self
    {
        return new self(self::CODE_INTERNAL_ERROR, [], $message);
    }

    /**
     * Escribe la respuesta a la petición en formato JSON.
     * Si data está vacío solo se escribe el message.
     * 
     * @return void
     */
    public function write(): void
    {
        header('Content-Type: application/json');
        http_response_code($this->code);
        
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');

        $response = [];
        if(count($this->data) == 0 && $this->message !== ''){
            $response = ['message' => $this->message];
        }
        else{
            $response = $this->data;
        }

        echo json_encode(
            $response,
            JSON_PRETTY_PRINT
        );

    }
}