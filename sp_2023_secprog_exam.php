<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_to_cart"])) {
        $product_name = strip_tags($_POST["product_name"]);
        $quantity = $_POST["quantity"];
        $price = $_POST["price"];

        // 1. TODO: Prepare and bind the INSERT statement
        $stmt = $conn->prepare("INSERT INTO carts (product_name,quantity,price) SET (?,?,?)");
        $stmt->bind_param("ss", $product_name, $quantity, $price);
        
        // 2. TODO: Validate if the query has been executed successfully
        if ($stmt->execute()) {
            echo "Item added to cart successfully.";
        } else {
            echo "Error adding item to cart";
        }

        $stmt->close();

    } else if (isset($_POST["update_cart"])) {
        $product_name = strip_tags($_POST["product_name"]);
        $quantity = $_POST["quantity"];

        // 3. TODO: Prepare and bind the UPDATE statement
        $stmt = $conn->prepare("UPDATE carts (product_name,quantity) SET (?,?)");
        $stmt->bind_param("ss", $product_name, $quantity);
        
        // 4. TODO: Validate if the query has been executed successfully
        if ($stmt->execute()) {
            echo "Cart updated successfully.";
        } else {
            echo "Error updating cart";
        }

        $stmt->close();
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // 5. TODO: Fetch all or search cart items by 'product_name' (execute GET statements)
    if (isset($_GET["show_carts"])) {
        $product_name = strip_tags($_GET["product_name"]);
        $quantity = $_GET["quantity"];
        $price = $_GET["price"];
    
        $stmt = $mysqli_query("SELECT product_name FROM carts");
        $rows = $stmt->fetch_all(MYSQLI_ASSOC);
        if($stmt->num_rows>0){
            foreach ($rows as $row){
                 printf("%s, %d, %d", $row["product_name"], $row["quantity"], $row["price"]);
             }
        }
    }
    else if (isset($_GET["search_product_from_carts"])) {
        $product_name = strip_tags($_GET["product_name"]);
        
        $searchname = $_GET["product_name"]; 
        $stmt = $mysqli->prepare("SELECT product_name FROM carts WHERE product_name = ?");
        $stmt->bind_param("s",$searchname);

        if ($stmt->execute()) {
            if($stmt->num_rows>0){
            while($row =$stmt->fetch_assoc()){
                printf("%s, %d, %d", $row["product_name"], $row["quantity"], $row["price"]);
                }
            }
        } else {
            echo "Error searching product name on cart";
        }
       
    }

}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Cart</title>
</head>

<body>
    <h2>My Cart</h2>
    <div>
        <ul>
            <?php foreach ($cartItems as $item) : ?>
                <li>
                    Product: <?php echo $item['product_name']; ?>,
                    Quantity: <?php echo $item['quantity']; ?>,
                    Price: <?php echo $item['price']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <h2>Add Item to Cart</h2>
    <form method="post" action="">
        Product Name: <input type="text" name="product_name"><br>
        Quantity: <input type="number" name="quantity"><br>
        Price: <input type="number" name="price"><br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>

    <h2>Update Cart</h2>
    <form method="post" action="">
        Product Name: <input type="text" name="product_name"><br>
        New Quantity: <input type="number" name="quantity"><br>
        <input type="submit" name="update_cart" value="Update Cart">
    </form>
</body>

</html>