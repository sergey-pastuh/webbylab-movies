<?php

namespace Controller\Movies;

use Controller\Base;
use Model\Movies;

class Index extends Base
{
    private $movies = [];

    protected function verifyParams($params)
    {
        if (
            !empty($params['SearchByMovieName']) &&
            mb_strlen($params['SearchByMovieName']) > 255
        ) {
            $this->errorMessage('Некорректный поисковый запрос');
        }

        if (
            !empty($params['SearchByActorName']) &&
            mb_strlen($params['SearchByActorName']) > 255
        ) {
            $this->errorMessage('Некорректный поисковый запрос');
        }
    }

    protected function action($params)
    {
        $this->movies = Movies::getMoviesByParams($params);
    }

    protected function afterAction($params)
    {
        $this->view('Movies/Index', [
            'title' => 'Список фильмов',
            'movies' => $this->movies,
            'searchByMovieName' => $params['SearchByMovieName'] ?? '',
            'searchByActorName' => $params['SearchByActorName'] ?? '',
        ]);
    }
}