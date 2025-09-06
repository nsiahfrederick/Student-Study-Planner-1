<?php
session_start();

// Initialize tasks if not already set
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_task'])) {
        $_SESSION['tasks'][] = [
            'subject' => $_POST['subject'],
            'task' => $_POST['task'],
            'deadline' => $_POST['deadline'],
            'priority' => $_POST['priority'],
            'completed' => false
        ];
    }

    if (isset($_POST['delete_task'])) {
        $index = $_POST['delete_task'];
        unset($_SESSION['tasks'][$index]);
        $_SESSION['tasks'] = array_values($_SESSION['tasks']); // Reindex
    }

    if (isset($_POST['toggle_complete'])) {
        $index = $_POST['toggle_complete'];
        $_SESSION['tasks'][$index]['completed'] = !$_SESSION['tasks'][$index]['completed'];
    }
}

// Calculate progress
$total = count($_SESSION['tasks']);
$completed = array_reduce($_SESSION['tasks'], function ($carry, $task) {
    return $carry + ($task['completed'] ? 1 : 0);
}, 0);
$progress = $total > 0 ? round(($completed / $total) * 100) : 0;

// Fun motivational quotes
$quotes = [
    "One page at a time, one step at a time!",
    "You’re smarter than you think!",
    "Study hard, and the results will follow.",
    "Stay focused and never give up!",
    "Every effort brings you closer to success!"
];
$randomQuote = $quotes[array_rand($quotes)];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Study Planner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f8ff;
            margin: 0;
            padding: 20px;
        }
        h1 { text-align: center; }
        form, .task-list {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
        input, select {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
        }
        button {
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            margin-top: 10px;
        }
        .task {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .completed {
            text-decoration: line-through;
            color: gray;
        }
        .progress-bar {
            width: 100%;
            background: #ddd;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .progress-bar-fill {
            height: 20px;
            background: #28a745;
            width: <?= $progress ?>%;
            text-align: center;
            color: white;
            line-height: 20px;
        }
        .quote {
            text-align: center;
            font-style: italic;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>

<h1>📚 Student Study Planner</h1>

<div class="progress-bar">
    <div class="progress-bar-fill"><?= $progress ?>%</div>
</div>

<form method="post">
    <h3>Add New Task</h3>
    <input type="text" name="subject" placeholder="Subject (e.g., Math)" required>
    <input type="text" name="task" placeholder="What to study?" required>
    <input type="date" name="deadline" required>
    <select name="priority">
        <option value="High">🔥 High</option>
        <option value="Medium">⭐ Medium</option>
        <option value="Low">🌙 Low</option>
    </select>
    <button type="submit" name="add_task">Add Task</button>
</form>

<div class="task-list">
    <h3>📋 Your Plan</h3>
    <?php if (empty($_SESSION['tasks'])): ?>
        <p>No tasks yet. Add one above!</p>
    <?php else: ?>
        <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
            <div class="task">
                <strong class="<?= $task['completed'] ? 'completed' : '' ?>">
                    [<?= $task['priority'] ?>] <?= htmlspecialchars($task['subject']) ?>: <?= htmlspecialchars($task['task']) ?>
                </strong><br>
                Deadline: <?= htmlspecialchars($task['deadline']) ?><br>
                Status: <?= $task['completed'] ? '✅ Completed' : '⏳ In Progress' ?>
                <form method="post" style="display:inline">
                    <button name="toggle_complete" value="<?= $index ?>">Toggle Complete</button>
                    <button name="delete_task" value="<?= $index ?>" style="background:#dc3545;">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="quote">
    <p>💡 <?= $randomQuote ?></p>
</div>

</body>
</html>
