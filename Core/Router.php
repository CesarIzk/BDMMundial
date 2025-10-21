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
        if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
            Middleware::resolve($route['middleware']);

            // Verificar si el controlador tiene formato tipo 'Clase@metodo'
            if (str_contains($route['controles'], '@')) {
                [$controllerPath, $methodName] = explode('@', $route['controles']);

                // Convertir ruta a namespace (controles/Api/AuthController → controles\Api\AuthController)
                $controllerClass = $this->pathToNamespace($controllerPath);
                
                // Verificar que la clase exista
                if (!class_exists($controllerClass)) {
                    $this->abort(500);
                }

                $controller = new $controllerClass;
                return $controller->$methodName();
            } else {
                // Es solo un archivo PHP de vista
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