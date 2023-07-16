<?php

namespace App;

use App\Controllers\HealthController;
use App\Lib\Excecoes\ErroInternoException;
use App\Utils\Data;
use App\Utils\Validacao;
use Swoole\MySQL\Exception;
use const PATH;
use function GuzzleHttp\json_encode;

class App
{

    private $controller;
    private $controllerFile;
    private $action;
    private $params;
    public $controllerName;
    private $ajax;
    private $nomeAdmin;
    private $https;
    private $host;

    /** Seta constantes do sistema e chama o método url() que é responsável 
     *  por idetificar o controlador, método e paramentro na URL */
    public function __construct()
    {
        $this->checkLoadBalancer();

        session_start();
        $this->setNomes();

        define('CNPJLOGIN', '99999999999999');
        define('PROTOCOL', $this->https);
        define('APP_HOST_SITE', $this->host);
        define('APP_HOST', $this->host);
        define('PATH', realpath('./'));

        Data::setTimeZone();
        $this->url();
    }

    /**
     * Responsável por instanciar a classe controladora e chamar o método, passando
     * os parametros, se houverem
     * @return void
     * @throws Exception
     * @throws ErroInternoException
     */
    public function run()
    {

        if ($this->ajax)
        {
            header("Access-Control-Allow-Origin: *");
            http_response_code(200);
        }

        if ($this->controller)
        {
            $this->controllerName = ucwords($this->controller) . 'Controller';
            $this->controllerName = preg_replace('/[^a-zA-Z]/i', '', $this->controllerName);
        }
        else
        {
            $this->controllerName = "HealthController";
        }

        $this->controllerFile = $this->controllerName . '.php';
        $this->action = preg_replace('/[^a-zA-Z]/i', '', $this->action);

        if (!$this->controller)
        {
            $this->controller = new HealthController($this);
            $this->controller->index();
            die;
        }

        if (!file_exists(PATH . '/App/Controllers/' . $this->controllerFile))
        {
            http_response_code(404);
            echo json_encode(["code" => 404, "message" => "Metodo nao encontrado - 1"]);
            die;
        }

        $nomeClasse = "\\App\\Controllers\\" . $this->controllerName;
        $objetoController = new $nomeClasse($this);

        if (!class_exists($nomeClasse))
        {
            http_response_code(404);
            echo json_encode(["code" => 404, "message" => "Metodo nao encontrado - 2"]);
            die;
        }

        if (method_exists($objetoController, $this->action))
        {
            $objetoController->{$this->action}($this->params);
            return;
        }
        else if (!$this->action && method_exists($objetoController, 'index'))
        {
            $objetoController->index($this->params);
            return;
        }
        else
        {
            if ($this->ajax)
            {
                echo json_encode(array("code" => 500, "Message" => "Erro interno do servidor"));
                die;
            }
            http_response_code(404);
            echo json_encode(["code" => 404, "message" => "Metodo nao encontrado - 3"]);
            die;
        }
        http_response_code(500);
        echo json_encode(["code" => 500, "message" => "Erro interno servidor"]);
        die;
    }

    /** Somente seta as propriedades controler, actions e param que vieram via get */
    public function url()
    {

        $get = Validacao::getFilterRequisicao(INPUT_GET);
        if (isset($get['url']))
        {

            $path = $get['url'];
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);
            $path = explode('/', $path);

            $this->controller = $this->verificaArray($path, 0);
            $this->action = $this->verificaArray($path, 1);

            $ajax = strtolower(trim($this->controller));
            $this->ajax = false;
            if ($ajax === 'ajax')
            {
                $this->ajax = true;
                unset($path[0]);
                $path = array_values($path);

                $this->controller = $this->verificaArray($path, 0);
                $this->action = $this->verificaArray($path, 1);
            }


            if ($this->verificaArray($path, 2))
            {
                unset($path[0]);
                unset($path[1]);
                $this->params = array_values($path);
            }

            // se houver parametros eu os trato
            if ($this->params != null && count($this->params) >= 1)
            {
                // se algum dos paramentros conter "=" eu trato como key=valor
                foreach ($this->params as $key => $value)
                {
                    $viaGet = explode("=", $value);
                    if (count($viaGet) >= 2)
                    {
                        unset($this->params[$key]);
                        $this->params[$viaGet[0]] = $viaGet[1];
                    }
                }

                // se a variavel get tiver mais parametros eu os adiciono a this params
                foreach ($get as $key => $g)
                {
                    if ($key != "url")
                        $this->params[$key] = $g;
                }
            }
        }
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getAjax()
    {
        return $this->ajax;
    }

    private function verificaArray($array, $key)
    {
        if (isset($array[$key]) && !empty($array[$key]))
        {
            return $array[$key];
        }
        return null;
    }

    private function setNomes()
    {

        $this->host = $_SERVER['HTTP_HOST'];
        $this->https = "https";
        $this->nomeAdmin = "";

        if (strpos(strtolower($this->host), 'ocalhost', 1))
        {
            $this->https = "http";
            $this->host = $_SERVER['HTTP_HOST'] . "/$this->nomeAdmin";
        }
    }

    private function checkLoadBalancer()
    {

        //Verifico se veio do loadBalancer da AWS. Se sim, só retorno o o código 200 falando que está ok, para não criar sessão atoa 
        if ($_SERVER['HTTP_USER_AGENT'] == "ELB-HealthChecker/2.0" ||
                strpos($_SERVER['HTTP_USER_AGENT'], "HealthChecker", 1))
        {

            http_response_code(200);
            die;
        }
    }

}
