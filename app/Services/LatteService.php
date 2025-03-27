<?php

namespace Huckabuild\Services;

use Latte\Engine;
use Latte\Loaders\FileLoader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LatteService
{
    private Engine $latte;
    private ?ServerRequestInterface $request = null;

    public function __construct()
    {
        $this->latte = new Engine();
        $this->latte->setLoader(new FileLoader(__DIR__ . '/../../resources/views'));
        $this->latte->setTempDirectory(__DIR__ . '/../../storage/cache/latte');
        
        // Create cache directory if it doesn't exist
        if (!is_dir(__DIR__ . '/../../storage/cache/latte')) {
            mkdir(__DIR__ . '/../../storage/cache/latte', 0755, true);
        }

        // Add default filters
        $this->latte->addFilter('noescape', fn($s) => $s);
    }

    public function render(ResponseInterface $response, string $template, array $data = []): ResponseInterface
    {
        // Add common data
        $data['theme_mode'] = $_ENV['THEME_MODE'] ?? 'light';
        $data['page_title'] = $data['page_title'] ?? 'Huckabuild Site';
        
        // Add request and auth data if request is set
        if ($this->request !== null) {
            $data['request'] = $this->request;
            
            // Add auth data from request attributes if available
            $auth = $this->request->getAttribute('auth');
            if ($auth !== null) {
                $data['auth'] = $auth;
            }
        }
        
        // Only append .latte if it's not already present
        $templateName = str_ends_with($template, '.latte') ? $template : $template . '.latte';
        $html = $this->latte->renderToString($templateName, $data);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }
}