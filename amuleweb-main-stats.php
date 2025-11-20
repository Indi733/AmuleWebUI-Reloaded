<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule - Statistics</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
        if ( isset($_SESSION["auto_refresh"]) && $_SESSION["auto_refresh"] > 0 ) {
            echo '<meta http-equiv="refresh" content="', $_SESSION["auto_refresh"], '">';
        }
        amule_load_vars("stats_graph");
    ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body { padding-top: 80px; padding-bottom: 60px; background-color: #212529; color: #e9ecef; }
        
        /* Style the iframe to look integrated */
        iframe {
            border: 1px solid #495057;
            border-radius: 0.375rem;
            background-color: white; /* Keep white for now as the internal page isn't dark yet */
        }
        
        .carousel-item img {
            max-height: 400px;
            object-fit: contain;
            background-color: #000;
            border-radius: 5px;
        }
        .carousel-caption {
            background: rgba(0,0,0,0.7);
            border-radius: 10px;
            padding: 5px 10px;
            bottom: 20px;
        }
    </style>
</head>

<body class="animated fadeIn">

    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        <div class="card bg-dark border-secondary">
            <div class="card-header bg-primary text-white fw-bold text-center">
                <i class="bi bi-bar-chart me-2"></i> STATISTICS
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <h5 class="text-info border-bottom border-secondary pb-2">Details</h5>
                        <iframe name="stats" src="stats_tree.php" width="100%" height="450"></iframe>
                    </div>

                    <div class="col-lg-8">
                        <h5 class="text-info border-bottom border-secondary pb-2">Graphs</h5>
                        
                        <div id="statsCarousel" class="carousel slide border border-secondary rounded p-2 bg-black" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#statsCarousel" data-bs-slide-to="0" class="active"></button>
                                <button type="button" data-bs-target="#statsCarousel" data-bs-slide-to="1"></button>
                                <button type="button" data-bs-target="#statsCarousel" data-bs-slide-to="2"></button>
                                <button type="button" data-bs-target="#statsCarousel" data-bs-slide-to="3"></button>
                            </div>

                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="amule_stats_download.png" class="d-block w-100" alt="Download Graph">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Downloads</h5>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="amule_stats_upload.png" class="d-block w-100" alt="Upload Graph">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Uploads</h5>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="amule_stats_conncount.png" class="d-block w-100" alt="Connections Graph">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Active Connections</h5>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="amule_stats_kad.png" class="d-block w-100" alt="Kad Graph">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Kademlia Nodes</h5>
                                    </div>
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#statsCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#statsCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar fixed-bottom-custom fixed-bottom">
        <div class="container-fluid justify-content-center">
            <form class="d-flex gap-2" action="amuleweb-main-stats.php" method="post">
                <div class="input-group">
                    <span class="input-group-text bg-secondary border-secondary text-white">ed2k://</span>
                    <input type="text" name="ed2klink" class="form-control bg-dark text-white border-secondary" placeholder="Paste link here" size="50">
                    <select name="selectcat" class="form-select bg-dark text-white border-secondary" style="max-width: 120px;">
                        <?php
                           $cats = amule_get_categories();
                           foreach($cats as $c) echo "<option>$c</option>";
                        ?>
                    </select>
                    <button type="submit" name="Submit" class="btn btn-primary">Download</button>
                </div>
            </form>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
