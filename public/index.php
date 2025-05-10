<?php
require_once '../config/database.php';

// Get categories for sidebar
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

// Get posts
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 5;
$offset = ($page - 1) * $per_page;

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$where = "WHERE p.status = 'published'";
if ($category_id) {
    $where .= " AND p.category_id = " . $category_id;
}
if ($search) {
    $search = $conn->real_escape_string($search);
    $where .= " AND (p.title LIKE '%$search%' OR p.content LIKE '%$search%')";
}

$sql = "SELECT p.*, u.username as author_name, c.name as category_name 
        FROM posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        LEFT JOIN categories c ON p.category_id = c.id 
        $where 
        ORDER BY p.created_at DESC 
        LIMIT $offset, $per_page";
$posts = $conn->query($sql);

// Get total posts for pagination
$sql = "SELECT COUNT(*) as total FROM posts p $where";
$total = $conn->query($sql)->fetch_assoc()['total'];
$total_pages = ceil($total / $per_page);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Sederhana</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
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
                    <span class="brand-text font-weight-light">CMS Sederhana</span>
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
                                <a href="?category=<?php echo $category['id']; ?>" class="nav-link"><?php echo htmlspecialchars($category['name']); ?></a>
                            </li>
                        <?php endwhile; ?>
                    </ul>

                    <!-- Search form -->
                    <form class="form-inline ml-0 ml-md-3" action="" method="get">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
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
                            <h1 class="m-0">Blog Posts</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <?php if ($posts->num_rows > 0): ?>
                                <?php while ($post = $posts->fetch_assoc()): ?>
                                    <div class="card">
                                        <?php if ($post['featured_image']): ?>
                                            <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    By <?php echo htmlspecialchars($post['author_name']); ?> in
                                                    <a href="?category=<?php echo $post['category_id']; ?>"><?php echo htmlspecialchars($post['category_name']); ?></a> |
                                                    <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                                </small>
                                            </p>
                                            <p class="card-text"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                            <a href="post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-primary">Read More</a>
                                        </div>
                                    </div>
                                    <br>
                                <?php endwhile; ?>

                                <!-- Pagination -->
                                <?php if ($total_pages > 1): ?>
                                    <nav>
                                        <ul class="pagination">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
                                                </li>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-info">No posts found.</div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Categories</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="nav nav-pills flex-column">
                                        <li class="nav-item">
                                            <a href="index.php" class="nav-link <?php echo !$category_id ? 'active' : ''; ?>">
                                                All Posts
                                            </a>
                                        </li>
                                        <?php
                                        $categories->data_seek(0);
                                        while ($category = $categories->fetch_assoc()):
                                        ?>
                                            <li class="nav-item">
                                                <a href="?category=<?php echo $category['id']; ?>" class="nav-link <?php echo $category_id === $category['id'] ? 'active' : ''; ?>">
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
            <strong>Copyright &copy; 2024 <a href="#">CMS Sederhana</a>.</strong> All rights reserved.
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