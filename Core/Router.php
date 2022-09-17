<?php

namespace Core;

class Router
{
    private $controller = "HomeController";
    private $method = "index";
    private $param = [];

    public function __construct()
    {
        // Executa a função url() desta classe
        $router = $this->url();

        // Se existir um arquivo com o nome passado na URL..
        // // guarda na variável $controller
        // // limpa a primeira posição do array $router
        // Os caminhos devem utilizar "../" para sair da pasta "public"
        if (file_exists("../App/Controllers/" . ucfirst($router[0]) . ".php")) {
            $this->controller = $router[0];
            unset($router[0]);
        }

        // Busca a classe controladora em "App/Controllers" e instancia ela
        $class = "\\App\\Controllers\\" . ucfirst($this->controller);
        $object = new $class;

        // Se existir um segundo argumento na URL
        // // guarda na variável $method
        // // limpa a segunda posição do array $router
        if (isset($router[1]) && method_exists($class, $router[1])) {
            $this->method = $router[1];
            unset($router[1]);
        }

        // Se existir mais valores na variável $router
        // // guarda um array com esses valores na variável $param
        // // senão, guarda um array vazio na variável $param
        $this->param = $router ? array_values($router) : [];

        // Executa o método da classe controladora instanciada com os valores da variável $param como parâmetros
        call_user_func_array([$object, $this->method], $this->param);
    }

    public function url()
    {
        // O terceiro parâmetro da função filter_input se refere a index.php?url=$1 no arquivo .htaccess
        $url = explode("/", filter_input(INPUT_GET, "url", FILTER_SANITIZE_URL));

        return $url;
    }
}
