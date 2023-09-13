<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';
$objDb = new DbConnect;

try {
    $conn = $objDb->connect();

    $method = $_SERVER['REQUEST_METHOD'];
    switch($method){
        case "GET":
            $sql = "SELECT * FROM users";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $users;
            break;

        case "POST":
            $user = json_decode(file_get_contents('php://input'));
            $sql = "INSERT INTO users(id, name, email, mobile, created_at) VALUES(null, :name, :email, :mobile, :created_at)";
            $stmt = $conn->prepare($sql);
            $created_at = date('Y-m-d');
            $stmt->bindParam(':name',$user->name);
            $stmt->bindParam(':email',$user->email);
            $stmt->bindParam(':mobile',$user->mobile);
            $stmt->bindParam(':created_at',$user->created_at);
            if($stmt->execute()){ // Corrected 'executed()' to 'execute()'
                $response = ['status' => 1, 'message' => 'Success'];
            }
            else{
                $response = ['status' => 0, 'message' => 'Failed'];
            }
            break;
    }
} catch (PDOException $e) {
    // Handle database connection error
    $response = ['status' => 0, 'message' => 'Database Connection Error: ' . $e->getMessage()];
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
