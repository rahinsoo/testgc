<?php

namespace Core;

final class Router {

    private array $getRoutes = [];
    private array $postRoutes = [];
    private array $getRegexRoutes = [];
    private array $postRegexRoutes = [];
    private array $putRoutes = [];
    private array $deleteRoutes = [];
    private array $patchRoutes = [];
    private array $deleteRegexRoutes = [];
    private array $putRegexRoutes = [];
    private array $patchRegexRoutes = [];

    public function get(string $path, callable $handler): void {
        if (str_contains($path, '(')) {
            $this->getRegexRoutes[$path] = $handler;
        } else {
            $this->getRoutes[$path] = $handler;
        }
    }

    public function post(string $path, callable $handler) :void {
        if (str_contains($path, '(')) {
            $this->postRegexRoutes[$path] = $handler;
        } else {
            $this->postRoutes[$path] = $handler;
        }
    }

    public function getRegex(string $pattern, callable $handler) : void {
        $this->getRegexRoutes[$pattern] = $handler;
    }

    public function put(string $path, callable $handler): void
    {
        if (str_contains($path, '(')) {
            $this->putRegexRoutes[$path] = $handler;
        } else {
            $this->putRoutes[$path] = $handler;
        }
    }

    public function patch(string $path, callable $handler): void
    {
        if (str_contains($path, '(')) {
            $this->patchRegexRoutes[$path] = $handler;
        } else {
            $this->patchRoutes[$path] = $handler;
        }
    }

    public function delete(string $path, callable $handler): void
    {
        if (str_contains($path, '(')) {
            $this->deleteRegexRoutes[$path] = $handler;
        } else {
            $this->deleteRoutes[$path] = $handler;
        }
    }

    public function dispatch (Request $request, Response $response) : void {
        $path = $request->path();
        $method = $request->method();

        if ($method === 'GET') {
            foreach ($this->getRegexRoutes as $pattern => $handler) {
                // Transforme le pattern en regex valide
                $regex = '#^' . $pattern . '$#';
                if (preg_match($regex, $path, $matches)) {
                    $handler($matches);  // ← Passe les matches à la closure
                    return;
                }
            }
        }

        if ($method === 'GET' && isset($this->getRoutes[$path])) {
            $this->getRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'POST') {
            foreach ($this->postRegexRoutes as $pattern => $handler) {
                $regex = '#^' . $pattern . '$#';
                if (preg_match($regex, $path, $matches)) {
                    $handler($matches);
                    return;
                }
            }
        }

        if ($method === 'POST' && isset($this->postRoutes[$path])) {
            $this->postRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'PUT') {
            foreach ($this->putRegexRoutes as $pattern => $handler) {
                // Transforme le pattern en regex valide
                $regex = '#^' . $pattern . '$#';
                if (preg_match($regex, $path, $matches)) {
                    $handler($matches);  // ← Passe les matches à la closure
                    return;
                }
            }
        }

        if ($method === 'PUT' && isset($this->putRoutes[$path])) {
            $this->putRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'PATCH') {
            foreach ($this->patchRegexRoutes as $pattern => $handler) {
                // Transforme le pattern en regex valide
                $regex = '#^' . $pattern . '$#';
                if (preg_match($regex, $path, $matches)) {
                    $handler($matches);  // ← Passe les matches à la closure
                    return;
                }
            }
        }

        if ($method === 'PATCH' && isset($this->patchRoutes[$path])) {
            $this->patchRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'DELETE') {
            foreach ($this->deleteRegexRoutes as $pattern => $handler) {
                // Transforme le pattern en regex valide
                $regex = '#^' . $pattern . '$#';
                if (preg_match($regex, $path, $matches)) {
                    $handler($matches);  // ← Passe les matches à la closure
                    return;
                }
            }
        }

        if ($method === 'DELETE' && isset($this->deleteRoutes[$path])) {
            $this->deleteRoutes[$path]($request, $response);
            return;
        }

        http_response_code(404);
        echo "Page non trouvée";

        /*$response->render('not-found', [], 404);*/
    }
}