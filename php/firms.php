<?php
header('Content-Type: application/json');

switch ($requestMethod) {
    case 'GET':
        if (isset($path[1]) && is_numeric($path[1])) {
            getFirmById($path[1]);
        } else {
            getAllFirms();
        }
        break;
    case 'POST':
        saveFirm();
        break;
    case 'PUT':
        if (isset($path[1]) && is_numeric($path[1])) {
            updateFirm($path[1]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Firm ID is required']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

function getAllFirms()
{
    global $conn;
    $result = $conn->query("SELECT * FROM firm");
    $firms = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($firms);
}

function getFirmById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM firm WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $firm = $result->fetch_assoc();
    if ($firm) {
        echo json_encode($firm);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Firm not found']);
    }
}

function saveFirm()
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO firm (name, surname, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data['name'], $data['surname'], $data['email'], $data['phone']);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Firm created', 'id' => $conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create firm']);
    }
}

function updateFirm($id)
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("UPDATE firm SET name = ?, surname = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $data['name'], $data['surname'], $data['email'], $data['phone'], $id);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Firm updated']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update firm']);
    }
}
?>
