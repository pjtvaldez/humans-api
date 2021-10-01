<?php
    class Humans {
        private $conn = null;


        public function __construct($conn)
        {
            $this->conn = $conn;
        }

        public function findAll()
        {
            $statement = "
                SELECT 
                    id, first_name, last_name
                FROM
                    humans;
            ";

            try {
                $statement = $this->conn->query($statement);
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } catch (PDOException $e) {
                exit($e->getMessage());
            }
        }

        public function find($id)
        {
            $statement = "
                SELECT 
                    id, first_name, last_name
                FROM
                    humans
                WHERE id = ?;
            ";

            try {
                $statement = $this->conn->prepare($statement);
                $statement->execute(array($id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } catch (PDOException $e) {
                exit($e->getMessage());
            }    
        }

        public function insert(Array $input)
        {
            $statement = "
                INSERT INTO humans 
                    (first_name, last_name)
                VALUES
                    (:first_name, :last_name);
            ";

            try {
                $statement = $this->conn->prepare($statement);
                $statement->execute(array(
                    'first_name' => $input['first_name'],
                    'last_name'  => $input['last_name']
                ));
                return $statement->rowCount();
            } catch (PDOException $e) {
                exit($e->getMessage());
            }    
        }

        public function update($id, Array $input)
        {
            $statement = "
                UPDATE humans
                SET 
                    first_name = :first_name,
                    last_name  = :last_name
                WHERE id = :id;
            ";

            try {
                $statement = $this->conn->prepare($statement);
                $statement->execute(array(
                    'id' => (int) $id,
                    'first_name' => $input['first_name'],
                    'last_name'  => $input['last_name']
                ));
                return $statement->rowCount();
            } catch (PDOException $e) {
                exit($e->getMessage());
            }    
        }

        public function delete($id)
        {
            $statement = "
                DELETE FROM humans
                WHERE id = :id;
            ";

            try {
                $statement = $this->conn->prepare($statement);
                $statement->execute(array('id' => $id));
                return $statement->rowCount();
            } catch (PDOException $e) {
                exit($e->getMessage());
            }    
        }
    }
