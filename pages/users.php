<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Prevent deleting self
    if ($id !== $_SESSION['user_id']) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: users.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (isset($_POST['id'])) {
        // Update existing user
        $id = (int)$_POST['id'];
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $username, $email, $role, $hashed_password, $id);
        } else {
            $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $email, $role, $id);
        }
    } else {
        // Create new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, role, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $role, $hashed_password);
    }

    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    } else {
        $error = "Error saving user: " . $conn->error;
    }
}

// Get user for editing
$user = [
    'id' => '',
    'username' => '',
    'email' => '',
    'role' => 'author'
];

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    }
}

// Get all users
$sql = "SELECT u.*, COUNT(p.id) as post_count 
        FROM users u 
        LEFT JOIN posts p ON u.id = p.author_id 
        GROUP BY u.id 
        ORDER BY u.username";
$users = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - DechPress</title>
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
            color: #222831 !important;
        }

        .main-header,
        .navbar,
        .main-sidebar,
        .sidebar,
        .navbar-brand {
            background: #1976D2 !important;
            color: #fff !important;
        }

        .brand-link img {
            height: 48px !important;
            margin-right: 16px !important;
        }

        .navbar-brand,
        .brand-text {
            color: #fff !important;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .nav-link,
        .nav-icon,
        .sidebar .nav-link,
        .sidebar .nav-icon {
            color: #fff !important;
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

        .small-box .icon,
        .small-box .inner h3,
        .small-box .inner p {
            color: #fff !important;
        }

        .navbar-nav .nav-link i,
        .navbar-nav .fa-bars,
        .navbar-nav .fa-sign-out-alt {
            color: #fff !important;
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
                            <a href="categories.php" class="nav-link">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link active">
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
                            <h1 class="m-0">Users</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Modern Users Management Start -->
                    <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
                        <h1 style="font-size:2rem;font-weight:bold;">Users Management</h1>
                        <div class="quick-actions">
                            <a href="#user-form" class="btn btn-primary" style="border-radius:20px;font-weight:bold;margin-right:8px;">+ Add User</a>
                            <a href="#" class="btn btn-warning" style="border-radius:20px;font-weight:bold;">Export</a>
                        </div>
                    </div>
                    <div class="stats-bar" style="display:flex;gap:2rem;margin-bottom:2rem;flex-wrap:wrap;">
                        <div class="stat-card" style="background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(25,118,210,0.10);padding:1.2rem 2rem;min-width:160px;">
                            <div style="font-size:1.2rem;color:#1976D2;font-weight:bold;">Total Users</div>
                            <div style="font-size:2rem;font-weight:bold;"><?php echo $users->num_rows; ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card" id="user-form" style="border-radius:16px;box-shadow:0 2px 8px rgba(25,118,210,0.07);padding:2rem;">
                                <div class="card-header" style="background:none;border:none;padding:0 0 1rem 0;">
                                    <h3 class="card-title" style="font-size:1.3rem;font-weight:bold;"><?php echo $user['id'] ? 'Edit' : 'Add'; ?> User</h3>
                                </div>
                                <div class="card-body" style="padding:0;">
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <form action="" method="post">
                                        <?php if ($user['id']): ?>
                                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password"><?php echo $user['id'] ? 'New Password (leave blank to keep current)' : 'Password'; ?></label>
                                            <input type="password" class="form-control" id="password" name="password" <?php echo $user['id'] ? '' : 'required'; ?>>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" id="role" name="role">
                                                <option value="author" <?php echo $user['role'] === 'author' ? 'selected' : ''; ?>>Author</option>
                                                <option value="editor" <?php echo $user['role'] === 'editor' ? 'selected' : ''; ?>>Editor</option>
                                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary" style="border-radius:20px;font-weight:bold;">Save User</button>
                                        <?php if ($user['id']): ?>
                                            <a href="users.php" class="btn btn-default" style="border-radius:20px;">Cancel</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card" style="border-radius:16px;box-shadow:0 2px 8px rgba(25,118,210,0.07);padding:2rem;">
                                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1rem;">
                                    <input type="text" class="form-control" placeholder="Search users..." style="max-width:300px;">
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" style="background:#fff;border-radius:12px;overflow:hidden;">
                                        <thead style="background:#1976D2;color:#fff;">
                                            <tr>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Posts</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $users->data_seek(0);
                                            while ($row = $users->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                    <td><?php echo ucfirst($row['role']); ?></td>
                                                    <td><?php echo $row['post_count']; ?></td>
                                                    <td>
                                                        <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" style="border-radius:50%;margin-right:4px;">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if ($row['id'] !== $_SESSION['user_id']): ?>
                                                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" style="border-radius:50%;" onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modern Users Management End -->
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