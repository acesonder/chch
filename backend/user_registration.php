<?php
require_once 'config.php';
require_once 'utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $date_of_birth = sanitize_input($_POST['date_of_birth']);
    $password = sanitize_input($_POST['password']);
    $password_confirmation = sanitize_input($_POST['password_confirmation']);
    $consent = isset($_POST['consent']) ? 1 : 0;

    // Validate input
    $errors = validate_registration($first_name, $last_name, $date_of_birth, $password, $password_confirmation, $consent);

    if (empty($errors)) {
        // Generate username
        $username = strtoupper(substr($first_name, 0, 3) . substr($last_name, 0, 3) . substr($date_of_birth, -2));

        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, date_of_birth, username, password_hash, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$first_name, $last_name, $date_of_birth, $username, $password_hash]);

        // Redirect to success page with username
        header("Location: success.php?username=" . urlencode($username));
        exit();
    }
}

function validate_registration($first_name, $last_name, $date_of_birth, $password, $password_confirmation, $consent) {
    $errors = [];

    if (empty($first_name)) {
        $errors[] = 'First name is required.';
    }

    if (empty($last_name)) {
        $errors[] = 'Last name is required.';
    }

    if (empty($date_of_birth)) {
        $errors[] = 'Date of birth is required.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif ($password !== $password_confirmation) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$consent) {
        $errors[] = 'You must agree to the terms of consent.';
    }

    return $errors;
}
?>
