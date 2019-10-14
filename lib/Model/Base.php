<?php

namespace Model;

use Model\Utils\QueryBuilder;

abstract class Base
{
    protected $id = null;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    abstract protected static function getQueryBuilderParams();
    abstract protected static function getFieldsMap();

    public static function query()
    {
        return new QueryBuilder(static::getQueryBuilderParams());
    }

	public function save()
	{
        $data = [];
        foreach (static::getFieldsMap() as $field => $alias) {
            $getter = 'get'.$alias;
            $value = $this->$getter();
            if (!is_null($value)) {
                $data[ $alias ] = $value;
            }
        }

        if (empty($data)) {
            return false;
        }

        if ($this->getId()) {
            self::query()
                ->update($data)
                ->where(['Id' => $this->getId()])
                ->run();
        } else {
            self::query()
                ->insert($data)
                ->run();

            $result = self::query()
                ->selectLastInsertId()
                ->run();

            $row = array_shift($result);
            $this->setId($row['Id'] ?? null);
        }

        return true;
	}

	public function remove()
	{
        if ($this->getId()) {
            self::query()
                ->delete()
                ->where(['Id' => $this->getId()])
                ->run();
        }
	}
}
