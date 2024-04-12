<?php

declare(strict_types=1);

namespace App;

use Exception;
use Throwable;

/**
 * Clase para gestionar los endpoints de la API.
 */
final class Api{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const METHODS = [
        self::METHOD_GET,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_DELETE
    ];

    private static ?self $instance = NULL;
    
    private array $routes = [];

    /**
     * Constructor de la clase.
     */
    private function __construct(){}

    /**
     * Obtiene la instancia única del objeto (Patron singleton).
     * 
     * @return self
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Convierte el route en una expresión Regex para poder hacer match cuando se haga petición a la API.
     * 
     * @param string $route
     * 
     * @return string
     */
    private function parseRouteInRegex(string $route): string
    {        
        $regex = str_replace('/', '\/', $route);
        $regex = preg_replace('/{id}/', '\d+', $regex);
        return $regex;
    }

    /**
     * Agrega un route, el método y la función callback.
     * 
     * @param string $method
     * @param string $route
     * @param mixed $function
     * 
     * @return void
     */
    public function addRoute(string $method, string $route, $function): void
    {
        if (!in_array($method, self::METHODS)) {
            throw new Exception("Method $method incorrect.");
        }
     
        if (str_contains('\\', $route)) {
            throw new Exception("Route incorrect: $route");
        }

        $this->routes[] = [
                'method' => $method,
                'route' => $route,
                'regex' => $this->parseRouteInRegex($route),
                'function' => $function
            ];
    }

    /**
     * Obtiene el valor {id}, siempre y cuando haya sido especificado en el route.
     * 
     * @param string $route
     * @param string $uri
     * 
     * @return array
     */
    private function getArgsFromUri(string $route, string $uri): array
    {
        $arrRoute = explode('/', $route);
        $arrUri = explode('/', $uri);

        $args = [];

        if(count($arrRoute) != count($arrUri)){
            throw new Exception("The structure of the route does not match the uri");
        }

        for($i = 0 ; $i < count($arrRoute); $i++) { 
            if($arrRoute[$i] == '{id}'){
                $args['id'] = (int) $arrUri[$i];
                break;
            }
        }

        return $args;
    }

    /**
     * Busca el match del método y el route configurado, luego ejecuta el callback.
     * En caso de no encontrar ninguna coincidencia devuelve HTTP Code 404.
     * 
     * @param string $method
     * @param string $uri
     * @param array $req
     * 
     * @return Response
     */
    private function execRoute(string $method, string $uri, array $req): Response
    {
        for ($i=0 ; $i < count($this->routes) ; $i++) {
            $regex = $this->routes[$i]['regex'];
            if($this->routes[$i]['method'] == $method && preg_match("/^$regex$/", $uri)){  
                $args = $this->getArgsFromUri($this->routes[$i]['route'], $uri);            
                return $this->routes[$i]['function']($req, $args);
            }
        }
        return Response::NotFound("No route found for URI: $uri");
    }

    /**
     * Inicializa el procesamiento del la petición a la API.
     * Se valida que el body de la petición pueda ser decodificado a JSON, en caso de error de devuelve HTTP Code 400.
     * En caso de algún error no manejado dentro de la ejecución se devuelve HTTP Code 500.
     * 
     * @return void
     */
    public function run(): void{
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $body = json_decode(file_get_contents('php://input'), true);

        if ($body === null && $method == self::METHOD_POST) {
            Response::BadRequest('The values sent in the request are not valid JSON.')->write();
            return;
         }

        $req = [
            'params' => $_GET,
            'body' => $body
        ];

        try{
            $this->execRoute($method, $uri, $req)->write();
        }
        catch (Throwable $e) {
            Response::InternalError($e->getMessage())->write();
        }
    }
}
