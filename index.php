<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DechPress</title>
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

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.07);
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

        .small-box .small-box-footer {
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
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
                <img src="public/assets/logo.png" alt="DechPress Logo" style="height:32px; margin-right:8px;">
                <span class="brand-text font-weight-light">DechPress</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/posts.php" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Posts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/categories.php" class="nav-link">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/users.php" class="nav-link">
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
                            <h1 class="m-0">Dashboard</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Modern Dashboard Start -->
                    <div class="dashboard-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
                        <div>
                            <h1 style="font-size:2rem;font-weight:bold;margin-bottom:0.5rem;">Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'DechPress User'; ?>!</h1>
                            <div style="color:#1976D2;font-size:1.1rem;">Have a productive day with DechPress 🚀</div>
                        </div>
                        <div class="quick-actions">
                            <a href="pages/post-edit.php" class="btn btn-primary" style="border-radius:20px;font-weight:bold;margin-right:8px;">+ Add Post</a>
                            <a href="pages/categories.php" class="btn btn-warning" style="border-radius:20px;font-weight:bold;margin-right:8px;">+ Add Category</a>
                            <a href="pages/users.php" class="btn btn-success" style="border-radius:20px;font-weight:bold;">+ Add User</a>
                        </div>
                    </div>
                    <div class="dashboard-stats" style="display:flex;gap:2rem;margin-bottom:2rem;flex-wrap:wrap;">
                        <div class="stat-card" style="background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(25,118,210,0.10);padding:2rem 2.5rem;display:flex;align-items:center;min-width:220px;position:relative;transition:transform 0.2s,box-shadow 0.2s;flex:1;">
                            <div class="stat-icon" style="font-size:3rem;color:#1976D2;opacity:0.15;position:absolute;right:20px;top:20px;"><i class="fas fa-file-alt"></i></div>
                            <div class="stat-info" style="z-index:1;">
                                <div class="stat-number" style="font-size:2.5rem;font-weight:bold;color:#1976D2;">150</div>
                                <div class="stat-label" style="font-size:1.1rem;color:#222831;">New Posts</div>
                                <div class="progress" style="height:6px;background:#eee;border-radius:3px;margin-top:10px;width:120px;">
                                    <div style="width:75%;background:#FFD600;height:100%;border-radius:3px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card" style="background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(25,118,210,0.10);padding:2rem 2.5rem;display:flex;align-items:center;min-width:220px;position:relative;transition:transform 0.2s,box-shadow 0.2s;flex:1;">
                            <div class="stat-icon" style="font-size:3rem;color:#1976D2;opacity:0.15;position:absolute;right:20px;top:20px;"><i class="fas fa-folder"></i></div>
                            <div class="stat-info" style="z-index:1;">
                                <div class="stat-number" style="font-size:2.5rem;font-weight:bold;color:#1976D2;">53</div>
                                <div class="stat-label" style="font-size:1.1rem;color:#222831;">Categories</div>
                                <div class="progress" style="height:6px;background:#eee;border-radius:3px;margin-top:10px;width:120px;">
                                    <div style="width:60%;background:#FFD600;height:100%;border-radius:3px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card" style="background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(25,118,210,0.10);padding:2rem 2.5rem;display:flex;align-items:center;min-width:220px;position:relative;transition:transform 0.2s,box-shadow 0.2s;flex:1;">
                            <div class="stat-icon" style="font-size:3rem;color:#1976D2;opacity:0.15;position:absolute;right:20px;top:20px;"><i class="fas fa-users"></i></div>
                            <div class="stat-info" style="z-index:1;">
                                <div class="stat-number" style="font-size:2.5rem;font-weight:bold;color:#1976D2;">44</div>
                                <div class="stat-label" style="font-size:1.1rem;color:#222831;">Users</div>
                                <div class="progress" style="height:6px;background:#eee;border-radius:3px;margin-top:10px;width:120px;">
                                    <div style="width:90%;background:#FFD600;height:100%;border-radius:3px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Mini Chart -->
                    <div style="background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(25,118,210,0.07);padding:1.5rem 2rem;margin-bottom:2rem;">
                        <h2 style="font-size:1.3rem;margin-bottom:1rem;color:#1976D2;">Posts per Month</h2>
                        <canvas id="postsChart" height="60"></canvas>
                    </div>
                    <!-- Recent Activity -->
                    <div class="dashboard-activity" style="background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(25,118,210,0.07);padding:1.5rem 2rem;margin-bottom:2rem;">
                        <h2 style="font-size:1.3rem;margin-bottom:1rem;color:#1976D2;">Recent Activity</h2>
                        <ul style="list-style:none;padding:0;margin:0;">
                            <li style="margin-bottom:0.5rem;color:#222831;"><b>Dechellya</b> added a new post: <i>"Tips Programming Modern"</i></li>
                            <li style="margin-bottom:0.5rem;color:#222831;"><b>Admin</b> created a new category: <i>"Web Development"</i></li>
                            <li style="margin-bottom:0.5rem;color:#222831;"><b>Dechellya</b> registered as a new user</li>
                        </ul>
                    </div>
                    <!-- Info Panel -->
                    <div style="background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(25,118,210,0.07);padding:1.5rem 2rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;">
                        <div>
                            <b>DechPress</b> v1.0.0 &mdash; Modern CMS for Everyone
                        </div>
                        <div>
                            <a href="#" style="color:#1976D2;font-weight:bold;">Documentation</a>
                        </div>
                    </div>
                    <!-- Modern Dashboard End -->
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
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('postsChart').getContext('2d');
        const postsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Posts',
                    data: [12, 19, 8, 15, 22, 30, 25, 18, 20, 24, 28, 32],
                    backgroundColor: 'rgba(25, 118, 210, 0.2)',
                    borderColor: '#1976D2',
                    borderWidth: 2,
                    pointBackgroundColor: '#FFD600',
                    pointBorderColor: '#1976D2',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>