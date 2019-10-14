<?php

namespace Controller\NotFound;

use Controller\Base;

class Show extends Base
{
    protected function action($params)
    {
        $this->view('NotFound/Show', [
            'title' => 'Страница не найдена',
        ]);
    }
}