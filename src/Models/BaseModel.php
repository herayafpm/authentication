<?php

namespace Raydragneel\Authentication\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $DBGroup          = 'default';
    protected $message;

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setDBGroup($db)
    {
        $this->DBGroup = $db;
        $this->db = db_connect($db, true);
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }
    public function getTableAs()
    {
        return $this->tableAs;
    }
    public function restore($id, $primaryKey = null)
    {
        if ($this->useSoftDeletes) {
            if (!empty($primaryKey)) {
                $this->primaryKey = $primaryKey;
            }
            return $this->update($id, [$this->deletedField => null]);
        }
        return true;
    }

    public function with($withs = [])
    {
        foreach ($withs as $with) {
            if (isset($with['table_as'])) {
                $this->join("{$with['table']} as {$with['table_as']}", "{$this->getTable()}.{$with['link']} = {$with['table_as']}.{$with['on']}");
            } else {
                $this->join("{$with['table']}", "{$this->getTable()}.{$with['link']} = {$with['table']}.{$with['on']}");
            }
        }
        return $this;
    }

    public function getReturnType()
    {
        return $this->returnType;
    }

    public function filter($limit, $start, $order, $ordered, $params = [])
    {
        $builder = $this;
        $order = $this->filterData($order);
        $builder->orderBy($order, $ordered);

        if (isset($params['select'])) {
            if (is_array($params['select'])) {
                $selects = [];
                foreach ($params['select'] as $value) {
                    array_push($selects, $this->filterData($value));
                }
                $builder->select(implode(",", $selects));
            } else {
                $builder->select($params['select']);
            }
        } else {
            $builder->select("{$this->table}.*");
        }

        if (isset($params['join'])) {
            foreach ($params['join'] as $key => $value) {
                $builder->join($key, $this->table . "." . $value['on'] . " = " . $value['link'], $value['type'] ?? 'LEFT');
            }
        }
        if (isset($params['join_custom'])) {
            foreach ($params['join_custom'] as $key => $value) {
                $builder->join($key, $value['query'], $value['type'] ?? 'LEFT');
            }
        }
        if (isset($params['where'])) {
            $where = $params['where'];
            foreach ($where as $key => $value) {
                $pos = strpos($key, '.');
                if ($pos === false) {
                    unset($where[$key]);
                    $where["{$this->table}.{$key}"] = $value;
                }
            }
            $builder->where($where);
        }
        if (isset($params['where_query'])) {
            $where_query = $params['where_query'];
            foreach ($where_query as $q) {
                $builder->where($q);
            }
        }
        if (isset($params['like'])) {
            foreach ($params['like'] as $key => $value) {
                $pos = strpos($key, '.');
                if ($pos === false) {
                    $key = "{$this->table}.{$key}";
                }
                $builder->like($key, $value);
            }
        }
        if (isset($params['orLike'])) {
            foreach ($params['orLike'] as $key => $value) {
                $pos = strpos($key, '.');
                if ($pos === false) {
                    $key = "{$this->table}.{$key}";
                }
                $builder->orLike($key, $value);
            }
        }
        if (isset($params['withDeleted'])) {
            $builder->withDeleted();
        }
        if ($limit > 0) {
            return $builder->findAll($limit, $start); // Untuk menambahkan query LIMIT
        } else {
            return $builder->findAll();
        }
    }
    public function count_all($params = [])
    {
        $builder = $this;

        if (isset($params['select'])) {
            if (is_array($params['select'])) {
                $selects = [];
                foreach ($params['select'] as $value) {
                    array_push($selects, $this->filterData($value));
                }
                $builder->select(implode(",", $selects));
            } else {
                $builder->select($params['select']);
            }
        } else {
            $builder->select("{$this->table}.*");
        }
        if (isset($params['join'])) {
            foreach ($params['join'] as $key => $value) {
                $builder->join($key, $this->table . "." . $value['on'] . " = " . $value['link'], $value['type'] ?? 'LEFT');
            }
        }
        if (isset($params['join_custom'])) {
            foreach ($params['join_custom'] as $key => $value) {
                $builder->join($key, $value['query'], $value['type'] ?? 'LEFT');
            }
        }
        if (isset($params['where'])) {
            $where = $params['where'];
            foreach ($where as $key => $value) {
                $pos = strpos($key, '.');
                if ($pos === false) {
                    unset($where[$key]);
                    $where["{$this->table}.{$key}"] = $value;
                }
            }
            $builder->where($where);
        }
        if (isset($params['where_query'])) {
            $where_query = $params['where_query'];
            foreach ($where_query as $q) {
                $builder->where($q);
            }
        }
        if (isset($params['like'])) {
            foreach ($params['like'] as $key => $value) {
                $pos = strpos($key, '.');
                if ($pos === false) {
                    $key = "{$this->table}.{$key}";
                }
                $builder->like($key, $value);
            }
        }
        if (isset($params['orLike'])) {
            foreach ($params['orLike'] as $key => $value) {
                $pos = strpos($key, '.');
                if ($pos === false) {
                    $key = "{$this->table}.{$key}";
                }
                $builder->orLike($key, $value);
            }
        }
        if (isset($params['withDeleted'])) {
            $builder->withDeleted();
        }
        return $builder->countAllResults();
    }

    public function filterData($key)
    {
        $key = $this->alias_field[$key] ?? $key;
        $pos = strpos($key, '.');
        if ($pos === false) {
            $key = "{$this->table}.{$key}";
        }
        return $key;
    }

    public function findEncode($id_encode,$withDeleted = false)
    {
        $id = base64_decode(urldecode($id_encode));
        if($this->useSoftDeletes){
            return $this->withDeleted($withDeleted)->find($id);
        }else{
            return $this->find($id);
        }
    }
}