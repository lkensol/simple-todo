<?php
//start session
session_start();
// print_r($_SESSION);
require_once "templates/header.php";

//including the database connection file
require_once("objects/query.php");

//Initialize user class
$sql = new Query();
$get_all_statuses = $sql->get_all_statuses();
$count_all_tasks_ordered_by_count = $sql->count_all_tasks_ordered_by_count();
$count_all_tasks_ordered_by_project_names = $sql->count_all_tasks_ordered_by_project_names();
$get_all_tasks_beggin_n = $sql->get_all_tasks_beggin_n();
$get_list_project_contain_a = $sql->get_list_project_contain_a();
$get_list_tasks_with_dupl_names = $sql->get_list_tasks_with_dupl_names();
$get_list_tasks_mathes = $sql->get_list_tasks_mathes();
$get_project_names_more_ten_tasks = $sql->get_project_names_more_ten_tasks();

echo('<pre>');

echo('<b>get all statuses, not repeating, alphabetically ordered</b></br>');
print_r($get_all_statuses);


echo('<b>get the count of all tasks in each project, order by tasks count descending</b></br>');
print_r($count_all_tasks_ordered_by_count);

echo('<b>get the count of all tasks in each project, order by projects names</b></br>');
print_r($count_all_tasks_ordered_by_project_names);

echo('<b>get the tasks for all projects having the name beginning with "N" letter</b></br>');
print_r($get_all_tasks_beggin_n);

echo('<b>get the list of all projects containing the "a" letter in the middle of the name, and show the tasks count near each project. Mention that there can exist projects without tasks and tasks with project_id = NULL</b></br>');
print_r($get_list_project_contain_a);

echo('<b>get the list of tasks with duplicate names. Order alphabetically</b></br>');
print_r($get_list_tasks_with_dupl_names);

echo('<b>get list of tasks having several exact matches of both name and status, from the project "Garage". Order by matches count</b></br>');
print_r($get_list_tasks_mathes);

echo('<b>get the list of project names having more than 10 tasks in status "completed". Order by project_id</b></br>');
print_r($get_project_names_more_ten_tasks);

echo('</pre>');
?>




<?php require_once "templates/footer.php"; ?>

