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
        $tasks  = array_values($tasks);
        saveTask($tasks);
        header('Location: '. $_SERVER['PHP_SELF']);
        exit;
    }

// Step Seventh: Toggle task complete/incomplete
    elseif(isset($_POST['toggle'])){
        $jIndex = $_POST['toggle'];
        $tasks[$jIndex]['done'] = !$tasks[$jIndex]['done'];
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
        body{
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(120deg, #f6f9fc, #e0f7fa);
            margin: 0;
            padding: 20px;
        }
        .container{
            max-width: 600px;
            width: 100%;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #00695c;
            padding-bottom: 20px;
        }
        h4{
            color: #3d8440;
        }
        form input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #b2dfdb;
            border-radius: 5px;
            margin-right: 10px;
            outline: none;
        }
        form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #26a69a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding: 12px 10px;
            margin-top: 10px;
            background: #f1f1f1;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        li.done {
            text-decoration: line-through;
            color: #0b3731;
            background: #8ad7d0;
            font-weight: bold;
        }
        .buttons form {
            display: inline-block;
            margin-left: 5px;
        }
        .buttons button {
            padding: 5px 10px;
            font-size: 14px;
            background: #009688;
            color: white;
            border: none;
            border-radius: 4px;
        }
        .buttons .delete {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <main>
        <div class="container">
            <h2>📝 My Task List | TODO App with PHP</h2>

            <form method="POST">
                <input type="text" name="task" placeholder="Add your Task...">
                <button type="submit">➕ Add Task</button>
            </form>

            <ul>
                <?php if(empty($tasks)): ?>
                <h4>There is No Task yet. add a Task</h4>

                <?php else : ?>
                <?php foreach ($tasks as $index => $task): ?>
                    <li class="<?php echo $task['done'] ? 'done' : '' ?>">
                        <?php echo $index+1 . '. ' .$task['task']?>
                        <div class="buttons">
                            <form method="post">
                                <button name="toggle" value="<?= $index ?>">☑️Complete</button>
                            </form>
                            <form method="post">
                                <button class="delete" name="delete" value="<?= $index ?>">🗑️ Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php endif ?>
            </ul>
        </div>
    </main>
</body>
</html>
