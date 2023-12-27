<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fonction pour obtenir l'état actuel de la tâche (done ou undo)
function getTaskStatus($taskId) {
    global $pdo;
    $query = $pdo->prepare("SELECT status FROM tasks WHERE id = :id");
    $query->execute(['id' => $taskId]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['status'] : null;
}

// Fonction pour mettre à jour l'état de la tâche (done ou undo)
function updateTaskStatus($taskId, $status) {
    global $pdo;
    $query = $pdo->prepare("UPDATE tasks SET status = :status WHERE id = :id");
    $query->execute(['status' => $status, 'id' => $taskId]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ToDoList</title>
</head>
<body>

<div class="container">
    <h1>ToDoList</h1>
    <h2>Bienvenue, <?php echo $_SESSION['user']; ?>!</h2>

    <form id="addTaskForm" action="add_task.php" method="post">
        <input type="text" id="taskInput" name="task" placeholder="Nouvelle tâche" required>
        <button type="submit">Ajouter</button>
    </form>

    <!-- Liste des tâches -->
    <?php
    $pdo = Database::connect();
    $tasks = $pdo->query("SELECT * FROM tasks WHERE user_id = {$_SESSION['user_id']}")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tasks as $task) {
        $status = getTaskStatus($task['id']);
        $background = $status === 'done' ? 'background-color: #8F8;' : 'background-color: #F90;';
        echo "<div class='task' style='{$background}'>";
        echo "<span>{$task['task']}</span>";
        echo "<a href='delete_task.php?id={$task['id']}' class='delete'></a>";
        echo "<button class='status' data-task-id='{$task['id']}'>{$task['status']}</button>";
        echo "</div>";
    }
    ?>
    
    <a href="logout.php">Déconnexion</a>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const statusButtons = document.querySelectorAll('.status');

    statusButtons.forEach(button => {
        button.addEventListener('click', () => {
            const taskId = button.getAttribute('data-task-id');
            fetch(`toggle_task_status.php?id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    button.textContent = data.status === 'done' ? 'Done' : 'Undo';
                    button.closest('.task').style.backgroundColor = data.status === 'done' ? '#8F8' : '#F90';
                })
                .catch(error => console.error('Error:', error));
        });
    });
});
$(document).ready(function () {
        $('#addTaskForm').submit(function (event) {
            event.preventDefault(); // Empêche le comportement par défaut du formulaire

            var taskInput = $('#taskInput').val();

            // Vérifiez si la tâche n'est pas vide
            if (taskInput.trim() !== '') {
                // Effectuez la requête AJAX
                $.ajax({
                    type: 'POST',
                    url: 'add_task.php',
                    data: $('#addTaskForm').serialize(),
                    success: function (response) {
                        // Ajoutez la nouvelle tâche à la liste
                        var newTask = '<div class="task pending"><span>' + taskInput + '</span>';
                        newTask += '<a href="delete_task.php?id=' + response.id + '">Confirmer</a>';
                        newTask += '<a href="toggle_task_status.php?id=' + response.id + '">Toggle Status</a></div>';

                        $('.container').append(newTask);

                        // Effacez le champ de saisie
                        $('#taskInput').val('');

                        alert('Tâche ajoutée avec succès!');
                    },
                    error: function (error) {
                        console.error('Erreur lors de l\'ajout de la tâche:', error);
                    }
                });
            } else {
                // Gérez le cas où la tâche est vide
                alert("La tâche ne peut pas être vide.");
            }
        });
    });
    
</script>


</body>
</html>
