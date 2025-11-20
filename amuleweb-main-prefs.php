<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule - Preferences</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body { padding-top: 80px; padding-bottom: 60px; background-color: #212529; color: #e9ecef; }
        .form-label { color: #4db6ac; font-weight: bold; margin-top: 10px; }
        .card-header { font-weight: bold; text-transform: uppercase; }
        /* Custom toggle switch look */
        .form-check-input:checked { background-color: #4db6ac; border-color: #4db6ac; }
    </style>

    <script>
        // Initialize values object
        var initvals = new Object;

        <?php
            // --- PHP LOGIC FOR APPLYING SETTINGS ---
            if ( isset($_POST["Submit"]) && $_POST["Submit"] == "Apply" && $_SESSION["guest_login"] == 0 ) {
                
                $file_opts = array("check_free_space", "extract_metadata", "ich_en","aich_trust", 
                                   "preview_prio","save_sources", "resume_same_cat", "min_free_space", 
                                   "new_files_paused", "alloc_full", "alloc_full_chunks",
                                   "new_files_auto_dl_prio", "new_files_auto_ul_prio");
                                   
                $conn_opts = array("max_line_up_cap","max_up_limit", "max_line_down_cap","max_down_limit", 
                                   "slot_alloc", "tcp_port","udp_port","udp_dis","max_file_src",
                                   "max_conn_total","autoconn_en","reconn_en");
                                   
                $webserver_opts = array("use_gzip", "autorefresh_time");

                $all_opts = array();

                foreach ($conn_opts as $i) $all_opts["connection"][$i] = (isset($_POST[$i]) && $_POST[$i] != "") ? ($_POST[$i] == "on" ? 1 : $_POST[$i]) : 0;
                foreach ($file_opts as $i) $all_opts["files"][$i] = (isset($_POST[$i]) && $_POST[$i] != "") ? ($_POST[$i] == "on" ? 1 : $_POST[$i]) : 0;
                foreach ($webserver_opts as $i) $all_opts["webserver"][$i] = (isset($_POST[$i]) && $_POST[$i] != "") ? ($_POST[$i] == "on" ? 1 : $_POST[$i]) : 0;

                amule_set_options($all_opts);
            }

            // --- LOAD CURRENT SETTINGS INTO JS ---
            $opts = amule_get_options();
            $opt_groups = array("connection", "files", "webserver");

            foreach ($opt_groups as $group) {
                foreach ($opts[$group] as $opt_name => $opt_val) {
                    echo 'initvals["', $opt_name, '"] = "', $opt_val, '";' . "\n";
                }
            }
        ?>

        function toggleStatus(checkboxName, targetName) {
            var isChecked = document.getElementsByName(checkboxName)[0].checked;
            var target = document.getElementsByName(targetName)[0];
            target.disabled = !isChecked;
        }

        function init_data() {
            var frm = document.forms.mainform;

            // Text inputs
            var str_params = ["max_line_down_cap", "max_line_up_cap", "max_up_limit", "max_down_limit", 
                              "max_file_src", "slot_alloc", "max_conn_total", "tcp_port", "udp_port", 
                              "min_free_space", "autorefresh_time"];
            
            str_params.forEach(function(name) {
                if(frm[name]) frm[name].value = initvals[name];
            });

            // Checkboxes
            var check_params = ["autoconn_en", "reconn_en", "udp_dis", "new_files_paused", "aich_trust", 
                                "alloc_full", "alloc_full_chunks", "check_free_space", "extract_metadata", 
                                "ich_en", "new_files_auto_dl_prio", "new_files_auto_ul_prio", "use_gzip"];
            
            check_params.forEach(function(name) {
                if(frm[name]) frm[name].checked = (initvals[name] == "1");
            });

            // Initial state for dependent fields
            toggleStatus('check_free_space', 'min_free_space');
        }

        $(document).ready(function() { init_data(); });
    </script>
</head>

<body class="animated fadeIn">

    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        <form name="mainform" action="amuleweb-main-prefs.php" method="post">
            <input type="hidden" name="command">

            <div class="card bg-dark border-secondary mb-4 sticky-top shadow" style="top: 70px; z-index: 1020;">
                <div class="card-body py-2 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white"><i class="bi bi-sliders"></i> Preferences</h4>
                    <?php if ($_SESSION["guest_login"] == 0): ?>
                        <button type="submit" name="Submit" value="Apply" class="btn btn-warning fw-bold px-4">
                            <i class="bi bi-check-circle-fill"></i> Apply Changes
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>Guest Mode (ReadOnly)</button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    
                    <div class="card bg-dark border-secondary mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-speedometer2 me-2"></i> Bandwidth Limits
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Max Download (KB/s)</label>
                                    <input type="number" class="form-control bg-black text-white border-secondary" name="max_down_limit">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Max Upload (KB/s)</label>
                                    <input type="number" class="form-control bg-black text-white border-secondary" name="max_up_limit">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slot Allocation</label>
                                    <input type="number" class="form-control bg-black text-white border-secondary" name="slot_alloc">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-dark border-secondary mb-4">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-hdd-network me-2"></i> Connection Settings
                        </div>
                        <div class="card-body">
                             <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Max Total Connections</label>
                                    <input type="number" class="form-control bg-black text-white border-secondary" name="max_conn_total">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Max Sources / File</label>
                                    <input type="number" class="form-control bg-black text-white border-secondary" name="max_file_src">
                                </div>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="autoconn_en">
                                <label class="form-check-label">Autoconnect at startup</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="reconn_en">
                                <label class="form-check-label">Reconnect on loss</label>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-dark border-secondary">
                        <div class="card-header bg-info text-dark">
                            <i class="bi bi-router me-2"></i> Ports & Network
                        </div>
                        <div class="card-body">
                             <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">TCP Port</label>
                                    <input type="number" class="form-control bg-black text-white border-secondary" name="tcp_port">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">UDP Port</label>
                                    <input type="number" class="form-control bg-black text-white border-secondary" name="udp_port">
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="udp_dis">
                                <label class="form-check-label">Disable UDP connections</label>
                            </div>
                            
                            <div class="mt-3 pt-3 border-top border-secondary">
                                <h6 class="text-muted">Line Capacity (Stats Only)</h6>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm bg-black text-white border-secondary" name="max_line_down_cap" placeholder="Down Cap">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm bg-black text-white border-secondary" name="max_line_up_cap" placeholder="Up Cap">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6">
                    
                    <div class="card bg-dark border-secondary mb-4">
                        <div class="card-header bg-warning text-dark">
                            <i class="bi bi-globe me-2"></i> Webserver
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center g-2">
                                <div class="col-auto">
                                    <label class="col-form-label">Refresh Interval (s):</label>
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control bg-black text-white border-secondary" name="autorefresh_time">
                                </div>
                            </div>
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="use_gzip">
                                <label class="form-check-label">Use GZIP Compression</label>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-dark border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <i class="bi bi-folder2-open me-2"></i> File Settings
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <div class="input-group-text bg-dark border-secondary">
                                    <input class="form-check-input mt-0" type="checkbox" name="check_free_space" onclick="toggleStatus('check_free_space','min_free_space')">
                                </div>
                                <span class="input-group-text bg-secondary border-secondary text-white">Min Free Space (MB)</span>
                                <input type="number" class="form-control bg-black text-white border-secondary" name="min_free_space">
                            </div>

                            <div class="vstack gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="new_files_auto_dl_prio">
                                    <label class="form-check-label">Auto priority for added downloads</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="new_files_auto_ul_prio">
                                    <label class="form-check-label">Auto priority for new shared files</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="ich_en">
                                    <label class="form-check-label">I.C.H. Active</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="aich_trust">
                                    <label class="form-check-label">AICH trusts every hash</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="alloc_full_chunks">
                                    <label class="form-check-label">Alloc full chunks (.part files)</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="alloc_full">
                                    <label class="form-check-label">Alloc full disk space (.part files)</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="new_files_paused">
                                    <label class="form-check-label">Add files paused</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="extract_metadata">
                                    <label class="form-check-label">Extract Metadata</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
