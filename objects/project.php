<?php

require_once "db.php";

class Project extends Database{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function create($user_id, $name) {
        try {
            $sql = 'INSERT INTO projects (name, user_id)  VALUES (:name, :user_id)';
    		$data = [
                ':name' => $name,
                ':user_id' => $user_id
			];
	    	$stmt = $this->conn->prepare($sql);
	    	$stmt->execute($data);
			$status = $this->conn->lastInsertId();
            return $status;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function get($user_id) {
        try {
            $sql = "SELECT * FROM projects WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $data = [
                ':user_id'=> $user_id
            ];
            $stmt -> execute($data);
            $projects =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($projects as $key => $project) {
                $sql = "SELECT * FROM tasks WHERE project_id = :project_id AND user_id = :user_id ORDER BY order_task";
                $stmt = $this->conn->prepare($sql);
                $data = [
                    ':user_id'=> $user_id,
                    ':project_id'=> $project['id_project']
                ];
                $stmt -> execute($data);
                $tasks =  $stmt->fetchAll(PDO::FETCH_ASSOC);
                $projects[$key]['tasks'] = $tasks;
            }
            return $projects;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }        
    }

    public function update($id_project, $name) {
        try {
            $sql = "UPDATE projects SET name=:name WHERE id_project=:id_project";
		    $data = [
			    ':name' =>$name,
			    ':id_project' => $id_project
			];
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($data);
			$status = $stmt->rowCount();
            return $status;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }        
    }

    public function delete($id_project) {
        try {            
            $sql = "DELETE FROM projects WHERE id_project = :id";
            $stmt = $this->conn->prepare($sql);
            $data = [
                ':id' => $id_project
            ];
            $stmt -> execute($data);
            $status = $stmt->rowCount();

            $sql = "SELECT * FROM projects WHERE id_project = :id";
            $stmt = $this->conn->prepare($sql);
            $data = [
                ':id' => $id_project
            ];
            $stmt -> execute($data);
            $result = $stmt->rowCount();

            if($result) {
                // and delete all tasks
                $sql = "DELETE FROM tasks WHERE id_project = :id";
                $stmt = $this->conn->prepare($sql);
                $data = [
                    ':id' => $id_project
                ];
                $stmt -> execute($data);
                $status = $stmt->rowCount();
            }           

            return $status;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
 
}