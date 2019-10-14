<?php

namespace Controller;

use App;
use Exception;

class Base
{
    private $errorsCount = 0;

    public function redirect($location)
    {
        header('Location: /'.$location);
        die();
    }

    public function notFound()
    {
        $this->redirect('not_found');
    }

    public function start($params)
    {
        try {
            $this->clearParams($params);
            $this->verifyParams($params);

            if ($this->errorsCount === 0) {
                $this->action($params);
            } else {
                $this->rememberParams($params);
            }
        } catch (Exception $e) {
            App::log('Controller error: '.$e->getMessage());
            $this->serverError();
        }

        $this->afterAction($params);
    }

    protected function escape($str)
    {
        return htmlentities($str);
    }

    protected function view($view, $data = [])
    {
        $content = function () use ($view, $data) {
            $params = $this->ejectRememberedParams();
            extract($data);
            include_once VIEW.'/'.$view.'.php';
        };

        extract($data);
        $messages = $this->ejectMessages();

        include_once VIEW.'/Base.php';
    }

    protected function rememberParams($params)
    {
        $_SESSION['rememberedParams'] = $params;
    }

    protected function ejectRememberedParams()
    {
        $params = $_SESSION['rememberedParams'] ?? [];
        unset($_SESSION['rememberedParams']);
        return $params;
    }

    protected function successMessage($message)
    {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function errorMessage($message)
    {
        $_SESSION['messages']['error'][] = $message;
        $this->errorsCount++;
    }

    protected function serverError()
    {
        $this->errorMessage('На сервере произошла ошибка');
    }

    protected function ejectMessages()
    {
        $messages = [
            'success' => [],
            'error' => [],
        ];

        $messages = ($_SESSION['messages'] ?? []) + $messages;
        $messages['error'] = array_unique($messages['error']);

        unset($_SESSION['messages']);
        return $messages;
    }

    protected function clearParams(&$params) {
        foreach ($params as $key => $param) {
            if (is_null($param)) {
                unset($params[ $key ]);
                continue;
            }

            if (is_array($param)) {
                $this->clearParams($param);
                continue;
            }

            $params[ $key ] = trim($param);
        }
    }

    protected function verifyParams($params)
    {
    }

    protected function action($params)
    {
    }

    protected function afterAction($params)
    {
    }
}