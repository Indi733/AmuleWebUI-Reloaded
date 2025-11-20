<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule Log Component</title>
    <meta charset="UTF-8">
    <meta http-equiv="Pragma" content="no-cache">
    
    <?php
        if ( isset($_SESSION["auto_refresh"]) && $_SESSION["auto_refresh"] > 0 ) {
            echo '<meta http-equiv="refresh" content="', $_SESSION["auto_refresh"], '">';
        }
    ?>

    <style>
        body { 
            background-color: #000000; /* Terminal Black */
            color: #e9ecef; 
            margin: 0; 
            padding: 10px;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 13px;
        }
        pre {
            white-space: pre-wrap; 
            word-wrap: break-word;
            margin: 0;
        }
        /* Matrix Green for standard logs */
        .log-text { color: #00ff00; }
        /* Cyan for server logs */
        .srv-text { color: #00d8ff; }
    </style>
</head>
<body>
    <?php
        // Modern PHP $_GET replacement
        $show = isset($_GET['show']) ? $_GET['show'] : '';
        $rstsrv = isset($_GET['rstsrv']) ? $_GET['rstsrv'] : 0;
        $rstlog = isset($_GET['rstlog']) ? $_GET['rstlog'] : 0;

        // Determine content type
        if ($show == "srv" || $rstsrv == 1) {
            $content = amule_get_serverinfo($rstsrv);
            $class = "srv-text";
        } else {
            $content = amule_get_log($rstlog);
            $class = "log-text";
        }
    ?>
    <pre class="<?php echo $class; ?>"><?php echo htmlspecialchars($content); ?></pre>
</body>
</html>
