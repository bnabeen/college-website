<?php
function get_count($conn, $table, $condition = null) {
    $sql = "SELECT COUNT(*) as count FROM `$table`";
    if ($condition) {
        $sql .= " WHERE $condition";
    }
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    return 0;
}

function get_user_by_id($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function get_recent_items($conn, $table, $limit = 5) {
    $stmt = $conn->prepare("SELECT * FROM `$table` ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function format_date($datetime) {
    return date("M d, Y", strtotime($datetime));
}
?>