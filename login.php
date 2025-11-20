<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>aMule - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body, html {
            height: 100%;
            background-color: #212529;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
        }
        .logo-img {
            width: 150px;
            margin-bottom: 20px;
        }
        .brand-color {
            color: #4db6ac;
        }
    </style>

    <script>
        function login_init() {
            if (top.location != self.location) {
                top.location = self.location.href;
            }
            document.getElementById('pass').focus();
        }
    </script>
</head>

<body class="d-flex align-items-center justify-content-center animated fadeIn" onload="login_init();">

    <div class="login-container text-center">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <img src="logo-brax.png" class="logo-img animated rubberBand" alt="aMule Logo">
                <h2 class="mb-4 brand-color">aMule WebUI</h2>
                <p class="text-muted mb-4">Please login to access the interface</p>

                <form name="login" method="post" action="login.php">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <div class="form-floating flex-grow-1">
                            <input type="password" name="pass" id="pass" class="form-control" placeholder="Password" required>
                            <label for="pass">Password</label>
                        </div>
                        <button class="btn btn-primary" type="submit" name="submit" value="Submit">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
