<?php

namespace Shmidt\Framework\Views;

class View
{
    public array $route = [];
    public string $view;

    public function __construct(array $route, string $view = '')
    {
        $this->route = $route;
        $this->view = $view;
    }

    public function render($data)
    {
        if (is_array($data)) {
            extract($data);
        }
        $fileView = SERVER . '/App/Views/' . $this->view . '.php';
        if (is_file($fileView)) {
            require $fileView;
        } else {
            echo '<h1> File view not found </h1>';
        }
    }
}
