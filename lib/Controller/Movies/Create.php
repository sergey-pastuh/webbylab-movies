<?php

namespace Controller\Movies;

use Controller\Base;
use Model\Movies;

class Create extends Base
{
    protected function verifyParams($params)
    {
        if (
            empty($params['Name']) ||
            mb_strlen($params['Name']) < 1 ||
            mb_strlen($params['Name']) > 255
        ) {
            $this->errorMessage('Некорректное имя фильма');
        }

        if (
            empty($params['Format']) ||
            !in_array($params['Format'], ['VHS', 'DVD', 'Blu-Ray'])
        ) {
            $this->errorMessage('Некорректный формат фильма');
        }

        if (
            empty($params['ReleaseYear']) ||
            !preg_match('/^\d\d\d\d$/', $params['ReleaseYear'])
        ) {
            $this->errorMessage('Некорректный год создания фильма');
        }

        if (empty($params['Actors'])) {
            $this->errorMessage('У фильма должен быть хотя бы один актер');
        } else {
            foreach ($params['Actors'] as $actorName) {
                if (empty($actorName)) {
                    $this->errorMessage('Имя актера не может быть пустым');
                } elseif (
                    mb_strlen($actorName) < 3 ||
                    mb_strlen($actorName) > 255
                ) {
                    $this->errorMessage($actorName.' - некорректное имя актера');
                }
            }
        }
    }

    protected function action($params)
    {
        Movies::createMovieFromParams($params);
        $this->successMessage('Фильм успешно добавлен');
    }

    protected function afterAction($params)
    {
        $this->redirect('movies/creation');
    }
}