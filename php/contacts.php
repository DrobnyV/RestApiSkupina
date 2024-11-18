<?php
header('Content-Type: application/json');

switch ($requestMethod) {
    case 'GET':
        if (isset($path[1]) && is_numeric($path[1])) {
            getContactsByFirmId($path[1]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Firm ID is required']);
        }
        break;
    case 'POST':
        if (isset($path[1]) && is_numeric($path[1])) {
            saveContact($path[1]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Firm ID is required']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

function getContactsByFirmId($firmId)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM contacts WHERE firm_id = ?");
    $stmt->bind_param("i", $firmId);
    $stmt->execute();
    $result = $stmt->get_result();
    $contacts = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($contacts);
}

function saveContact($firmId)
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO contacts (firm_id, name, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $firmId, $data['name'], $data['email'], $data['phone']);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Contact created', 'id' => $conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create contact']);
    }
}
?>
