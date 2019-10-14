<?php

namespace Model;

class MoviesActors extends Base
{
    private $moviesId = null;
    private $actorsId = null;

    protected static function getQueryBuilderParams()
    {
        return [
            'TableName' => 'movies_actors',
            'FieldsMap' => self::getFieldsMap(),
        ];
    }

    protected static function getFieldsMap()
    {
        return [
            'movies_id' => 'MoviesId',
            'actors_id' => 'ActorsId',
        ];
    }

    public function setMoviesId($moviesId)
    {
        $this->moviesId = $moviesId;
        return $this;
    }

    public function getMoviesId()
    {
        return $this->moviesId;
    }

    public function setActorsId($actorsId)
    {
        $this->actorsId = $actorsId;
        return $this;
    }

    public function getActorsId()
    {
        return $this->actorsId;
    }
}