<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>aMule - Stats Tree</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Remove default body margin/padding for seamless iframe fit */
        body { 
            background-color: #212529; 
            color: #e9ecef; 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; 
            font-size: 14px; 
            padding: 15px; 
        }
        .trigger { 
            cursor: pointer; 
            user-select: none; 
            display: inline-flex;
            align-items: center;
        }
        .trigger:hover { color: #4db6ac; }
        .branch { display: block; }
        .tree-item {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
        }
        a { text-decoration: none; color: inherit; }
    </style>

    <script>
        // Toggle visibility of the branch
        function showBranch(branchId) {
            var objBranch = document.getElementById(branchId);
            if(objBranch) {
                objBranch.style.display = (objBranch.style.display === "none") ? "block" : "none";
            }
        }

        // Toggle folder icon open/closed
        function swapFolder(iconId) {
            var objIcon = document.getElementById(iconId);
            if (objIcon) {
                if (objIcon.classList.contains('bi-folder2-open')) {
                    objIcon.classList.remove('bi-folder2-open');
                    objIcon.classList.add('bi-folder');
                } else {
                    objIcon.classList.remove('bi-folder');
                    objIcon.classList.add('bi-folder2-open');
                }
            }
        }
    </script>
</head>
<body>

<?php
    // Auto refresh
    if ( isset($_SESSION["auto_refresh"]) && $_SESSION["auto_refresh"] > 0 ) {
        echo '<meta http-equiv="refresh" content="', $_SESSION["auto_refresh"], '">';
    }

    // Helper to print a single item (Leaf)
    function print_item($it, $ident) {
        $padding = $ident * 20;
        echo '<div class="tree-item" style="padding-left: ' . $padding . 'px;">';
        echo '<i class="bi bi-file-earmark-text text-secondary me-2"></i>';
        echo '<span>', $it, '</span>';
        echo '</div>' . "\n";
    }

    // Recursive function to print folders
    function print_folder($key, &$arr, $ident) {
        $padding = $ident * 20;
        // Create unique IDs for JS to target
        $unique_id = md5($key . $ident . rand()); 
        $branchId = "br_" . $unique_id;
        $iconId = "fl_" . $unique_id;

        echo '<div class="tree-item" style="padding-left: ' . $padding . 'px;">';
        echo '<span class="trigger" onClick="showBranch(\'', $branchId, '\'); swapFolder(\'', $iconId, '\');">';
        echo '<i class="bi bi-folder2-open text-warning me-2" id="', $iconId, '"></i>';
        echo '<strong>', $key, '</strong>';
        echo '</span>';
        echo '</div>' . "\n";

        // The container for children
        echo '<div class="branch" id="', $branchId, '">' . "\n";
        foreach ($arr as $k => $v) {
            if ( count($v) ) {
                print_folder($k, $v, $ident + 1);
            } else {
                print_item($k, $ident + 1);
            }
        }
        echo '</div>' . "\n";
    }

    // Load data from aMule
    $stattree = amule_load_vars("stats_tree");

    // Start building the tree
    foreach ($stattree as $k => $v) {
        if ( count($v) ) {
            print_folder($k, $v, 0);
        } else {
            print_item($k, 0);
        }
    }
?>

</body>
</html>
