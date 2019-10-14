<?php

namespace Controller\Movies\Creation;

use Controller\Base;

class Show extends Base
{
    protected function action($params)
    {
        $this->view('Movies/Creation/Show', [
            'title' => 'Добавить фильм',
        ]);
    }
}