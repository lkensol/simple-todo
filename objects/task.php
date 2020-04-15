<?php

require_once "db.php";

class Task extends Database{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function create($name, $project_id, $user_id) {
        
        try {
            $sql = 'INSERT INTO tasks (name, project_id, user_id, status)  VALUES (:name, :project_id, :user_id, :status)';
    		$data = [
                ':name' => $name,
                ':project_id' => $project_id,
                ':user_id' => $user_id,
			    ':status' => '0',
			];
	    	$stmt = $this->conn->prepare($sql);
	    	$stmt->execute($data);
			$status = $this->conn->lastInsertId();
            return $status;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function get($user_id, $project_id) {
        try {
            $sql = "SELECT * FROM tasks WHERE user_id = :user_id AND project_id = :project_id ORDER BY order_task";
            $stmt = $this->conn->prepare($sql);
            $data = [
                ':user_id'=> $user_id,
                ':project_id' => $project_id
            ];
            $stmt -> execute($data);
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }        
    }

    public function update($id_task, $name, $status) {
        try {
            $sql = "UPDATE tasks SET name=:name, status=:status WHERE id_task=:id_task";
		    $data = [
                ':name' =>$name,
                ':status' =>$status,
                ':id_task' =>$id_task
			];
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($data);
			$status = $stmt->rowCount();
            return $status;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }        
    }

    public function delete($id_task) {
        try {
            $sql = "DELETE FROM tasks WHERE id_task = :id";
            $stmt = $this->conn->prepare($sql);
            $data = [
                ':id' => $id_task
            ];
            $stmt -> execute($data);
            $status = $stmt->rowCount();
            return $status;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function save_order ($order_string) { 

        $order = explode(',', $order_string);     
        try {
            $sql = "UPDATE tasks SET order_task=:order_task WHERE id_task=:id_task";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':order_task', $order_task);
            $stmt->bindParam(':id_task', $id_task);
            
            $i = 1;
            foreach($order as $value) {
                $order_task = $i;
                $id_task = $value;

                $stmt->execute();
                $i++;
            }           
			return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        } 
    }
}