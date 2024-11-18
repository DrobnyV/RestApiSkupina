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

    // If no contacts are found, get the details from the firm table
    if (empty($contacts)) {
        $stmt = $conn->prepare("SELECT email, phone FROM firm WHERE id = ?");
        $stmt->bind_param("i", $firmId);
        $stmt->execute();
        $result = $stmt->get_result();
        $contacts = $result->fetch_all(MYSQLI_ASSOC);
    }

    echo json_encode($contacts);
}

?>
