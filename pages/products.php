<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

//ADD PRODUCT
if (isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/pro-img/' . $image);
    }

    $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $desc, $price, $image);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

//EDIT PRODUCT
if (isset($_POST['edit_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    $result = $conn->query("SELECT image FROM products WHERE id = $id");
    $row = $result->fetch_assoc();
    $image = $row['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/pro-img/' . $image);
    }

    $stmt = $conn->prepare("UPDATE products SET product_name=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

//DELETE PRODUCT
if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    $result = $conn->query("SELECT image FROM products WHERE id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['image'];
        $image_path = "../assets/images/pro-img/" . $image;
        if (file_exists($image_path)) {
            unlink($image_path); 
        }
    }
    $conn->query("DELETE FROM products WHERE id = $id");

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

//FETCH PRODUCTS
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color:#f0bdbd; 
            font-family:  Trebuchet, serif ;
            padding: 20px;
        }
        .container{
            background-color:#faebd7; 
            border-radius: 10px;
        }
        .card{
            border-radius: 10px;
            padding: 10px;
        }
        .pro-img{
            text-align: center;
            margin:20px;
        }
        .card-img-top{
            max-width: 50%;
            border-radius: 8px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        } 
        .adding{
            margin:20px 0px;
        }
        .dang:hover{
            background-color: red;
        }
    
    </style>
</head>
<body>
<div class="container">
    <h2  style="text-align:center;" class="mb-4">Manage products</h2>

    <div class="adding" style="text-align:right;">
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i>Add a new product</button>
    </div>

    <div class="row">
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="pro-img">
                            <?php if (!empty($product['image'])) : ?>
                                <img class="card-img-top" src="../assets/images/pro-img/<?= htmlspecialchars($product['image']) ?>" alt="Product Image">
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="card-text"><strong><?= htmlspecialchars($product['price']) ?> $</strong></p>
                        </div>
                        <div class="btn-group" style="text-align:center;">
                            <button class="btn btn-outline-secondary deleteBtn" data-bs-toggle="modal" data-bs-target="#editModal<?= $product['id'] ?>">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-outline-secondary deleteBtn dang" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $product['id'] ?>">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $product['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" enctype="multipart/form-data">
                                <div class="modal-header" style="background-color:#f0bdbd;>
                                    <h5 class="modal-title">Edit product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Product name</label>
                                        <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" for="inputGroupFile01">img</label>
                                        <input type="file" name="image" class="form-control" id="inputGroupFile01">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="edit_product" class="btn btn-outline-secondary" style="background-color:#f0bdbd;">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal<?= $product['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header" style="background-color:#f0bdbd;>
                                    <h5 class="modal-title">Delete the product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete the product?<strong><?= htmlspecialchars($product['product_name']) ?></strong>؟
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" name="delete_product" class="btn btn-outline-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-center">No products found!.</p>
        <?php endif; ?>
    </div>
</div>




<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header" style="background-color:#f0bdbd;>
                    <h5 class="modal-title">Add a new product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product name</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" step="0.01" required> 
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupFile01">img</label>
                        <input type="file" name="image" class="form-control" id="inputGroupFile01">
                    </div>
                </div>
        
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_product" class="btn btn-outline-secondary" style="background-color:#f0bdbd;">Add</button>        
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>