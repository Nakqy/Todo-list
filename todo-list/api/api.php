<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "todolist");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection Failed " . $conn->connect_error]));
}

// Mengambil daftar tugas
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/tasks') {
    $result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    echo json_encode($tasks);
}

// Menambahkan tugas baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/tasks') {
    $data = json_decode(file_get_contents("php://input"), true);
    $task = $data['task'];
    $conn->query("INSERT INTO tasks (task) VALUES ('$task')");
    echo json_encode(["message" => "Task added successfully"]);
}

// Menandai tugas sebagai selesai
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && preg_match('/\/tasks\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = $matches[1];
    $conn->query("UPDATE tasks SET status = 'completed' WHERE id = '$id'");
    echo json_encode(["message" => "Task marked as completed"]);
}

// Menghapus tugas
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && preg_match('/\/tasks\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = $matches[1];
    $conn->query("DELETE FROM tasks WHERE id = '$id'");
    echo json_encode(["message" => "Task deleted successfully"]);
}
?>
