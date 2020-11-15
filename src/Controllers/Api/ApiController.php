<?php

namespace App\Controllers\Api;

use Exception;
use JsonException;
use RuntimeException;

abstract class ApiController
{
    public string $apiName = '';

    public array $requestUri = [];
    public array $requestParams = [];
    public array $formData = []; //Хранит данные из body

    protected string $method = ''; //GET|POST|PUT|DELETE
    protected string $action = ''; //Название метода для выполнения

    /**
     * ApiController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        //Массив GET параметров разделенных слешем
        $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->requestParams = $_REQUEST;

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->formData = $this->getFormData($this->method);
    }

    public function run()
    {
        //Первые 2 элемента массива URI должны быть "api" и название таблицы
        if (array_shift($this->requestUri) !== 'api' || array_shift($this->requestUri) !== $this->apiName) {
            throw new RuntimeException('API Not Found', 404);
        }

        //Определение действия для обработки
        $this->action = $this->getAction();

        //Если метод(действие) определен в дочернем классе API
        if (method_exists($this, $this->action)) {
            return $this->{$this->action}();
        }

        throw new RuntimeException('Invalid Method', 405);
    }

    protected function getAction(): string
    {
        $method = $this->method;
        switch ($method) {
            case 'GET':
                return 'indexAction';
            case 'POST':
                return 'createAction';
            case 'PUT':
                return 'updateAction';
            case 'DELETE':
                return 'deleteAction';
            default:
                return '';
        }
    }

    /**
     * @param $method
     * @return array
     * @throws JsonException
     */
    protected function getFormData($method): array
    {
        if ($method === 'GET') {
            return $_GET;
        }

        $inputJSON = file_get_contents('php://input');
        return json_decode($inputJSON, TRUE, 512, JSON_THROW_ON_ERROR);
    }

    abstract protected function indexAction();

    abstract protected function createAction();

    abstract protected function updateAction();

    abstract protected function deleteAction();
}