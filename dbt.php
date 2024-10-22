<?php
$servername = "sql303.ezyro.com";
$username = "ezyro_36397304";
$password = "Al.Ma-47";
$database = "ezyro_36397304_order";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully<br>";
    
    if(isset($_POST['insert'])){
        $text = $_POST['text'];
        $sql = "INSERT INTO your_table_name (column_name) VALUES (:text)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':text', $text);
        
        if ($stmt->execute()) {
            echo "New record inserted successfully<br>";
        } else {
            echo "Error inserting record";
        }
    }
    
    if(isset($_POST['delete'])){
        $sql = "DELETE FROM your_table_name ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute()) {
            echo "Last record deleted successfully<br>";
        } else {
            echo "Error deleting record";
        }
    }

    $sql = "SELECT * FROM your_table_name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "<br>All records:<br>";
        foreach ($result as $row) {
            echo "id: " . $row["id"]. " - Text: " . $row["column_name"]. "<br>";
        }
    } else {
        echo "0 results";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;

?>

<!DOCTYPE html>
<html>
<body>

<form method="post">
    <input type="text" name="text" placeholder="Enter text">
    <button type="submit" name="insert">Insert Data</button>
    <button type="submit" name="delete">Delete Last Inserted Data</button>
</form>

</body>
</html>
