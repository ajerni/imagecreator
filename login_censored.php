<?php
session_start();

// Randomly select a background image from the backgrounds folder
$background_dir = __DIR__ . '/backgrounds';
$background_files = glob($background_dir . '/*.jpg');
$background_url = '';
if ($background_files && count($background_files) > 0) {
    $random_file = $background_files[array_rand($background_files)];
    $background_url = 'backgrounds/' . basename($random_file);
}

// Check if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit();
}

// Check if form submitted
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real-world scenario, you should store this in a config file
    $stored_hash = '$2y$10$GIfddddddx3vTWQO.uCGjPdTU0cEGze'; // use the hash generator to create a new hash
    
    $password = $_POST['password'] ?? '';
    
    if (password_verify($password, $stored_hash)) {
        $_SESSION['logged_in'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Creator - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
            font-family: 'Inter', Arial, sans-serif;
            <?php if ($background_url): ?>
            background-image: url('<?= htmlspecialchars($background_url) ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            <?php endif; ?>
        }
        .login-form {
            width: 100%;
            max-width: 400px;
            padding: 0;
            margin: auto;
        }
        .card {
            background: rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37), 0 1.5px 8px 0 rgba(0,0,0,0.10);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 28px;
            overflow: hidden;
            transition: transform 0.2s cubic-bezier(.4,2,.6,1), box-shadow 0.2s;
        }
        .card:hover {
            transform: scale(1.025);
            box-shadow: 0 16px 48px 0 rgba(31, 38, 135, 0.45), 0 3px 16px 0 rgba(0,0,0,0.13);
        }
        .card-header {
            background: linear-gradient(90deg, #3575ec 0%, #42e695 100%);
            color: #fff;
            box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.10);
            text-align: center;
            text-shadow: 0 2px 8px rgba(31, 38, 135, 0.18);
        }
        .card-header h3 {
            font-weight: 600;
            letter-spacing: 1px;
        }
        .card-body {
            padding: 2rem 2rem 1.5rem 2rem;
        }
        .form-label {
            font-weight: 500;
            color: #222;
        }
        .form-control {
            margin-bottom: 18px;
            border-radius: 12px;
            border: 1.5px solid #e0e7ef;
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.7);
            box-shadow: 0 1px 4px 0 rgba(31, 38, 135, 0.07);
            transition: border 0.2s;
        }
        .form-control:focus {
            border: 1.5px solid #4f8cff;
            outline: none;
            background: rgba(255,255,255,0.9);
        }
        .btn-primary {
            background: linear-gradient(90deg, #3575ec 0%, #42e695 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.15rem;
            box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(90deg, #42e695 0%, #3575ec 100%);
            box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.18);
        }
        .alert-danger {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Image Creator Login</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 