<?php

use Controller\Base as BaseController;

class Router
{
    private $routes = [];

    public function defineRoute(string $method, string $uri, string $controllerName)
    {
        $method = $this->formatMethod($method);
        $uri = $this->formatUri($uri);

        $this->routes[ $method ][ $uri ] = function($params = []) use ($controllerName) {
            $controller = new $controllerName();
            $controller->start( $params );
        };
    }

    public function defineRedirect(string $method, string $uri, string $redirectUri)
    {
        $method = $this->formatMethod($method);
        $uri = $this->formatUri($uri);
        $redirectUri = $this->formatUri($redirectUri);

        $this->routes[ $method ][ $uri ] = function() use ($redirectUri) {
            $controller = new BaseController();
            $controller->redirect($redirectUri);
        };
    }

    private function formatMethod($method)
    {
        return strtolower($method);
    }

    private function formatUri($uri)
    {
        $uri = strtolower($uri);
        $uri = strtok($uri, '?');
        $uri = trim($uri, '/');

        return $uri;
    }

    private function matchRoute($method, $uri, $params = [])
    {
        $method = $this->formatMethod($method);
        $uri = $this->formatUri($uri);

        foreach ($this->routes[ $method ] ?? [] as $route => $handler) {
            $pattern = $route;

            $idDependent = strpos($pattern, '%id') !== false;
            if ($idDependent) {
                $pattern = str_replace('%id', '(\d+)', $pattern);
            }

            $matches = [];
            $pattern = str_replace('/', '\/', $pattern);
            $matched = preg_match('/^'.$pattern.'$/', $uri, $matches);

            if ($idDependent) {
                $params['Id'] = $matches[1] ?? 0;
            }

            if ($matched) {
                $handler( $params );
                return true;
            }
        }

        return false;
    }

    public function start()
    {
        $params = $_POST + $_GET + $_FILES;
        $method = $params['RequestMethod'] ?? $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (!$this->matchRoute($method, $uri, $params)) {
            $this->matchRoute('get', 'not_found');
        }
    }
}