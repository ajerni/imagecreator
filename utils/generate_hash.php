<?php
// Simple script to generate a password hash

// Check if password was provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !empty($_POST['password'])) {
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $message = "Hash for '$password': <code>$hash</code>";
} else {
    $message = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 30px;
        }
        .container {
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Password Hash Generator</h1>
        
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="password" class="form-label">Enter Password</label>
                        <input type="text" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Hash</button>
                </form>
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success mt-4">
                        <?php echo $message; ?>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted small">
                            Copy this hash to your login.php file to update the password.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="../index.php" class="btn btn-outline-secondary">&larr; Back to Application</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 