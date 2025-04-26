<?php
/**
 * Get count of records from a table
 * 
 * @param mysqli $conn Database connection
 * @param string $table Table name
 * @param string $condition Optional WHERE condition
 * @return int Count of records
 */
function get_count($conn, $table, $condition = '') {
    $table = mysqli_real_escape_string($conn, $table);
    $sql = "SELECT COUNT(*) as count FROM `$table`";
    
    if (!empty($condition)) {
        $sql .= " WHERE " . $condition;
    }
    
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn));
        return 0;
    }
    
    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return (int)$row['count'];
}

/**
 * Get user details by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @return array|null User details or null if not found
 */
function get_user_by_id($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        return null;
    }

    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return null;
    }

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    return $user;
}

/**
 * Get recent items from a table
 * 
 * @param mysqli $conn Database connection
 * @param string $table Table name
 * @param int $limit Number of items to return
 * @return array Array of recent items
 */
function get_recent_items($conn, $table, $limit = 5) {
    $table = mysqli_real_escape_string($conn, $table);
    $stmt = mysqli_prepare($conn, "SELECT * FROM `$table` ORDER BY created_at DESC LIMIT ?");
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        return [];
    }

    mysqli_stmt_bind_param($stmt, 'i', $limit);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return [];
    }

    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    mysqli_stmt_close($stmt);
    return $items;
}

/**
 * Format date to readable format
 * 
 * @param string $datetime DateTime string
 * @return string Formatted date
 */
function format_date($datetime) {
    return date("M d, Y", strtotime($datetime));
}

/**
 * Sanitize user input
 * 
 * @param mysqli $conn Database connection
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

/**
 * Generate slug from string
 * 
 * @param string $string Input string
 * @return string URL-friendly slug
 */
function generate_slug($string) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
}

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Redirect to a URL
 * 
 * @param string $url URL to redirect to
 * @return void
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Set flash message
 * 
 * @param string $message Message text
 * @param string $type Message type (success/danger/warning/info)
 * @return void
 */
function set_flash_message($message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}