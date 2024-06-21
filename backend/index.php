<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

require 'vendor/autoload.php';
require_once './config.php';
$db = new db();


$app = new \Slim\App;


$app->get('/users', function ($request, $response, $args) use ($db) {
    try {
        $conn = $db->connect();

        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $response->withJson($result);
    } catch (PDOException $e) {
        return $response->withJson(["error" => "Error: " . $e->getMessage()]);
    }
});

$app->get('/users/{id}', function ($request, $response, $args) use ($db) {
    try {
        $id = $args['id'];
        $conn = $db->connect();

        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $response->withJson($user);
        } else {
            return $response->withJson(["error" => "User not found"]);
        }
    } catch (PDOException $e) {
        return $response->withJson(["error" => "Database error: " . $e->getMessage()]);
    }
});

$app->post('/users', function ($request, $response, $args) use ($db) {
    try {
        $conn = $db->connect();
        $data = $request->getParsedBody();

        $name = $data['name'];
        $email = $data['email'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $response->withJson(["message" => "Invalid email format."]);
        }

        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $userId = $conn->lastInsertId();
        return $response->withJson(["message" => "User created successfully!", "id" => $userId]);
    } catch (Exception $e) {
        return $response->withJson(["error" => "Error creating user: " . $e->getMessage()]);
    }
});


$app->put('/users/{id}', function ($request, $response, $args) use ($db) {
    try {
        $id = $args['id'];
        $conn = $db->connect();
        $data = $request->getParsedBody();


        $email = $data['email'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $response->withStatus(400);
        } else {
            $name = $data['name'];
            $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $response->withJson(["message" => "User updated successfully!"]);
        }
    } catch (Exception $e) {
        return $response->withJson(["error" => "Error updating user: " . $e->getMessage()]);
    }
});



$app->delete('/users/{id}', function ($request, $response, $args) use ($db) {
    try {
        $id = $args['id'];

        $conn = $db->connect();

        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $response;
    } catch (PDOException $e) {
        return $response->withJson(["error" => "Error deleting user: " . $e->getMessage()]);
    }
});

$app->run();
