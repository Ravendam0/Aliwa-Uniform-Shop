<?php if(isset($_GET['message'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    showToast("<?php echo $_GET['message']; ?>", "success");
});
</script>
<?php endif; ?>

<div id="toast-message" 
     style="display:none; position:fixed; top:20px; right:20px; 
            padding:15px 20px; color:#fff; border-radius:8px; 
            box-shadow:0 4px 15px rgba(0,0,0,0.2); 
            z-index:99999;">
</div>

<script>
function showToast(message, type = "error") {
    let toast = document.getElementById("toast-message");
    toast.innerHTML = message;
    
    // Colors
    toast.style.background = type === "success" ? "#28a745" : "#dc3545";
    
    toast.style.display = "block";

    setTimeout(() => {
        toast.style.display = "none";
    }, 3000);
}
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>
    <style>
        .bg {
            animation: slide 3s ease-in-out infinite alternate;
            background-image: linear-gradient(-60deg, #6c3 50%, #09f 50%);
            bottom: 0;
            left: -50%;
            opacity: .5;
            position: fixed;
            right: -50%;
            top: 0;
            z-index: -1;
        }

        .bg2 {
            animation-direction: alternate-reverse;
            animation-duration: 4s;
        }

        .bg3 {
            animation-duration: 5s;
        }

        .content {
            background-color: rgba(255, 255, 255, .8);
            border-radius: .25em;
            box-shadow: 0 0 .25em rgba(0, 0, 0, .25);
            box-sizing: border-box;
            left: 50%;
            padding: 10vmin;
            position: fixed;
            text-align: center;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        h1 {
            font-family: monospace;
        }

        @keyframes slide {
            0% {
                transform: translateX(-25%);
            }

            100% {
                transform: translateX(25%);
            }
        }
    </style>
    <style>
        body {
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .login-card {
            background: #ffffff;
            padding: 35px;
            width: 100%;
            max-width: 380px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.89);
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            color: #333;
        }

        .form-label {
            font-weight: 500;
            color: #444;
        }

        .form-control {
            padding: 10px;
            border-radius: 6px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            font-weight: 600;
        }

        .error {
            margin-top: 15px;
            text-align: center;
            color: #b30000;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h3>Admin Login</h3>

        <form action="codes.php" method="POST">

            <label class="form-label">Username</label>
            <input
                type="text"
                name="username"
                class="form-control mb-3"
                required>

            <label class="form-label">Password</label>
            <input
                type="password"
                name="password"
                class="form-control mb-3"
                required>

            <button type="submit" class="btn btn-primary">Login</button>

            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

        </form>
    </div>

</body>

</html>