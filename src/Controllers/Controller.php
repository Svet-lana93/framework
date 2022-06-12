<?php

namespace Shmidt\Framework\Controllers;

use Shmidt\Framework\Views\View;

abstract class Controller
{
    public array $route = [];
    public string $view;
    public array $data = [];

    public function __construct(array $route)
    {
        $this->route = $route;
        $this->view = $route[1]; //$this->>view = $route['action]
    }

    public function view(string $view, array $data = []): bool
    {
        $this->view = $view;
        $viewObj = new View($this->route, $this->view);
        $viewObj->render($data);
        return true;
    }
}
