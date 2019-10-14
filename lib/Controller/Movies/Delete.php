<?php

namespace Controller\Movies;

use Controller\Base;
use Model\Movies;

class Delete extends Base
{
    protected function verifyParams($params)
    {
        if (empty($params['Id'])) {
            $this->errorMessage('Некорректный идентификатор фильма');
        }
    }

    protected function action($params)
    {
        Movies::query()
            ->delete()
            ->where('Id', '=', $params['Id'])
            ->run();

        $this->successMessage('Фильм успешно удален');
    }

    protected function afterAction($params)
    {
        $this->redirect('movies');
    }
}