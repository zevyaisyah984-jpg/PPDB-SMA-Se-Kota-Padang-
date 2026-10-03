<?php
// Simple Router Class
class Router {
    private $routes = [];
    private $notFoundCallback;

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function any($path, $callback) {
        $this->routes['GET'][$path] = $callback;
        $this->routes['POST'][$path] = $callback;
    }

    public function notFound($callback) {
        $this->notFoundCallback = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path
        $basePath = defined('BASE_PATH') ? BASE_PATH : '/zonasi/public';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // Normalize URI
        $uri = $uri ?: '/';
        $uri = rtrim($uri, '/') ?: '/';

        // Check for exact match
        if (isset($this->routes[$method][$uri])) {
            return $this->call($this->routes[$method][$uri]);
        }

        // Check for pattern match with parameters
        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return $this->call($callback, $matches);
            }
        }

        // 404 Not Found
        if ($this->notFoundCallback) {
            return $this->call($this->notFoundCallback);
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }

    private function call($callback, $params = []) {
        if (is_string($callback)) {
            // Controller@method format
            list($controller, $method) = explode('@', $callback);
            $controllerFile = ROOT_PATH . 'app/Controllers/' . $controller . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $instance = new $controller();
                return call_user_func_array([$instance, $method], $params);
            }
        } elseif (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }
    }
}
