<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule - Transfer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
        // Modern PHP refresh logic
        if ( isset($_SESSION["auto_refresh"]) && $_SESSION["auto_refresh"] > 0 ) {
            echo '<meta http-equiv="refresh" content="', $_SESSION["auto_refresh"], '">';
        }
    ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        /* Push content down so it doesn't hide behind the fixed navbar */
        body { padding-top: 80px; padding-bottom: 60px; background-color: #212529; color: #e9ecef; }
        .table-custom td, .table-custom th { vertical-align: middle; font-size: 0.9rem; }
        .progress { height: 20px; position: relative; }
        .progress-text { 
            position: absolute; width: 100%; text-align: center; 
            line-height: 20px; color: white; text-shadow: 1px 1px 2px black; font-size: 0.75rem; 
        }
        .fixed-bottom-custom {
            background-color: #212529;
            border-top: 1px solid #495057;
            padding: 10px 0;
        }
    </style>

    <script>
    function formCommandSubmit(command) {
        if (command == "cancel") {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            if (checkboxes.length === 0) return;
            if (!confirm("Delete selected files?")) return;
        }
        
        <?php if ($_SESSION["guest_login"] != 0) echo 'alert("Guest mode - commands disabled"); return;'; ?>

        var frm = document.forms.mainform;
        frm.command.value = command;
        frm.submit();
    }

    function selectAll(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }
    </script>
</head>

<body class="animated fadeIn">

    <?php include 'navbar.php'; ?>

    <div class="container-fluid mb-3">
        <div class="card bg-dark border-secondary">
            <div class="card-body py-2">
                <form action="amuleweb-main-dload.php" method="post" name="mainform" class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
                    <input type="hidden" name="command">
                    
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-warning" onclick="formCommandSubmit('pause')" title="Pause">
                            <i class="bi bi-pause-fill"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="formCommandSubmit('resume')" title="Resume">
                            <i class="bi bi-play-fill"></i>
                        </button>
                    </div>

                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="formCommandSubmit('priodown')" title="Lower Priority">
                            <i class="bi bi-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="formCommandSubmit('cancel')" title="Remove">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="formCommandSubmit('prioup')" title="Increase Priority">
                            <i class="bi bi-arrow-up"></i>
                        </button>
                    </div>

                    <div class="input-group w-auto">
                        <span class="input-group-text bg-secondary border-secondary text-white"><i class="bi bi-funnel"></i></span>
                        <select name="status" class="form-select bg-dark text-white border-secondary">
                            <?php
                                $all_status = array("all", "Waiting", "Paused", "Downloading");
                                $curr_status = isset($_GET["status"]) ? $_GET["status"] : (isset($_SESSION["filter_status"]) ? $_SESSION["filter_status"] : "all");
                                foreach ($all_status as $s) {
                                    echo '<option' . ($s == $curr_status ? ' selected' : '') . '>' . $s . '</option>';
                                }
                            ?>
                        </select>
                        <select name="category" class="form-select bg-dark text-white border-secondary">
                            <?php
                                $cats = amule_get_categories();
                                $curr_cat = isset($_GET["category"]) ? $_GET["category"] : (isset($_SESSION["filter_cat"]) ? $_SESSION["filter_cat"] : "all");
                                foreach($cats as $c) {
                                    echo '<option' . ($c == $curr_cat ? ' selected' : '') . '>' . $c . '</option>';
                                }
                            ?>
                        </select>
                        <button class="btn btn-primary" type="button" onclick="formCommandSubmit('filter')">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card mb-4 bg-dark border-secondary">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-download me-2"></i> DOWNLOADS
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th style="width: 30px;"><input class="form-check-input" type="checkbox" onclick="selectAll(this)"></th>
                                <th><a href="?sort=name" class="text-decoration-none text-info">Filename</a></th>
                                <th><a href="?sort=size" class="text-decoration-none text-info">Size</a></th>
                                <th><a href="?sort=size_done" class="text-decoration-none text-info">Completed</a></th>
                                <th><a href="?sort=speed" class="text-decoration-none text-info">Speed</a></th>
                                <th>Progress</th>
                                <th>Sources</th>
                                <th>Status</th>
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // --- Helper Functions ---
                                function CastToXBytes($size) {
                                    if ( $size < 1024 ) return $size . " b";
                                    elseif ( $size < 1048576 ) return round($size / 1024.0, 2) . " kb";
                                    elseif ( $size < 1073741824 ) return round($size / 1048576.0, 2) . " mb";
                                    else return round($size / 1073741824.0, 2) . " gb";
                                }

                                function PrioString($file) {
                                    $prionames = array(0 => "Low", 1 => "Normal", 2 => "High", 3 => "Very high", 4 => "Very low", 5=> "Auto", 6 => "Release");
                                    return $prionames[$file->prio] . ($file->prio_auto == 1 ? " (auto)" : "");
                                }

                                function StatusString($file) {
                                    if ( $file->status == 7 ) return "Paused";
                                    elseif ( $file->src_count_xfer > 0 ) return "Downloading";
                                    else return "Waiting";
                                }

                                // --- Logic ---
                                $downloads = amule_load_vars("downloads");

                                // Handle Commands (Pause/Resume/Delete)
                                if ( isset($_POST["command"]) && $_POST["command"] != "" && $_SESSION["guest_login"] == 0 ) {
                                    foreach ( $_POST as $name => $val) {
                                        if ( strlen($name) == 32 && $val == "on" ) {
                                            amule_do_download_cmd($name, $_POST["command"]);
                                        }
                                    }
                                    // Reload variables after command
                                    $downloads = amule_load_vars("downloads"); 
                                }

                                foreach ($downloads as $file) {
                                    // Calculate percentage
                                    $percent = ($file->size > 0) ? ($file->size_done / $file->size * 100) : 0;
                                    $percent = number_format($percent, 1);
                                    
                                    // Status Badge Color Logic
                                    $statusBadge = 'bg-secondary';
                                    $statusCode = StatusString($file);
                                    if ($statusCode == "Paused") $statusBadge = 'bg-warning text-dark';
                                    elseif ($statusCode == "Downloading") $statusBadge = 'bg-success';
                                    else $statusBadge = 'bg-info text-dark';

                                    echo '<tr>';
                                    echo '<td><input class="form-check-input" type="checkbox" name="' . $file->hash . '"></td>';
                                    echo '<td class="text-truncate" style="max-width: 300px;" title="' . htmlspecialchars($file->name) . '">' . htmlspecialchars($file->name) . '</td>';
                                    echo '<td>' . CastToXBytes($file->size) . '</td>';
                                    echo '<td>' . CastToXBytes($file->size_done) . ' (' . $percent . '%)</td>';
                                    echo '<td>' . ($file->speed > 0 ? CastToXBytes($file->speed).'/s' : '-') . '</td>';
                                    
                                    // Progress Bar
                                    echo '<td>
                                            <div class="progress bg-secondary">
                                                <div class="progress-bar ' . $statusBadge . '" role="progressbar" style="width: ' . $percent . '%"></div>
                                                <span class="progress-text">' . $percent . '%</span>
                                            </div>
                                          </td>';
                                          
                                    echo '<td>' . $file->src_count . ' (' . $file->src_count_xfer . ')</td>';
                                    echo '<td><span class="badge ' . $statusBadge . '">' . $statusCode . '</span></td>';
                                    echo '<td>' . PrioString($file) . '</td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar fixed-bottom-custom fixed-bottom">
        <div class="container-fluid justify-content-center">
            <form class="d-flex gap-2" action="amuleweb-main-dload.php" method="post">
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
            <div class="d-flex align-items-center ms-3 text-white small">
                 <?php
                    $stats = amule_get_stats();
                    if ( $stats["id"] == 0 ) {
                        echo '<span class="badge bg-danger me-1">ED2K: Not connected</span>';
                    } elseif ( $stats["id"] == 0xffffffff ) {
                        echo '<span class="badge bg-info text-dark me-1">ED2K: Connecting</span>';
                    } else {
                        $idStatus = ($stats["id"] < 16777216) ? "LowID" : "HighID";
                        $badgeColor = ($idStatus == "LowID") ? "bg-warning text-dark" : "bg-success";
                        echo '<span class="badge ' . $badgeColor . ' me-1">ED2K: Connected (' . $idStatus . ')</span>';
                    }

                    if ( $stats["kad_connected"] == 1 ) {
                        $kadStatus = ($stats["kad_firewalled"] == 1) ? "Firewalled" : "OK";
                        $kadColor = ($kadStatus == "Firewalled") ? "bg-warning text-dark" : "bg-success";
                        echo '<span class="badge ' . $kadColor . '">KAD: Connected (' . $kadStatus . ')</span>';
                    } else {
                        echo '<span class="badge bg-danger">KAD: Disconnected</span>';
                    }
                 ?>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
