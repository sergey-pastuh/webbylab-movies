<?php

namespace Controller\Movies\Import;

use Controller\Base;

class Show extends Base
{
    protected function action($params)
    {
        $this->view('Movies/Import/Show', [
            'title' => 'Импортировать фильмы',
        ]);
    }
}