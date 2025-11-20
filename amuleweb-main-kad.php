<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule - Kad</title>
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
        .table-custom td, .table-custom th { vertical-align: middle; font-size: 0.9rem; }
        /* Custom width for IP inputs */
        .ip-input { width: 60px; text-align: center; }
    </style>
</head>

<body class="animated fadeIn">

    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        
        <div class="card bg-dark border-secondary mb-4">
            <div class="card-body py-3">
                <form action="amuleweb-main-kad.php" method="post" name="mainform">
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                        <label class="fw-bold text-info me-2">Bootstrap from Node:</label>
                        
                        <div class="input-group w-auto">
                            <input type="text" name="ip0" class="form-control bg-dark text-white border-secondary ip-input" placeholder="255" maxlength="3">
                            <span class="input-group-text bg-secondary border-secondary text-white">.</span>
                            <input type="text" name="ip1" class="form-control bg-dark text-white border-secondary ip-input" placeholder="255" maxlength="3">
                            <span class="input-group-text bg-secondary border-secondary text-white">.</span>
                            <input type="text" name="ip2" class="form-control bg-dark text-white border-secondary ip-input" placeholder="255" maxlength="3">
                            <span class="input-group-text bg-secondary border-secondary text-white">.</span>
                            <input type="text" name="ip3" class="form-control bg-dark text-white border-secondary ip-input" placeholder="255" maxlength="3">
                            <span class="input-group-text bg-secondary border-secondary text-white">:</span>
                            <input type="text" name="port" class="form-control bg-dark text-white border-secondary" placeholder="Port" maxlength="5" style="width: 80px;">
                            <button type="submit" name="Submit" class="btn btn-warning">Connect</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card bg-dark border-secondary" style="max-width: 800px; margin: 0 auto;">
            <div class="card-header bg-primary text-white fw-bold text-center">
                <i class="bi bi-diagram-3 me-2"></i> KAD STATUS
            </div>
            <div class="card-body">
                <?php
                    $stats = amule_get_stats();
                    
                    // Determine Connection Badge
                    if ($stats["kad_connected"] == 1) {
                        $connBadge = '<span class="badge bg-success">Connected</span>';
                        if ($stats["kad_firewalled"] == 0) { 
                            $fwBadge = '<span class="badge bg-success">OK</span>'; 
                        } else { 
                            $fwBadge = '<span class="badge bg-warning text-dark">Firewalled</span>'; 
                        }
                    } else {
                        $connBadge = '<span class="badge bg-danger">Disconnected</span>';
                        $fwBadge = '<span class="badge bg-secondary">-</span>';
                    }
                ?>

                <table class="table table-dark table-striped table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th class="w-50 text-end pe-4">Connection State</th>
                            <td class="w-50 ps-4"><?php echo $connBadge; ?></td>
                        </tr>
                        <tr>
                            <th class="text-end pe-4">Firewall Status</th>
                            <td class="ps-4"><?php echo $fwBadge; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center bg-black p-3">
                                <img src="amule_stats_kad.png" class="img-fluid rounded border border-secondary" alt="Kad Stats Graph">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <nav class="navbar fixed-bottom-custom fixed-bottom">
        <div class="container-fluid justify-content-center">
            <form class="d-flex gap-2" action="amuleweb-main-kad.php" method="post">
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
