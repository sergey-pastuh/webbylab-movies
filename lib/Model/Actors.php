<?php

namespace Model;

class Actors extends Base
{
    private $name = null;

    protected static function getQueryBuilderParams()
    {
        return [
            'TableName' => 'actors',
            'FieldsMap' => self::getFieldsMap(),
        ];
    }

    protected static function getFieldsMap()
    {
        return [
            'name' => 'Name',
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
}