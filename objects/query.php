<?php

require_once "db.php";

class Query extends Database{
    
    public function __construct()
    {
        parent::__construct();
    } 

    //get all statuses, not repeating, alphabetically ordered
    public function get_all_statuses() {
        try {
            $sql = "SELECT DISTINCT status FROM tasks ORDER BY name ASC";
            $stmt = $this->conn->prepare($sql);            
            $stmt -> execute();
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }        
    }

    // get the count of all tasks in each project, order by tasks count descending
    public function count_all_tasks_ordered_by_count() {
        try {
            $sql = "SELECT COUNT(status) as c_status FROM tasks GROUP BY project_id  ORDER BY c_status DESC";
            $stmt = $this->conn->prepare($sql);            
            $stmt -> execute();
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // get the count of all tasks in each project, order by projects names
    public function count_all_tasks_ordered_by_project_names() {
        try {
            $sql = "SELECT COUNT(t.status) as c_status 
                    FROM tasks AS t
                    JOIN projects AS p
                    ON p.id_project =  t.project_id
                    GROUP BY t.project_id  
                    ORDER BY p.name
                    ";
            $stmt = $this->conn->prepare($sql);            
            $stmt -> execute();
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // get the tasks for all projects having the name beginning with "N" letter
    public function get_all_tasks_beggin_n () {
        try {
            $sql = "SELECT * 
                    FROM tasks   
                    WHERE name REGEXP '^N'
                    ";
            $stmt = $this->conn->prepare($sql);            
            $stmt -> execute();
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // get the list of all projects containing the 'a' letter in the middle of the name, and show the tasks count near each project. Mention that there can exist projects without tasks and tasks with project_id = NULL
    public function get_list_project_contain_a () {
        try {
            $sql = "SELECT 
                        p.id_project, 
                        p.name, 
                        COUNT(t.id_task) AS task_count,
                        SUBSTRING(p.name,CEILING(LENGTH(p.name)/2.0),1) AS midLett
                    FROM projects AS p                    
                    left JOIN tasks AS t
                        ON t.project_id =  p.id_project
                    GROUP BY p.id_project
                    HAVING midLett = 'a'
                    UNION
                    SELECT 
                        p.id_project, 
                        p.name, 
                        COUNT(t.id_task) AS task_count,
                        SUBSTRING(p.name,CEILING(LENGTH(p.name)/2.0),1) AS midLett
                    FROM projects AS p                    
                    RIGHT JOIN tasks AS t
                        ON t.project_id =  p.id_project
                    GROUP BY p.id_project
                    HAVING midLett = 'a'
                    ";

            $stmt = $this->conn->prepare($sql);            
            $stmt -> execute();
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // get the list of tasks with duplicate names. Order alphabetically
    public function get_list_tasks_with_dupl_names () {
        try {
            $sql = "SELECT name,  COUNT(*) as names_count
                    FROM tasks  
                    Group BY name 
                    HAVING COUNT(*) > 1
                    ORDER BY name
                    ";
            $stmt = $this->conn->prepare($sql);            
            $stmt -> execute();
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // get list of tasks having several exact matches of both name and status, from the project 'Garage'. Order by matches count
    public function get_list_tasks_mathes () {
        try {
            $sql = "SELECT 
                        t.name, 
                        t.status,
                        COUNT(t.name) AS name_count,
                        COUNT(t.id_task) AS task_count
                    FROM projects AS p                    
                    left JOIN tasks AS t
                        ON t.project_id =  p.id_project
                    WHERE p.name = 'Garage'
                    -- ORDER BY COUNT(t.id_task)
                    GROUP BY t.name, t.status
                    HAVING name_count = task_count 
                    ORDER BY name_count
                    ";

            $stmt = $this->conn->prepare($sql);            
            $stmt -> execute();
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // get the list of project names having more than 10 tasks in status 'completed'. Order by project_id
    public function get_project_names_more_ten_tasks () {
        try {
            $sql = "SELECT 
                        p.name
                    FROM projects AS p                    
                    left JOIN tasks AS t
                        ON t.project_id =  p.id_project
                    WHERE t.status = 1
                    GROUP BY p.id_project
                    HAVING COUNT(t.id_task) > 10 
                    ORDER BY p.id_project
                    ";

            $stmt = $this->conn->prepare($sql);            
            $stmt -> execute();
            $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
}