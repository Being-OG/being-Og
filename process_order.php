<?php

try {

    $db = new PDO('mysql:host=sql303.byetcluster.com;dbname=ezyro_36397304_order', 'ezyro_36397304', 'Al.Ma-47');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $res = $db->exec(
        "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY, 
            fullName TEXT, 
            address TEXT, 
            contactNumber TEXT, 
            email TEXT, 
            cartItems TEXT, 
            totalPrice DECIMAL(10,2),
            orderTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );

    if ($res === false) {
        throw new Exception("Table creation failed");
    }

    $stmt = $db->prepare(
        "INSERT INTO orders (fullName, address, contactNumber, email, cartItems, totalPrice) 
        VALUES (:fullName, :address, :contactNumber, :email, :cartItems, :totalPrice)"
    );

    $fullName = $_POST["fullName"];
    $address = $_POST["address"];
    $contactNumber = $_POST["contactNumber"];
    $email = $_POST["email"];
    $cartItems = json_decode($_POST["cartItems"], true);
    $totalPrice = $_POST["totalPrice"];

    $stmt->bindParam(':fullName', $fullName);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':contactNumber', $contactNumber);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cartItems', $cartItemsJSON);
    $stmt->bindParam(':totalPrice', $totalPrice);

    $cartItemsJSON = json_encode($cartItems);

    $stmt->execute();

    $rowCount = $stmt->rowCount();
    if ($rowCount === 0) {
        throw new Exception("No rows inserted");
    }

    echo json_encode(["success" => true, "message" => "Order processed successfully"]);

    $db = null;
} catch (PDOException $ex) {
    echo json_encode(["success" => false, "error" => "Database operation failed: " . $ex->getMessage()]);
} catch (Exception $ex) {

    echo json_encode(["success" => false, "error" => "Table creation failed: " . $ex->getMessage()]);
}

?>
