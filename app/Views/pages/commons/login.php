<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CodeIgniter 4 Starter Panel - Login">
    <meta name="author" content="Gilang Heavy">
    <meta name="keywords" content="codeigniter, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="favicon.ico" />

    <title>Sign In | CodeIgniter 4 Starter Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%233b82f6" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            pointer-events: none;
        }
        
        .login-container {
            position: relative;
            z-index: 1;
        }
        
        .card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.5);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
            border-radius: 20px;
        }
        
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }
        
        .bg-shape-1 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            top: 10%;
            left: -5%;
            animation-delay: 0s;
        }
        
        .bg-shape-2 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            top: 60%;
            right: -3%;
            animation-delay: 2s;
        }
        
        .bg-shape-3 {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #93c5fd, #60a5fa);
            top: 30%;
            left: 70%;
            animation-delay: 4s;
        }
        
        .bg-shape-4 {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #dbeafe, #93c5fd);
            top: 80%;
            left: 20%;
            animation-delay: 1s;
        }
        
        .brand-logo {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }
        
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
        }
        
        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        

        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        @media (max-width: 576px) {
            .card {
                margin: 1rem;
            }
            
            .brand-logo {
                width: 60px;
                height: 60px;
                border-radius: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Background Decorative Shapes -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>
    <div class="bg-shape bg-shape-4"></div>

    <main class="d-flex w-100 login-container">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <?= $this->include('components/alerts'); ?>

                        <div class="text-center mt-4">
                            <div class="brand-logo">
                                <i class="bi bi-lightning-charge-fill text-white" style="font-size: 2.5rem;"></i>
                            </div>
                            <h1 class="h2 text-dark fw-bold">Login Account</h1>                            
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-4">
                                    <form action="<?= base_url('login'); ?>" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">
                                                <i class="bi bi-envelope me-2"></i>Email
                                            </label>
                                            <input class="form-control form-control-lg" type="email" name="inputEmail" placeholder="Enter your email" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">
                                                <i class="bi bi-lock me-2"></i>Password
                                            </label>
                                            <input class="form-control form-control-lg" type="password" name="inputPassword" placeholder="Enter your password" />
                                        </div>
                                    
                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" class="btn btn-lg btn-primary">
                                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign in
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
