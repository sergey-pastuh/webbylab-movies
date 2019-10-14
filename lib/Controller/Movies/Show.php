<?php

namespace Controller\Movies;

use Model\Movies;
use Controller\Base;

class Show extends Base
{
    private $movie = null;

    protected function verifyParams($params)
    {
        if (empty($params['Id'])) {
            $this->errorMessage('Некорректный идентификатор фильма');
        }
    }

    protected function action($params)
    {
        $movies = Movies::query()
            ->select()
            ->where('Id', '=', $params['Id'])
            ->run();

        $this->movie = array_shift($movies);
    }

    protected function afterAction($params)
    {
        $movie = $this->movie;
        if (empty($movie)) {
            $this->notFound();
        }

        $this->view('Movies/Show', [
            'title' => 'Фильм "'.$movie['Name'].'"',
            'movie' => $movie,
        ]);
    }
}