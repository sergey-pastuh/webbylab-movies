<?php

namespace Model;

class Movies extends Base
{
	private $name = null;
	private $format = null;
	private $releaseYear = null;

    protected static function getQueryBuilderParams()
    {
        return [
            'TableName' => 'movies',
            'FieldsMap' => self::getFieldsMap(),
        ];
    }

    protected static function getFieldsMap()
    {
        return [
            'name' => 'Name',
            'format' => 'Format',
            'release_year' => 'ReleaseYear',
        ];
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setReleaseYear($releaseYear)
    {
        $this->releaseYear = $releaseYear;
        return $this;
    }

    public function getReleaseYear()
    {
        return $this->releaseYear;
    }

    public static function getMoviesByParams($params)
    {
        $moviesQuery = Movies::query()->select();
        if(!empty($params['Id'])) {
            $moviesQuery->where('Id', '=', $params['Id']);
        } elseif (!empty($params['SearchByMovieName'])) {
            $moviesQuery->where('Name', 'LIKE', '%'.$params['SearchByMovieName'].'%');
        }
        $movies = $moviesQuery
            ->orderBy('Name')
            ->run();

        if (!empty($movies)) {
            $moviesActors = MoviesActors::query()
                ->select()
                ->where('MoviesId', 'IN', array_column($movies, 'Id'))
                ->run();

            $actors = Actors::query()
                ->select()
                ->where('Id', 'IN', array_column($moviesActors, 'ActorsId'))
                ->run();

            $groupMoviesActors = [];
            foreach ($moviesActors as $movieActor) {
                $groupMoviesActors[ $movieActor['MoviesId'] ][] = $movieActor['ActorsId'];
            }
            unset($moviesActors);

            foreach ($movies as $movieId => $movie) {
                $movie['Actors'] = [];
                foreach ($groupMoviesActors[ $movieId ] ?? [] as $actorId) {
                    if (isset($actors[ $actorId ])) {
                        $movie['Actors'][] = $actors[ $actorId ]['Name'];
                    }
                }

                $movies[ $movieId ] = $movie;
            }

            if (!empty($params['SearchByActorName'])) {
                foreach ($movies as $movieId => $movie) {
                    $skipMovie = true;
                    foreach ($movie['Actors'] as $actorName) {
                        if (strpos($actorName, $params['SearchByActorName']) !== false) {
                            $skipMovie = false;
                            break;
                        }
                    }
                    if ($skipMovie) {
                        unset($movies[ $movieId ]);
                    }
                }
            }
        }

        return $movies;
    }

    public static function createMovieFromParams($params)
    {
        $movie = new Movies();
        $movie
            ->setName($params['Name'])
            ->setFormat($params['Format'])
            ->setReleaseYear($params['ReleaseYear'])
            ->save();

        $actors = array_unique($params['Actors']);
        $existedActors = Actors::query()
            ->select()
            ->where('Name', 'IN', $actors)
            ->run();

        $existedActorsMap = [];
        foreach ($existedActors as $existedActor) {
            $existedActorsMap[ $existedActor['Name'] ] = $existedActor['Id'];
        }

        foreach ($actors as $actorName) {
            if (isset($existedActorsMap[ $actorName ])) {
                $actorId = $existedActorsMap[ $actorName ];
            } else {
                $actor = new Actors();
                $actor
                    ->setName($actorName)
                    ->save();

                $actorId = $actor->getId();
            }

            $movieActor = new MoviesActors();
            $movieActor
                ->setMoviesId($movie->getId())
                ->setActorsId($actorId)
                ->save();
        }
    }
}