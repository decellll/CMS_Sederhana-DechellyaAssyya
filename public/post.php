<?php
require_once '../config/database.php';

// Get post by slug
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$sql = "SELECT p.*, u.username as author_name, c.name as category_name 
        FROM posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.slug = ? AND p.status = 'published'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$post = $result->fetch_assoc();

// Get categories for sidebar
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - DechPress</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body,
        .content-wrapper,
        .container,
        .container-fluid {
            background: #fff !important;
            color: #222831;
        }

        .main-header,
        .navbar,
        .navbar-brand {
            background: #1976D2 !important;
            color: #fff !important;
        }

        .navbar-brand,
        .brand-text {
            color: #fff !important;
        }

        .nav-link.active,
        .nav-link:hover {
            background: #FFD600 !important;
            color: #222831 !important;
            border-radius: 4px;
        }

        .btn-primary,
        .badge-success {
            background: #1976D2 !important;
            border: none;
            color: #fff !important;
        }

        .btn-primary:hover {
            background: #1565C0 !important;
        }

        .btn-warning,
        .badge-warning {
            background: #FFD600 !important;
            color: #222831 !important;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.07);
        }

        .post-content img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <img src="assets/logo.png" alt="DechPress Logo" style="height:32px; margin-right:8px;">
                    <span class="brand-text font-weight-light">DechPress</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">Home</a>
                        </li>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <li class="nav-item">
                                <a href="index.php?category=<?php echo $category['id']; ?>" class="nav-link"><?php echo htmlspecialchars($category['name']); ?></a>
                            </li>
                        <?php endwhile; ?>
                    </ul>

                    <!-- Search form -->
                    <form class="form-inline ml-0 ml-md-3" action="index.php" method="get">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" name="search" placeholder="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?php echo htmlspecialchars($post['title']); ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <?php if ($post['featured_image']): ?>
                                    <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <p class="card-text">
                                        <small class="text-muted">
                                            By <?php echo htmlspecialchars($post['author_name']); ?> in
                                            <a href="index.php?category=<?php echo $post['category_id']; ?>"><?php echo htmlspecialchars($post['category_name']); ?></a> |
                                            <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                        </small>
                                    </p>
                                    <div class="post-content">
                                        <?php echo $post['content']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Categories</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="nav nav-pills flex-column">
                                        <li class="nav-item">
                                            <a href="index.php" class="nav-link">
                                                All Posts
                                            </a>
                                        </li>
                                        <?php
                                        $categories->data_seek(0);
                                        while ($category = $categories->fetch_assoc()):
                                        ?>
                                            <li class="nav-item">
                                                <a href="index.php?category=<?php echo $category['id']; ?>" class="nav-link <?php echo $post['category_id'] === $category['id'] ? 'active' : ''; ?>">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2025 Dechellya Assyya.</strong> All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>