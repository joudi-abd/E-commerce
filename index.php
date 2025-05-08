<?php
session_start();
require  'includes/db.php';

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
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rose Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body{
            font-family: Arial, serif ;
            background-color :#f7efed 
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
    </style>
</head>
<body>
    <header>
        <div class="py-1 border-bottom" style="background-color: #D17D98;">
            <div class="container d-flex justify-content-between">
                <div>
                    <span>
                        <img src="" alt="Store Logo" style="height:40px;">
                    </span>
                </div>
                <div class="d-flex gap-3">
                    <a href="#" class="text-dark"><i class="fa-solid fa-phone-flip"></i></a>
                    <a href="#" class="text-dark"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="text-dark"><i class="fa-brands fa-instagram"></i></a>           
                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg border-bottom" style="background-color: rgb(250 215 232);">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item"><a href="./pages/users.php" class="nav-link">Manage Users</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="./pages/products.php">Manage Products</a></li>
                    <?php endif; ?>
                    </ul>
                </div>
                <button class="btn p-0 border-0 ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#profileOffcanvas">
                    <i class="fa-regular fa-user"></i>
                </button>
            </div>
        </nav>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="profileOffcanvas" style="background-color: rgb(250 215 232);"> 
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">My Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="text-center mb-3">
                        <h6><?php echo $_SESSION['username'] ?? 'Guest'; ?></h6>
                    </div>
                    <ul class="list-unstyled">
                        <li><a href="auth/logout.php" class="btn btn-outline-dark w-100" style="background-color:#D17D98">Logout</a></li>
                    </ul>
                <?php else: ?>
                    <ul class="list-unstyled">
                        <li><a href="auth/login.php" class="btn btn-outline-dark w-100" style="background-color:#D17D98">Login</a></li>
                        <li><a href="auth/register.php" class="btn btn-outline-dark w-100" style="background-color:#D17D98">Create a new account</a></li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main  dir="rtl">
        <section class="py-5 text-center">
            <div class="container">
                <h2 class="mb-3">أجمل إكسسوارات عالم الموضة لتكتمل إطلالتك</h2>
                <p class="mb-4">اكتشفي أحدث تشكيلة من الإكسسوارات العصرية التي تناسب كل المناسبات</p>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-4">يمكنك تصفح منتجاتنا</h2>
                <div class="row">
                    <?php if (!empty($products)) : ?>
                        <?php foreach ($products as $product) : ?>
                            <div class="col-md-4" style="background-color: rgb(250 215 232); border-radius: 10px; padding: 10px; margin-bottom: 20px;">
                                <a href="pages/product.php?id=<?= $product['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                                    <div class="pro-img">
                                        <?php if (!empty($product['image'])) : ?>
                                            <img class="card-img-top" src="./assets/images/pro-img/<?= htmlspecialchars($product['image']) ?>" alt="Product Image">
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center">No products found!.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white pt-5" dir="rtl" >
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-3 border-top mt-4">
                <div class="col-md-3">
                    <h5>روز - Rose </h5>
                </div>
                <div class="d-flex gap-2">
                    <a href="#" class="text-white"><i class="fa-solid fa-phone-flip"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                </div>            
            </div>
        </div>
    </footer>
</body>
</html>