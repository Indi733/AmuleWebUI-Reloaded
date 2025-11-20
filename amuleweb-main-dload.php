<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule - Transfer</title>
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
        body { padding-top: 80px; padding-bottom: 60px; }
        .table-custom td, .table-custom th { vertical-align: middle; font-size: 0.9rem; }
        .progress { height: 20px; position: relative; }
        .progress-text { 
            position: absolute; width: 100%; text-align: center; 
            line-height: 20px; color: white; text-shadow: 1px 1px 2px black; font-size: 0.75rem; 
        }
        /* Fixed Footer */
        .fixed-bottom-custom {
            background-color: #212529;
            border-top: 1px solid #495057;
            padding: 10px 0;
        }
    </style>

    <script>
    function formCommandSubmit(command) {
        if (command == "cancel") {
            var checkboxes = document.querySelectorAll('input[name^="download_"]:checked');
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

    <?php include 'navbar_snippet.php'; // Or paste the navbar code here ?>

    <div class="container-fluid mb-3">
        <div class="card">
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
                        <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                        <select name="status" class="form-select">
                            <?php
                                $all_status = array("all", "Waiting", "Paused", "Downloading");
                                $curr_status = isset($_GET["status"]) ? $_GET["status"] : (isset($_SESSION["filter_status"]) ? $_SESSION["filter_status"] : "all");
                                foreach ($all_status as $s) {
                                    echo '<option' . ($s == $curr_status ? ' selected' : '') . '>' . $s . '</option>';
                                }
                            ?>
                        </select>
                        <select name="category" class="form-select">
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
        <div class="card mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-download me-2"></i> DOWNLOADS
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-custom mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 30px;"><input class="form-check-input" type="checkbox" onclick="selectAll(this)"></th>
                                <th><a href="?sort=name" class="text-decoration-none text-white">Filename</a></th>
                                <th><a href="?sort=size" class="text-decoration-none text-white">Size</a></th>
                                <th><a href="?sort=size_done" class="text-decoration-none text-white">Completed</a></th>
                                <th><a href="?sort=speed" class="text-decoration-none text-white">Speed</a></th>
                                <th>Progress</th>
                                <th>Sources</th>
                                <th>Status</th>
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // --- PHP LOGIC (Simplified for Modernization) ---
                                // Use $_GET instead of $HTTP_GET_VARS
                                // ... [Assume Helper functions CastToXBytes etc exist] ...
                                
                                $downloads = amule_load_vars("downloads");
                                
                                // Sorting logic would go here (same as original but cleaned up)

                                foreach ($downloads as $file) {
                                    // Calculate percentage
                                    $percent = ($file->size > 0) ? ($file->size_done / $file->size * 100) : 0;
                                    $percent = number_format($percent, 1);
                                    
                                    // Determine Status Badge Color
                                    $statusBadge = 'bg-secondary';
                                    if ($file->status == 7) $statusBadge = 'bg-warning text-dark'; // Paused
                                    elseif ($file->src_count_xfer > 0) $statusBadge = 'bg-success'; // Downloading
                                    else $statusBadge = 'bg-info text-dark'; // Waiting

                                    echo '<tr>';
                                    echo '<td><input class="form-check-input" type="checkbox" name="' . $file->hash . '"></td>';
                                    echo '<td class="text-truncate" style="max-width: 300px;" title="' . htmlspecialchars($file->name) . '">' . htmlspecialchars($file->name) . '</td>';
                                    echo '<td>' . CastToXBytes($file->size) . '</td>';
                                    echo '<td>' . CastToXBytes($file->size_done) . ' (' . $percent . '%)</td>';
                                    echo '<td>' . ($file->speed > 0 ? CastToXBytes($file->speed).'/s' : '-') . '</td>';
                                    
                                    // Modern Bootstrap 5 Progress Bar
                                    echo '<td>
                                            <div class="progress">
                                                <div class="progress-bar ' . $statusBadge . '" role="progressbar" style="width: ' . $percent . '%"></div>
                                                <span class="progress-text">' . $percent . '%</span>
                                            </div>
                                          </td>';
                                          
                                    echo '<td>' . $file->src_count . ' (' . $file->src_count_xfer . ')</td>';
                                    echo '<td><span class="badge ' . $statusBadge . '">' . $file->status_str . '</span></td>'; // Assume status_str logic
                                    echo '<td>' . $file->prio_str . '</td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card mb-5">
            <div class="card-header bg-success text-white fw-bold">
                <i class="bi bi-upload me-2"></i> UPLOADS
            </div>
            </div>
    </div>

    <nav class="navbar fixed-bottom-custom fixed-bottom">
        <div class="container-fluid justify-content-center">
            <form class="d-flex gap-2" action="amuleweb-main-dload.php" method="post">
                <div class="input-group">
                    <span class="input-group-text">ed2k://</span>
                    <input type="text" name="ed2klink" class="form-control" placeholder="Paste link here" size="50">
                    <select name="selectcat" class="form-select" style="max-width: 120px;">
                        <?php
                           $cats = amule_get_categories();
                           foreach($cats as $c) echo "<option>$c</option>";
                        ?>
                    </select>
                    <button type="submit" name="Submit" class="btn btn-primary">Download</button>
                </div>
            </form>
            <div class="d-flex align-items-center ms-3 text-white small">
                 <span class="badge bg-success me-1">ED2K: Connected</span>
                 <span class="badge bg-warning text-dark">KAD: Firewalled</span>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>
</html>
