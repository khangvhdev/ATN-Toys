<?php include './components/header.php' ?>
<?php
include('connect.php');
$get_products = "SELECT * FROM products";
$get_categories = "SELECT * FROM category";
$products = mysqli_query($conn, $get_products);
$categories = [];

// Fetch category data and store in the $categories array
$category = mysqli_query($conn, $get_categories);
if ($category) {
  while ($row = mysqli_fetch_assoc($category)) {
    $categories[$row['id']] = $row['cate_name'];
  }
}
?>
<!-- PRODUCT START -->
<div class="product__list mt-5">
  <div class="container">
    <div class="row g-3 justify-content-center">
      <?php foreach ($products as $product) { ?>
        <div class="col-12 col-sm-9 col-md-6 col-lg-3">
          <div class="card">
            <img src="<?php echo $product['thumbnail']; ?>" class="card-img-top" alt="..." />
            <div class="card-body">
              <h5 class="card-title"><?php echo $product['prod_name']; ?></h5>
              <div class="product__info d-flex justify-content-between align-items-center">
                <p class="text-info fs-4">$<?php echo $product['price']; ?></p>
                <p class="text-white fs-5">
                  <span class="badge bg-secondary">#<?php echo $product['category_id']; ?></span>
                </p>
              </div>
              <p class="card-text">
              </p>
              <div class="product__edit">
                <a href='./update.php/<?php echo $product['id']; ?>' class="btn btn-primary w-100 mb-1">Edit</a>
                <a href="#" class="btn btn-danger w-100" data-toggle="modal" data-target="#product-<?php echo $product['id']; ?>">Delete</a>
              </div>
              <div class="modal fade" id="product-<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title text-warning" id="exampleModalLabel">
                        Warning
                      </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">Are you sure to delete?</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                      </button>
                      <button onclick="location.href='index.php?delete_product_id=<?php echo $product['id']; ?>'" type="button" class="btn btn-danger">
                        Delete
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<!-- PRODUCT END -->
<?php include('./components/footer.php'); ?>

<?php
if (isset($_GET['delete_product_id'])) {
  $product_id = $_GET['delete_product_id'];
  $delete_product = "delete from products where id='$product_id'";
  $execute = mysqli_query($conn, $delete_product);

  if ($execute) {
    echo "<script>window.open('index.php', '_self')</script>";
  }
}
?>