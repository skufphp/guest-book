<?php

declare(strict_types=1);

/**
 * Outputs a formatted representation of the provided data for debugging purposes.
 *
 * @param array|object $data The data to be dumped, either as an array or an object.
 * @return void
 */
function dump(array|object $data): void
{
    echo '<pre>' . print_r($data, true) . '</pre>';
}

/**
 * Redirects the user to the specified URL.
 *
 * @param string $url The URL to redirect to.
 * @return never
 */
function redirect(string $url): never
{
    header("Location: $url");
    exit;
}

/**
 * Formats an array of error messages into an unordered list as a string.
 *
 * @param array $errors An array of error groups, where each group contains multiple error messages.
 * @return string A string representation of the error messages formatted as an unordered list.
 */
function getErrors(array $errors): string
{
    $html = '<ul class="list-unstyled">';
    foreach ($errors as $errorGroup) {
        foreach ($errorGroup as $error) {
            $html .= "<li>$error</li>";
        }
    }
    $html .= '</ul>';
    return $html;
}

/**
 * Load data from $_POST or $_GET based on the provided fillable fields.
 *
 * @param array $fillable Array of field names to load data for.
 * @param bool $post Whether to load data from $_POST (true) or $_GET (false).
 * @return array Associative array with field names as keys and loaded data as values.
 */
function loadData(array $fillable, bool $post = true): array
{
    $loadData = $post ? $_POST : $_GET;
    $data = [];
    foreach ($fillable as $field) {
        if (isset($loadData[$field]) && is_string($loadData[$field])) {
            $data[$field] = trim($loadData[$field]);
        } else {
            $data[$field] = '';
        }
    }
    return $data;
}

/**
 * Retrieves the old input value from the specified source (POST or GET).
 *
 * @param string $name The name of the input field to retrieve.
 * @param bool $post Determines whether to retrieve the value from POST (true) or GET (false). Defaults to true.
 * @return string The retrieved input value, or an empty string if the input is not found.
 */
function oldInput(string $name, bool $post = true): string
{
    $loadData = $post ? $_POST : $_GET;
    return specialChars($loadData[$name] ?? '');
}

/**
 * Escapes special characters in a string for use in HTML, converting them to their corresponding HTML entities.
 *
 * @param string $string The input string to escape.
 * @return string The escaped string with special characters converted to HTML entities.
 */
function specialChars(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Registers a new user with the provided data.
 *
 * @param PDO $db The database connection object.
 * @param array $data The user registration data containing 'name', 'email', and 'password' fields.
 * @return bool True if the registration is successful, false otherwise.
 */
function registerUser(PDO $db, array $data): bool
{
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetchColumn()) {
        $_SESSION['errors'] = 'Email already exists';
        return false;
    }

    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$data['name'], $data['email'], $data['password']]);
    $_SESSION['success'] = 'You are now registered and can log in';

    return true;
}

/**
 * Authenticates a user with the provided login credentials.
 *
 * @param PDO $db The database connection object.
 * @param array $data The user login data containing 'email' and 'password' fields.
 * @return bool True if the login is successful and user data is stored in the session, false otherwise.
 */
function loginUser(PDO $db, array $data): bool
{
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);

    $row = $stmt->fetch();

    if ($row) {
        if (!password_verify($data['password'], $row['password'])) {
            $_SESSION['errors'] = 'Wrong email or password';
            return false;
        }
    } else {
        $_SESSION['errors'] = 'Wrong email or password';
        return false;
    }

    foreach ($row as $key => $value) {
        if ($key != 'password') {
            $_SESSION['user'][$key] = $value;
        }
    }
    $_SESSION['success'] = 'Successfully logged in';
    return true;
}

/**
 * Checks if a user is currently logged in.
 *
 * @return bool True if the user is logged in, false otherwise.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Checks if the logged-in user has admin privileges.
 *
 * @return bool True if the user is an admin, false otherwise.
 */
function isAdmin(): bool
{
    return isLoggedIn() && $_SESSION['user']['role'] === 2;
}

/**
 * Saves a user message to the database.
 *
 * @param PDO $db The database connection object.
 * @param array $data The message data containing the 'message' field.
 * @return bool True if the message is successfully saved, false otherwise.
 */
function saveMessage(PDO $db, array $data): bool
{
    if (!isLoggedIn()) {
        $_SESSION['errors'] = 'You must be logged in to send a message';
        return false;
    }

    $stmt = $db->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user']['id'], $data['message']]);
    $_SESSION['success'] = 'Message sent successfully';
    return true;
}

/**
 * Edits an existing message in the database.
 *
 * @param PDO $db The database connection object.
 * @param array $data The data for the message update containing 'id' and 'message' fields.
 * @return bool True if the message was edited successfully, false otherwise.
 */
function editMessage(PDO $db, array $data): bool
{
    if (!isAdmin()) {
        $_SESSION['errors'] = 'You must be logged in as an admin to edit a message';
        return false;
    }

    $stmt = $db->prepare("UPDATE messages SET message = ? WHERE id = ?");
    $stmt->execute([$data['message'], $data['id']]);
    $_SESSION['success'] = 'Message edit successfully';
    return true;
}

/**
 * Retrieves messages from the database.
 *
 * @param PDO $db The database connection object.
 * @return array An array of messages, each containing 'id', 'user_id', 'message', 'created_at', and 'status' fields.
 */
function getMessages(PDO $db, int $startPage, int $postPerPage): array
{
    $where = '';
    if (!isAdmin()) {
        $where .= 'WHERE status = 1';
    }

    $stmt = $db->prepare("SELECT messages.*, users.name,
                                DATE_FORMAT(messages.created_at, '%d.%m.%Y %H:%i') AS created_at
                                FROM messages 
                                JOIN users 
                                ON users.id = messages.user_id $where
                                ORDER BY messages.id DESC
                                LIMIT $startPage, $postPerPage");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Retrieves the count of messages from the database, optionally filtered by status.
 *
 * @param PDO $db The database connection object.
 * @return int The total count of messages. If the user is not an admin, only messages with status 1 are counted.
 */
function getCountMessages(PDO $db): int
{
    $where = '';
    if (!isAdmin()) {
        $where .= 'WHERE status = 1';
    }

    $stmt = $db->prepare("SELECT COUNT(*) FROM messages $where");
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}

/**
 * Toggles the status of a message in the database.
 *
 * @param PDO $db The database connection object.
 * @param int $id The unique identifier of the message.
 * @param int $status The new status value to set (1 for active, 0 for inactive).
 * @return bool True if the status is successfully updated, false otherwise.
 */
function toggleMessageStatus(PDO $db, int $status, int $id): bool
{
    if (!isAdmin()) {
        $_SESSION['errors'] = 'You must be logged in as an admin to change the message status';
        return false;
    }

    $status = $status ? 1 : 0;
    $stmt = $db->prepare("UPDATE messages SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
}

