<?php
// Script to test WordPress version, enumerate users, and check wp-config.php security
// Version 1.0
// GPL v3 License
// iby[@]9.technology

// Define the allowed IP (replace with your Zabbix server IP)
$allowed_ip = 'zabbix_ip_here';

// Check if the script is being accessed from the allowed IP
if ($_SERVER['REMOTE_ADDR'] !== $allowed_ip) {
    header('HTTP/1.0 403 Forbidden');
    echo json_encode(["error" => "Access denied."]);
    exit;
}

ini_set('display_errors', 0); // Disable display of errors
ini_set('display_startup_errors', 0);
error_reporting(0); // Suppress all error messages

require('wp-load.php');
require('wp-admin/includes/update.php');

// Initialize response array
$response = array();

// WordPress Version and Update Check
$response['current_version'] = $wp_version;

$updates = get_core_updates();
if (!isset($updates[0]->response) || 'latest' === $updates[0]->response) {
    $response['update_needed'] = false;
    $response['message'] = 'No update needed';
} else {
    $response['update_needed'] = true;
    $response['message'] = 'Update needed';
    $response['latest_version'] = $updates[0]->current ?? 'Unknown';
}

// Enumerate Users
$response['users'] = array();

$users = get_users();
foreach ($users as $user) {
    $response['users'][] = array(
        'ID' => $user->ID,
        'username' => $user->user_login,
        'display_name' => $user->display_name,
        'role' => implode(', ', $user->roles)
    );
}

// wp-config.php Security Checks
$wp_config_path = ABSPATH . 'wp-config.php';
$config_security = array();
$config_security['file_permissions'] = substr(sprintf('%o', fileperms($wp_config_path)), -3) === '600' || substr(sprintf('%o', fileperms($wp_config_path)), -3) === '640';

$config_security['salts_defined'] = defined('AUTH_KEY') && defined('SECURE_AUTH_KEY') && defined('LOGGED_IN_KEY') && defined('NONCE_KEY');
$config_security['disallow_file_edit'] = defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT;
$config_security['force_ssl_admin'] = defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN;

// Add config security details to response
$response['config_security'] = $config_security;

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
