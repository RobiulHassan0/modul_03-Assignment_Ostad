<?php
//  Simple ToDO App

// First Step: 
define('TASK_FILE', 'tasks.json');

// Second Step: loading data from JsonFile with function
function loadTask(){
    if(!file_exists(TASK_FILE)){
        return [];
    }
    $taskData = file_get_contents(TASK_FILE); 
    return $taskData ? json_decode($taskData, true) : []; // Convert JsonData to Array 
}
$tasks = loadTask();

// Third Step: saving data in JsonFile with function
function saveTask(array $tasks): void{
    file_put_contents(TASK_FILE, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Fourth Step: Checking user submission form 
if($_SERVER['REQUEST_METHOD'] === 'POST'){

// Step Fifth: Add Task
    if(isset($_POST['task']) && !empty(trim($_POST['task']))){
        array_push($tasks, [
            'task' => htmlspecialchars(trim($_POST['task'])),
            'done' => false
        ]);
        saveTask($tasks);
        header('Location: '. $_SERVER['PHP_SELF']);
        exit;
    }
    
// Step Sixth: Delete or Remove task-data from JsonFile
    elseif(isset($_POST['delete'])){
        $deleteTask = $_POST['delete'];
        unset($tasks[$deleteTask]);
        saveTask($tasks);
        header('Location: '. $_SERVER['PHP_SELF']);
        exit;
    }

// Step Seventh: Toggle task complete/incomplete
    elseif(isset($_POST['toggle'])){
        $jIndex = $_POST['toggle'];
        $tasks[$jIndex['done']] = !$tasks[$jIndex['done']];
        saveTask($tasks);
        header('Location: '. $_SERVER['PHP_SELF']);
        exit;
    }
}

?>


<!-- HTML UI Code For ToDo App -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO DO App</title>
    <style>
        header{
            text-align: center;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            background: red;
        }
    </style>
</head>
<body>
    <header>
        <h1>TO DO Application with PHP</h1>
    </header>
    <main>
    </main>
</body>
</html>
