<?php
// Simple token-based API for DigiPost
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../source/config.php';
require_once __DIR__ . '/../source/database.php';

function get_bearer_token() {
    $headers = null;
    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) return trim(str_replace('Bearer', '', $headers['Authorization']));
    }
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim(str_replace('Bearer', '', $_SERVER['HTTP_AUTHORIZATION']));
    }
    // fallback: check GET param token for convenience (only for development)
    if (isset($_GET['api_token'])) return $_GET['api_token'];
    return null;
}

$token = get_bearer_token();
if (!$token || $token !== API_TOKEN) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$op = $_GET['op'] ?? 'list';
$conn = database_connect();

if ($method === 'GET') {
    if ($op === 'list') {
        $stmt = $conn->prepare('SELECT m.id, m.subject, m.is_read, m.created_at,
            m.sender_id, s.email AS sender_email, s.display_name AS sender_name,
            m.recipient_id, r.email AS recipient_email, r.display_name AS recipient_name
            FROM messages m
            LEFT JOIN users s ON m.sender_id = s.id
            LEFT JOIN users r ON m.recipient_id = r.id
            ORDER BY m.created_at DESC');
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) { $rows[] = $r; }
        echo json_encode($rows);
        exit;
    }
    if ($op === 'read' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare('SELECT m.*, s.email AS sender_email, s.display_name AS sender_name, r.email AS recipient_email, r.display_name AS recipient_name FROM messages m LEFT JOIN users s ON m.sender_id=s.id LEFT JOIN users r ON m.recipient_id=r.id WHERE m.id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if (!$row) { http_response_code(404); echo json_encode(['error'=>'Not found']); exit; }
        echo json_encode($row);
        exit;
    }
}

$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    // create a message (requires sender_id and recipient_id)
    $sender_id = isset($input['sender_id']) ? (int)$input['sender_id'] : null;
    $recipient_id = isset($input['recipient_id']) ? (int)$input['recipient_id'] : null;
    $subject = $input['subject'] ?? null;
    $body = $input['body'] ?? null;
    if (!$sender_id || !$recipient_id || !$subject || !$body) { http_response_code(400); echo json_encode(['error'=>'Missing fields']); exit; }
    // validate users exist
    $v = $conn->prepare('SELECT id FROM users WHERE id IN (?,?)');
    $v->bind_param('ii', $sender_id, $recipient_id);
    $v->execute();
    $vres = $v->get_result();
    if ($vres->num_rows < 2) { http_response_code(400); echo json_encode(['error'=>'Invalid sender or recipient']); exit; }

    $stmt = $conn->prepare('INSERT INTO messages (sender_id, recipient_id, subject, body) VALUES (?,?,?,?)');
    $stmt->bind_param('iiss', $sender_id, $recipient_id, $subject, $body);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['success'=>true, 'id'=>$conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error'=>'DB error']);
    }
    exit;
}

if ($method === 'PUT') {
    $id = $input['id'] ?? null;
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }
    $is_read = isset($input['is_read']) ? (int)$input['is_read'] : null;
    $stmt = $conn->prepare('UPDATE messages SET is_read = ? WHERE id = ?');
    $stmt->bind_param('ii', $is_read, $id);
    if ($stmt->execute()) { echo json_encode(['success'=>true]); } else { http_response_code(500); echo json_encode(['error'=>'DB error']); }
    exit;
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }
    $id = (int)$id;
    $stmt = $conn->prepare('DELETE FROM messages WHERE id=?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) { echo json_encode(['success'=>true]); } else { http_response_code(500); echo json_encode(['error'=>'DB error']); }
    exit;
}

http_response_code(405);
echo json_encode(['error'=>'Method not allowed']);
