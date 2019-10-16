<?php

namespace Controller\Movies\Import;

use Model\Movies;
use Controller\Base;

class Create extends Base
{
    protected function verifyParams($params)
    {
        if (empty($params['ImportFile'])) {
            $this->errorMessage('Сначала выберите файл');
        } else {
            $file = $params['ImportFile'];

            if (
                empty($file['tmp_name']) ||
                !file_exists($file['tmp_name'])
            ) {
                $this->errorMessage('Не удалось загрузить файл');
                return;
            }

            if (
                empty($file['size']) ||
                $file['size'] > 20971520
            ) {
                $this->errorMessage('Некоректный размер файла. Максимальный размер файла - 20мб');
            }

            if (
                empty($file['type']) ||
                $file['type'] != 'text/plain' ||
                empty($file['name']) ||
                pathinfo($file['name'], PATHINFO_EXTENSION) != 'txt'
            ) {
                $this->errorMessage('Некоректный тип файла. Допускаются только файлы формата txt');
            }
        }
    }

    protected function action($params)
    {
        $text = file_get_contents($params['ImportFile']['tmp_name']);

        $pattern = (
            '/'.
            'Title: ([^\n]+)\s*\n'.
            'Release Year: (\d{4})\s*\n'.
            'Format: (VHS|DVD|Blu-Ray)\s*\n'.
            'Stars: ([^\n]+)\s*\n'.
            '/'
        );

        $matches = [];
        preg_match_all($pattern, $text, $matches);

        $names = $matches[1] ?? [];
        $years = $matches[2] ?? [];
        $formats = $matches[3] ?? [];
        $stars = $matches[4] ?? [];

        $movies = [];
        if (
            !empty($names) &&
            count(array_unique([
                count($names),
                count($years),
                count($formats),
                count($stars)
            ])) == 1
        ) {
            for ($i = 0; $i < count($names); $i++) {
                $movie = [
                    'Name' => $names[ $i ] ?? '',
                    'Format' => $formats[ $i ] ?? '',
                    'ReleaseYear' => $years[ $i ] ?? '',
                    'Actors' => explode(',', $stars[ $i ] ?? ''),
                ];

                $this->clearParams($movie);

                foreach ($movie['Actors'] as $actorName) {
                    if (mb_strlen($actorName) < 3 || mb_strlen($actorName) > 255) {
                        $movie['Actors'] = [];
                        break;
                    }
                }

                if (
                    mb_strlen($movie['Name']) >= 1 &&
                    mb_strlen($movie['Name']) <= 255 &&
                    in_array($movie['Format'], ['VHS', 'DVD', 'Blu-Ray']) &&
                    preg_match('/^\d\d\d\d$/', $movie['ReleaseYear']) &&
                    !empty($movie['Actors'])
                ) {
                    $movies[] = $movie;
                }
            }
        }

        if (empty($movies)) {
            $this->errorMessage('Файл не содержит корректно заданых фильмов');
            return;
        } 

        $existedMovies = Movies::getMoviesByParams();
        $skippedMoviesCount = 0;
        foreach ($movies as $movie) {
            if (Movies::isMovieInList($movie, $existedMovies)) {
                $skippedMoviesCount++;
            } else {
                Movies::createMovieFromParams($movie);
                $existedMovies[] = $movie;
            }
        }

        if ($skippedMoviesCount) {
            $this->errorMessage($skippedMoviesCount. ' фильмов пропущено - фильмы с такими данными уже существуют');
        }

        $addedMoviesCount = count($movies) - $skippedMoviesCount;
        if ($addedMoviesCount) {
            $this->successMessage($addedMoviesCount.' фильмов успешно добавлено');
        }
    }

    protected function afterAction($params)
    {
        $this->redirect('movies/import');
    }
}