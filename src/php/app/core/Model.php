<?php

trait Model
{
    //you can only extends one Class but can add many traits
    //add trait Database to this Model
    use Database;

    // test connect to DB
    function test()
    {
        $query = "SELECT * FROM users";
        $result = $this->query($query);
        //show($result);
    }

    protected $limit = 10;
    protected $offset = 0;
    protected $order_type = "desc";
    protected $order_column = "id";

    //FINDALL query
    public function findAll()
    {
        //build query
        $query = "SELECT * FROM $this->table ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        //echo $query;

        return $this->query($query);
    }

    //WHERE query
    public function where($data, $data_not = [])
    {
        //grab all keys from array
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->table WHERE ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        //delete last symbols in string
        $query = trim($query, " && ");

        //build query
        $query .= " ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        $data = array_merge($data, $data_not);
        //echo $query;

        return $this->query($query, $data);
    }

    //FIRST query, get first item
    public function first($data, $data_not = [])
    {
        //grab all keys from array
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->table WHERE ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        //delete last symbols in string
        $query = trim($query, " && ");

        //build query
        $query .= " LIMIT $this->limit OFFSET $this->offset";
        $data = array_merge($data, $data_not);
        //echo $query;

        $result = $this->query($query, $data);
        if ($result) {
            return $result[0];
        }

        return false;
    }

    //INSERT query, add new row to db
    public function insert($data)
    {
        //remove unwanted columns in data
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        //grab all keys from array
        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(", ", $keys) . ") VALUES (:" . implode(", :", $keys) . ")";
        //echo $query;

        $this->query($query, $data);

        return false;
    }

    //UPDATE query
    public function update($id, $data, $id_column = 'id')
    {
        //remove unwanted columns in data
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        //grab all keys from array
        $keys = array_keys($data);
        $query = "UPDATE $this->table SET ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . ", ";
        }

        //delete last symbols in string
        $query = trim($query, ", ");

        //build query
        $query .= "  WHERE $id_column = :$id_column";
        //echo $query;

        $data[$id_column] = $id;
        $this->query($query, $data);

        return false;
    }

    //DELETE query
    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "DELETE FROM $this->table WHERE $id_column = :$id_column";
        //echo $query;

        $this->query($query, $data);

        return false;
    }
}
