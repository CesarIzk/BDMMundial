<?php

namespace Core;

use Core\Middleware\Authenticated;
use Core\Middleware\Guest;
use Core\Middleware\Middleware;


class Router
{
    protected $routes = [];
    protected function convertToNamespace($path)
    {
        $path = str_replace('/', '\\', $path); // Ej: controles/Api/PostController → controles\Api\PostController
        return $path;
    }
    
    public function add($method, $uri, $controles)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controles' => $controles,
            'method' => $method,
            'middleware' => null
        ];

        return $this;
    }

    public function get($uri, $controles)
    {
        return $this->add('GET', $uri, $controles);
    }

    public function post($uri, $controles)
    {
        return $this->add('POST', $uri, $controles);
    }

    public function delete($uri, $controles)
    {
        return $this->add('DELETE', $uri, $controles);
    }

    public function patch($uri, $controles)
    {
        return $this->add('PATCH', $uri, $controles);
    }

    public function put($uri, $controles)
    {
        return $this->add('PUT', $uri, $controles);
    }

    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;

        return $this;
    }
public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            // 1. Comprobar el método HTTP
            if ($route['method'] !== strtoupper($method)) {
                continue; 
            }

            // 2. Convertir la URI de la ruta a una expresión regular
            // ¡CAMBIO 1: Quitado el '\' de [^\/]+
            $regexUri = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['uri']);
            
            // ¡CAMBIO 2: Se usa ~ como delimitador y se quitó str_replace
            $regexPattern = '~^' . $regexUri . '$~';

            // 3. Comprobar si la URI actual del navegador coincide con el patrón
            if (preg_match($regexPattern, $uri, $matches)) { // Esta es tu línea 77
                
                Middleware::resolve($route['middleware']);

                // 4. Extraer los parámetros (con el fix para PHP < 5.6)
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                // 5. Comprobar si es un controlador o una vista
                if (str_contains($route['controles'], '@')) {
                    [$controllerPath, $methodName] = explode('@', $route['controles']);

                    $controllerClass = $this->pathToNamespace($controllerPath);
                    
                    if (!class_exists($controllerClass)) {
                        $this->abort(500);
                    }

                    $controller = new $controllerClass;
                    
                    // 6. Pasar los parámetros al método
                    return $controller->$methodName($params);
                
                } else {
                    return require base_path($route['controles']);
                }
            }
        }

        $this->abort();
    }

/**
 * Convierte ruta de archivo a namespace de clase
 * Ej: controles/Api/AuthController → Controles\Api\AuthController
 */
protected function pathToNamespace($path)
{
    // Eliminar extensión .php si existe
    $path = str_replace('.php', '', $path);
    
    // Convertir primera letra de cada segmento a mayúscula
    $parts = explode('/', $path);
    $parts = array_map('ucfirst', $parts);
    
    return implode('\\', $parts);
}
    


    protected function abort($code = 404)
    {
        http_response_code($code);

        require base_path("front/{$code}.php");

        die();
    }
}