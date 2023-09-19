<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'DbConnect.php';
$objDb = new DbConnect;

try {
    $conn = $objDb->connect();

    $method = $_SERVER['REQUEST_METHOD'];
    switch($method){
        case "GET":
            $sql = "SELECT * FROM users";
            $path = explode('/',$_SERVER['REQUEST_URI']);
            if(isset($path[3]) && is_numeric($path[3])) {
                $sql .= " WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id',$path[3]);
                $stmt->execute();
                $users = $stmt->fetch(PDO::FETCH_ASSOC);
            } 
            else {
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
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

        case "PUT":
            $user = json_decode(file_get_contents('php://input'));
            $sql = "UPDATE users SET name= :name, email = :email, mobile = :mobile, update_at= :update_at WHERE id= :id";
            $stmt = $conn->prepare($sql);
            $created_at = date('Y-m-d');
            $stmt->bindParam(':id',$user->id);
            $stmt->bindParam(':name',$user->name);
            $stmt->bindParam(':email',$user->email);
            $stmt->bindParam(':mobile',$user->mobile);
            $stmt->bindParam(':update_at',$user->update_at);
            if($stmt->execute()){ // Corrected 'executed()' to 'execute()'
                $response = ['status' => 1, 'message' => 'Updated'];
            }
            else{
                $response = ['status' => 0, 'message' => 'Failed to Update'];
            }
            break;
        
        case "DELETE":
            $sql = "DELETE FROM users WHERE id= :id";
            $path = explode('/',$_SERVER['REQUEST_URI']);
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id',$path[3]);
            if($stmt->execute()){ // Corrected 'executed()' to 'execute()'
                $response = ['status' => 1, 'message' => 'Deleted'];
            }
            else{
                $response = ['status' => 0, 'message' => 'Failed to Delete'];
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
