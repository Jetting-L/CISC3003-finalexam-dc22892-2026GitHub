<?php
declare(strict_types=1);

require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Sanitize and validate input
$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$bio = trim((string) filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$department = trim((string) filter_input(INPUT_POST, 'department', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$gender = trim((string) filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$agreeTerms = filter_input(INPUT_POST, 'agree_terms', FILTER_VALIDATE_INT);

$errors = [];

// Validation rules
if ($name === '' || mb_strlen($name) > 100) {
    $errors[] = 'Please enter a valid name.';
}

if ($email === false) {
    $errors[] = 'Please enter a valid email address.';
}

if ($bio !== '' && mb_strlen($bio) > 1000) {
    $errors[] = 'Biography must not exceed 1000 characters.';
}

$allowedDepartments = ['IT', 'HR', 'Marketing', 'Finance'];
if (!in_array($department, $allowedDepartments, true)) {
    $errors[] = 'Please select a valid department.';
}

$allowedGenders = ['Male', 'Female', 'Other'];
if (!in_array($gender, $allowedGenders, true)) {
    $errors[] = 'Please select a valid gender.';
}

if ($agreeTerms !== 1) {
    $errors[] = 'You must agree to the terms and conditions.';
}

if (!empty($errors)) {
    echo '<h1>Form Submission Error</h1>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    echo '</ul>';
    echo '<p><a href="index.php">Go back to the form</a></p>';
    exit;
}

// Insert into table using prepared statement
$sql = "INSERT INTO submissions (name, email, bio, department, gender, agreed_terms)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    exit('Prepare failed: ' . htmlspecialchars($mysqli->error, ENT_QUOTES, 'UTF-8'));
}

$agreedTermsValue = 1;
$stmt->bind_param('sssssi', $name, $email, $bio, $department, $gender, $agreedTermsValue);

if ($stmt->execute()) {
    echo '<h1>Submission Successful</h1>';
    echo '<p>Your information has been saved successfully.</p>';
    echo '<p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p>';
    echo '<p><a href="index.php">Back to form</a></p>';
} else {
    echo '<h1>Database Error</h1>';
    echo '<p>Failed to save data: ' . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8') . '</p>';
}

$stmt->close();
$mysqli->close();
