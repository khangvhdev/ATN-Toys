<?php include './components/header.php' ?>
<?php
include('connect.php');
$get_categories = "SELECT * FROM category";
$categories = mysqli_query($conn, $get_categories);

$categoriesArr = [];
if ($categories) {
    while ($row = mysqli_fetch_assoc($categories)) {
        $categoriesArr[$row['id']] = $row['cate_name'];
    }
}

$currentURL = $_SERVER['REQUEST_URI'];
$baseURL = dirname(substr($currentURL, 0, strrpos($currentURL, '/')));
// Split the URL by slashes (/)
$parts = explode('/', $currentURL);

// Get the last part of the URL, which should be the ID
$id = end($parts);
// Command to specific row
$getProductById = "SELECT * FROM products WHERE id = '$id'";
$currentProduct = mysqli_query($conn, $getProductById);

// Check if the query was successful and there are row
if ($currentProduct && mysqli_num_rows($currentProduct) > 0) {
    // Fetch the data
    $productData = mysqli_fetch_assoc($currentProduct);
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $title = $_POST["name"];
    $price = $_POST["price"];
    $category = $_POST["category"];

    // Handle thumbnail file upload
    $thumbnail = $_FILES["thumbnail"];
    $thumbnailName = $thumbnail["name"];
    $thumbnailTmpName = $thumbnail["tmp_name"];
    $thumbnailPath = "assets/" . $thumbnailName;
    move_uploaded_file($thumbnailTmpName, $thumbnailPath);

    // Process form submission
    $sql = "UPDATE products SET prod_name = ?, price = ?, category_id = ?, thumbnail = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $title, $price, $category, $thumbnailPath, $id); // string, double, integer, string

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Redirect to the homepage
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    // Close the prepared statement and database connection
    $stmt->close();
}
?>
<!-- FORM START -->
<div class="ATN__form">
    <form class="container mx-auto py-3" method="POST" enctype="multipart/form-data">
        <h1 class="text-dark font-weight-bold">Create new product</h1>
        <?php foreach ($currentProduct as $product) { ?>
            <div class="form-group">
                <label for="product__name">Product name</label>
                <input type="text" class="form-control" name="name" id="product__name" value="<?php echo $product["prod_name"] ?>" />
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="product__price">Price</label>
                    <input type="number" class="form-control" name="price" id="product__price" value="<?php echo $product["price"] ?>" />
                </div>
                <div class="form-group col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" name="category" id="category">
                        <option selected hidden value="<?php echo $product["category_id"] ?>">
                            <?php echo $categoriesArr[$product["category_id"]] ?></option>
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo $category["id"] ?>"><?php echo "#" . $category["cate_name"] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="product__name">Product image</label>
                <input type="file" name="thumbnail" class="form-control-file" id="product__name" value="<?php echo $product["thumbnail"] ?>" />
                <img src="<?php echo $baseURL . '/' . $product["thumbnail"]; ?>" class="mt-3" alt="preview" width="200" height="200">
            </div>
        <?php } ?>
        <div class="form-group">
            <a href="./index.php" class="btn btn-secondary">Back to Products</a>
            <button class="btn btn-success" type="submit">Save</button>
        </div>
    </form>
</div>
<!-- FORM END -->
<? include './components/footer.php' ?>

<?php
if (isset($_GET['update_product_id'])) {
    $product_id = $_GET['update_product_id'];
    $update_product = "UPDATE FROM PRODUCTS SET prod_name = '$title' category_id = '$category' price = '$price' thumbnail '$thumbnailPath' where id = '$product_id'";
    $execute = mysqli_query($conn, $update_product);

    if ($execute) {
        echo "<script>window.open('index.php', 'self')</script>";
    }
}
?>