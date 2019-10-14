<?php

namespace Model\Utils;

use Model\Utils\DatabaseConnection;
use App;
use PDO;
use PDOException;
use Exception;

class QueryBuilder
{
    private $params = [
        'TableName' => null,
        'FieldsMap' => null,
    ];

    private $query = [
        'action' => '',
        'where' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
    ];

    private $binds = [];

    private function addBind($value)
    {
        $key = ':bind'.count($this->binds);
        $this->binds[ $key ] = $value;

        return $key;
    }

    function __construct(array $params)
    {
        $this->params = $params + $this->params;
    }

    private function escape(string $value)
    {
        return '`'.$value.'`';
    }

    private function isSelect()
    {
        return strpos($this->query['action'], 'SELECT') !== false;
    }

    public function select()
    {
        $this->query['action'] = 'SELECT * FROM '.$this->escape($this->params['TableName']);
        return $this;
    }

    public function selectLastInsertId()
    {
        $this->query['action'] = 'SELECT LAST_INSERT_ID() AS Id';
        return $this;
    }

    public function delete()
    {
        $this->query['action'] = 'DELETE FROM '.$this->escape($this->params['TableName']);
        return $this;
    }

    public function update(array $data)
    {
        $values = [];
        foreach ($data as $field => $value) {
            $bindKey = $this->addBind($value);
            $values[] = $this->escape($this->unmapField($field)).' = '.$bindKey;
        }

        $this->query['action'] = (
            'UPDATE '.
            $this->escape($this->params['TableName']).
            ' SET '.implode(',', $values)
        );

        return $this;
    }

    public function insert(array $data)
    {
        $fields = [];
        $values = [];
        foreach ($data as $field => $value) {
            $fields[] = $this->escape($this->unmapField($field));
            $bindKey = $this->addBind($value);
            $values[] = $bindKey;
        }

        $this->query['action'] = (
            'INSERT INTO '.
            $this->escape($this->params['TableName']).
            ' ('.implode(',', $fields).') '.
            'VALUES ('.implode(',', $values).')'
        );

        return $this;
    }

    public function where(string $field, string $operator, $value)
    {
        if (is_array($value)) {
            $bindValues = [];
            foreach ($value as $item) {
                $bindValues[] = $this->addBind($item);
            }
            $bindValue = '('.implode(',', $bindValues).')';
        } else {
            $bindValue = $this->addBind($value);
        }

        $this->query['where'] = (
            ' WHERE ('.
            $this->escape($this->unmapField($field)).' '.
            $operator.' '.
            $bindValue.
            ')'
        );

        return $this;
    }

    public function orderBy(string $field, string $type = 'ASC')
    {
        $this->query['order'] = ' ORDER BY '.$this->escape($this->unmapField($field)).' '.$type;
        return $this;
    }

    public function limit(int $limit)
    {
        $this->query['limit'] = ' LIMIT '.intval($limit);
        return $this;
    }

    public function offset(int $offset)
    {
        $this->query['offset'] = ' OFFSET '.intval($offset);
        return $this;
    }

    private function mapField($field)
    {
        $map = $this->params['FieldsMap'] + ['id' => 'Id'];
        if (isset($map[ $field ])) {
            return $map[ $field ];
        }
        return $field;
    }

    private function unmapField($alias)
    {
        $map = $this->params['FieldsMap'] + ['id' => 'Id'];
        $map = array_flip($map);
        if (isset($map[ $alias ])) {
            return $map[ $alias ];
        }
        return $alias;
    }

    private function formatSelectResult($result)
    {
        if (empty($result) || !is_array($result)) {
            return [];
        }

        $items = [];
        foreach ($result as $row) {
            $item = [];
            foreach ($row as $field => $value) {
                $item[ $this->mapField($field) ] = $value;
            }

            $items[ $item['Id'] ] = $item;
        }

        return $items;
    }

    public function run()
    {
        try {
            $sql = implode(' ', $this->query);

            $con = DatabaseConnection::getConnection()->prepare($sql);
            $status = $con->execute($this->binds);

            if ($status && $this->isSelect()) {
                return $this->formatSelectResult($con->fetchAll(PDO::FETCH_ASSOC));
            }

            return $status;
        } catch (PDOException $e) {
            App::log('Query run PDO error: '.$e->getMessage());
            throw $e;
        }

        return false;
    }
}
