<?php

namespace Huckabuild\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Huckabuild\Services\LatteService;

class BaseController
{
    protected $request;
    protected $response;
    protected $view;

    public function __construct(Request $request, Response $response, LatteService $view)
    {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
        $this->view->setRequest($request);
    }

    /**
     * Render a view with the given data
     */
    protected function view(string $template, array $data = []): Response
    {
        return $this->view->render($this->response, $template, $data);
    }

    /**
     * Redirect to a given URL
     */
    protected function redirect(string $url): Response
    {
        return $this->response
            ->withHeader('Location', $url)
            ->withStatus(302);
    }

    /**
     * Get POST data from the request
     */
    protected function post(): array
    {
        return $this->request->getParsedBody();
    }

    /**
     * Get GET data from the request
     */
    protected function get(): array
    {
        return $this->request->getQueryParams();
    }

    /**
     * Get a specific POST parameter
     */
    protected function input(string $key, $default = null)
    {
        $data = $this->post();
        return $data[$key] ?? $default;
    }

    /**
     * Get a specific GET parameter
     */
    protected function query(string $key, $default = null)
    {
        $data = $this->get();
        return $data[$key] ?? $default;
    }

    /**
     * Flash a message to the session
     */
    protected function with(string $key, string $value): self
    {
        $_SESSION['flash'][$key] = $value;
        return $this;
    }

    /**
     * Get flash messages
     */
    protected function getFlash(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }
} 