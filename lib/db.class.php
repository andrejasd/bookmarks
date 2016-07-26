<?php

class DB{

    protected $connection;

    public function __construct($host, $user, $password, $db_name){
        $this->connection = new mysqli($host, $user, $password, $db_name);

        if( mysqli_connect_error() ){
            throw new Exception('Could not connect to DB');
        }
    }

    public function query($sql){  // сделать всему escape???
        if ( !$this->connection ){
            return false;
        }

        $result = $this->connection->query($sql);

        if ( mysqli_error($this->connection) ){
            throw new Exception(mysqli_error($this->connection));
        }

        if ( is_bool($result) ){
            return $result;
        }

        $data = array();
        while( $row = mysqli_fetch_assoc($result) ){
            $data[] = $row;
        }
        return $data;
    }

    public function multi_query($sql){
        if ( !$this->connection ){
            return false;
        }

        $result = $this->connection->multi_query($sql);

        if ( mysqli_error($this->connection) ){
            throw new Exception(mysqli_error($this->connection));
        }

        /*
        // выдаем результаты последнего запроса
        if ($result) {
            do {

                if (!$this->connection->more_results()) {
                    exit ($this->connection->store_result());
                }
            } while ($this->connection->next_result());
        }


        if ( is_bool($this->connection->store_result()) ){
            return $this->connection->store_result();
        }
        */

        $data = array();

        do {
            if ($result = $this->connection->store_result()) {
                while ($row = $result->fetch_row()) {
                    $data[] = $row;
                }
            }
        } while ($this->connection->next_result());

        /*
        while( $row = mysqli_fetch_assoc($this->connection->store_result()) ){
            $data[] = $row;
        }
        */
        return $data;

    }

    // предотвращаем sql injection
    public function escape($str){
        return mysqli_escape_string($this->connection, $str);
    }

}