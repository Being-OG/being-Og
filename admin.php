<?php

try {
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$checkColumn = $db->query("PRAGMA table_info(orders)");
$columns = $checkColumn->fetchAll(PDO::FETCH_ASSOC);
$cartItemsExists = false;
foreach ($columns as $column) {
    if ($column['name'] === 'cartItems') {
        $cartItemsExists = true;
        break;
    }
}

if (!$cartItemsExists) {
    $db->exec("ALTER TABLE orders ADD COLUMN cartItems TEXT");
}

$getOrders = $db->query("SELECT * FROM orders");
$orders = $getOrders->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #656565;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #d2edff;
            font-weight: bold;
        }

        tbody tr:hover {
            background-color: #edf2f7;
        }

        tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order History</h1>
        <?php if (count($orders) > 0): ?>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Order</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['fullName']; ?></td>
                                <td><?php echo $order['address']; ?></td>
                                <td><?php echo $order['contactNumber']; ?></td>
                                <td><?php echo $order['email']; ?></td>
                                <td>
                                    <?php 
                                    $cartItems = json_decode($order['cartItems'], true);
                                    foreach ($cartItems as $item) {
                                        echo $item['name'] . ' (Quantity: ' . $item['quantity'] . '), ' . '[size: ' . $item['size'] . '] <br><hr>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $order['totalPrice']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>


<?php
$db = null;
?>