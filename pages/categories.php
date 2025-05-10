<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle category deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: categories.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $slug = strtolower(str_replace(' ', '-', $name));

    if (isset($_POST['id'])) {
        // Update existing category
        $id = (int)$_POST['id'];
        $sql = "UPDATE categories SET name = ?, description = ?, slug = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $description, $slug, $id);
    } else {
        // Create new category
        $sql = "INSERT INTO categories (name, description, slug) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $description, $slug);
    }

    if ($stmt->execute()) {
        header("Location: categories.php");
        exit();
    } else {
        $error = "Error saving category: " . $conn->error;
    }
}

// Get category for editing
$category = [
    'id' => '',
    'name' => '',
    'description' => ''
];

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $sql = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $category = $result->fetch_assoc();
    }
}

// Get all categories
$sql = "SELECT c.*, COUNT(p.id) as post_count 
        FROM categories c 
        LEFT JOIN posts p ON c.id = p.category_id 
        GROUP BY c.id 
        ORDER BY c.name";
$categories = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - DechPress</title>
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
            color: #fff !important;
        }

        .main-header,
        .navbar,
        .main-sidebar,
        .sidebar,
        .navbar-brand {
            background: #1976D2 !important;
            color: #fff !important;
        }

        .navbar-brand,
        .brand-text {
            color: #fff !important;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .nav-link.active,
        .nav-link:hover {
            background: #FFD600 !important;
            color: #1976D2 !important;
            border-radius: 4px;
        }

        .btn-primary,
        .badge-success,
        .small-box.bg-info,
        .small-box.bg-success,
        .small-box.bg-warning {
            background: #FFD600 !important;
            border: none;
            color: #fff !important;
        }

        .btn-primary:hover {
            background: #1565C0 !important;
        }

        .btn-warning,
        .badge-warning,
        .small-box.bg-warning {
            background: #FFD600 !important;
            color: #fff !important;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.07);
        }

        .small-box .icon,
        .small-box .inner h3,
        .small-box .inner p {
            color: #fff !important;
        }

        .navbar-nav .nav-link,
        .navbar-nav .nav-link i,
        .navbar-nav .fa-bars,
        .navbar-nav .fa-sign-out-alt {
            color: #fff !important;
        }

        .card,
        .form-control,
        .table,
        .table td,
        .table th,
        .card-body,
        label,
        select,
        input,
        textarea {
            color: #222831 !important;
        }

        .sidebar .nav-link,
        .sidebar .nav-icon,
        .nav-link,
        .nav-icon {
            color: #fff !important;
        }

        .brand-link {
            display: flex;
            align-items: center;
        }

        .brand-link img {
            height: 48px !important;
            margin-right: 16px !important;
            vertical-align: middle;
        }

        .brand-text {
            color: #fff !important;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 2px;
            vertical-align: middle;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../auth/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="../index.php" class="brand-link">
                <img src="../public/assets/logo.png" alt="DechPress Logo" style="height:32px; margin-right:8px;">
                <span class="brand-text font-weight-light">DechPress</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="../index.php" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="posts.php" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Posts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="categories.php" class="nav-link active">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Categories</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo $category['id'] ? 'Edit' : 'Add'; ?> Category</h3>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>

                                    <form action="" method="post">
                                        <?php if ($category['id']): ?>
                                            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($category['description']); ?></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Save Category</button>
                                        <?php if ($category['id']): ?>
                                            <a href="categories.php" class="btn btn-default">Cancel</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Posts</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $categories->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                                    <td><?php echo $row['post_count']; ?></td>
                                                    <td>
                                                        <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
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