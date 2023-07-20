<?php
include('connect.php');
$get_categories = "SELECT * FROM category";
$categories = mysqli_query($conn, $get_categories);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
  $sql = "INSERT INTO products (prod_name, price, category_id, thumbnail) VALUES (?,?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sdis", $title, $price, $category, $thumbnailPath); // string, double, integer, string

  // Execute the prepared statement
  if ($stmt->execute()) {
    // Redirect to the homepage
    header("Location: index.php");
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  // Close the prepared statement and database connection
  $stmt->close();
}
$conn->close();
?>
<?php include './components/header.php' ?>

<!-- FORM START -->
<div class="ATN__form">
  <form class="container mx-auto py-3" method="POST" enctype="multipart/form-data">
    <h1 class="text-dark font-weight-bold">Create new product</h1>
    <div class="form-group">
      <label for="product__name">Product name</label>
      <input type="text" class="form-control" name="name" id="prod_name" placeholder="Enter product name ..." />
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="product__price">Price</label>
        <input type="number" class="form-control" name="price" id="prod_price" placeholder="Enter price ..." />
      </div>
      <div class="form-group col-md-6">
        <label for="category" class="form-label">Category</label>
        <select class="form-control" name="category" id="category">
          <?php foreach ($categories as $category) { ?>
            <option value="<?php echo $category["id"] ?>"><?php echo $category["cate_name"] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="product__name">Product image</label>
      <input type="file" name="thumbnail" class="form-control-file" id="prod_img" placeholder="Enter product name ..." />
    </div>
    <div class="form-group">
      <a href="./index.php" class="btn btn-secondary">Back to Products</a>
      <button href="./index.php" type="submit" class="btn btn-success">Save</button>
    </div>
  </form>
</div>
<!-- FORM END -->
<? include './components/footer.php' ?>