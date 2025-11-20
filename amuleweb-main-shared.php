<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule - Shared Files</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body { padding-top: 80px; padding-bottom: 60px; background-color: #212529; color: #e9ecef; }
        .table-custom td, .table-custom th { vertical-align: middle; font-size: 0.9rem; }
    </style>

    <script>
    function formCommandSubmit(command) {
        <?php if ($_SESSION["guest_login"] != 0) echo 'alert("Guest mode - commands disabled"); return;'; ?>
        
        var frm = document.forms.mainform;
        frm.command.value = command;
        frm.submit();
    }
    
    function selectAll(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
    </script>
</head>

<body class="animated fadeIn">

    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        <form action="amuleweb-main-shared.php" method="post" name="mainform">
            <input type="hidden" name="command">

            <div class="card bg-dark border-secondary mb-3">
                <div class="card-body py-2 d-flex flex-wrap gap-2 justify-content-center align-items-center">
                    
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="formCommandSubmit('priodown')" title="Lower Priority">
                            <i class="bi bi-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="formCommandSubmit('reload')" title="Reload List">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="formCommandSubmit('prioup')" title="Raise Priority">
                            <i class="bi bi-arrow-up"></i>
                        </button>
                    </div>

                    <div class="input-group w-auto">
                        <span class="input-group-text bg-secondary border-secondary text-white"><i class="bi bi-funnel"></i></span>
                        <select name="select" class="form-select bg-dark text-white border-secondary">
                            <option selected>All</option>
                            <option>Low</option>
                            <option>Normal</option>
                            <option>High</option>
                            <option>Release</option>
                        </select>
                        <button class="btn btn-primary" type="button" onclick="formCommandSubmit('setprio')">Set Prio / Filter</button>
                    </div>

                </div>
            </div>

            <div class="card bg-dark border-secondary">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-share me-2"></i> SHARED FILES
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover table-custom mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 30px;"><input class="form-check-input" type="checkbox" onclick="selectAll(this)"></th>
                                    <th><a href="?sort=name" class="text-decoration-none text-info">Filename</a></th>
                                    <th><a href="?sort=xfer" class="text-decoration-none text-info">Transferred</a></th>
                                    <th><a href="?sort=req" class="text-decoration-none text-info">Requested</a></th>
                                    <th><a href="?sort=acc" class="text-decoration-none text-info">Accepted</a></th>
                                    <th><a href="?sort=size" class="text-decoration-none text-info">Size</a></th>
                                    <th><a href="?sort=prio" class="text-decoration-none text-info">Priority</a></th>
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

                                    // --- Logic ---
                                    if (isset($_POST["command"]) && $_POST["command"] != "" && $_SESSION["guest_login"] == 0) {
                                        if ($_POST["command"] == "reload") {
                                            amule_do_reload_shared_cmd();
                                        } elseif (in_array($_POST["command"], ["priodown", "prioup"])) {
                                            // Handle priority changes
                                            foreach ($_POST as $name => $val) {
                                                if (strlen($name) == 32 && $val == "on") {
                                                    amule_do_shared_cmd($name, $_POST["command"]);
                                                }
                                            }
                                        }
                                    }

                                    $shared = amule_load_vars("shared");
                                    
                                    // Filtering logic
                                    $filter_prio = isset($_POST["select"]) ? $_POST["select"] : "All";

                                    foreach ($shared as $file) {
                                        // Simple check to see if we should show this row
                                        $prioStr = PrioString($file);
                                        // Strip "(auto)" for comparison if needed, or keep simple logic
                                        // This basic check assumes "All" shows everything
                                        if ($filter_prio != "All" && strpos($prioStr, $filter_prio) === false) {
                                            continue; 
                                        }

                                        echo '<tr>';
                                        echo '<td><input class="form-check-input" type="checkbox" name="' . $file->hash . '"></td>';
                                        echo '<td class="text-break">' . htmlspecialchars($file->name) . '</td>';
                                        echo '<td>' . CastToXBytes($file->xfer) . ' <span class="text-muted small">(' . CastToXBytes($file->xfer_all) . ')</span></td>';
                                        echo '<td>' . $file->req . ' <span class="text-muted small">(' . $file->req_all . ')</span></td>';
                                        echo '<td>' . $file->accept . ' <span class="text-muted small">(' . $file->accept_all . ')</span></td>';
                                        echo '<td>' . CastToXBytes($file->size) . '</td>';
                                        echo '<td>' . $prioStr . '</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
