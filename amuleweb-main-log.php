<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule - Logs</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
        if ( isset($_SESSION["auto_refresh"]) && $_SESSION["auto_refresh"] > 0 ) {
            echo '<meta http-equiv="refresh" content="', $_SESSION["auto_refresh"], '">';
        }
    ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body { padding-top: 80px; padding-bottom: 60px; background-color: #212529; color: #e9ecef; }
        
        .log-container {
            background-color: #000;
            color: #00ff00; /* Classic terminal green */
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.85rem;
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #495057;
        }
    </style>
</head>

<body class="animated fadeIn">

    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        
        <div class="card bg-dark border-secondary mb-4">
            <div class="card-body py-2">
                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <a href="amuleweb-main-log.php" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </a>
                    
                    <div class="btn-group">
                        <a href="amuleweb-main-log.php?rstlog=1" class="btn btn-outline-danger" onclick="return confirm('Reset aMule Log?')">
                            <i class="bi bi-trash"></i> Clear aMule Log
                        </a>
                        <a href="amuleweb-main-log.php?rstsrv=1" class="btn btn-outline-warning" onclick="return confirm('Reset Server Log?')">
                            <i class="bi bi-trash"></i> Clear Server Log
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card bg-dark border-secondary">
                    <div class="card-header bg-secondary text-white">
                        <i class="bi bi-terminal me-2"></i> AMULE LOG
                    </div>
                    <div class="card-body p-0">
                        <div class="log-container">
                            <?php 
                                $rst = isset($_GET['rstlog']) ? $_GET['rstlog'] : 0;
                                echo htmlspecialchars(amule_get_log($rst)); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card bg-dark border-secondary">
                    <div class="card-header bg-info text-dark">
                        <i class="bi bi-hdd-network me-2"></i> SERVER LOG
                    </div>
                    <div class="card-body p-0">
                        <div class="log-container" style="color: #00d8ff;"> <?php 
                                $rstsrv = isset($_GET['rstsrv']) ? $_GET['rstsrv'] : 0;
                                echo htmlspecialchars(amule_get_serverinfo($rstsrv)); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
