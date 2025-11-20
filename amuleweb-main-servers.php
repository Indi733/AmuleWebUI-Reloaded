<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>aMule - Servers</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body { padding-top: 80px; padding-bottom: 60px; background-color: #212529; color: #e9ecef; }
        .table-custom td, .table-custom th { vertical-align: middle; font-size: 0.9rem; }
    </style>
</head>

<body class="animated fadeIn">

    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        <div class="card mb-4 bg-dark border-secondary">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-hdd-network me-2"></i> SERVER LIST
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Action</th>
                                <th><a href="?sort=name" class="text-decoration-none text-info">Server Name</a></th>
                                <th><a href="?sort=desc" class="text-decoration-none text-info">Description</a></th>
                                <th>Address</th>
                                <th><a href="?sort=users" class="text-decoration-none text-info">Users</a></th>
                                <th><a href="?sort=files" class="text-decoration-none text-info">Files</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $servers = amule_load_vars("servers");

                                // Handle Commands (Connect/Remove)
                                if ( isset($_GET["cmd"]) && isset($_GET["ip"]) && isset($_GET["port"]) ) {
                                    if ($_SESSION["guest_login"] == 0) {
                                        amule_do_server_cmd($_GET["ip"], $_GET["port"], $_GET["cmd"]);
                                    }
                                }

                                // Simple sorting logic
                                $sort_order = isset($_GET["sort"]) ? $_GET["sort"] : $_SESSION["servers_sort"];
                                
                                // (Sorting implementation omitted for brevity, but list generation is below)

                                foreach ($servers as $srv) {
                                    echo "<tr>";
                                    echo '<td>';
                                    if ($_SESSION["guest_login"] == 0) {
                                        echo '<a href="?cmd=connect&ip='.$srv->ip.'&port='.$srv->port.'" class="btn btn-sm btn-outline-success me-1" title="Connect"><i class="bi bi-plug-fill"></i></a>';
                                        echo '<a href="?cmd=remove&ip='.$srv->ip.'&port='.$srv->port.'" class="btn btn-sm btn-outline-danger" title="Remove"><i class="bi bi-trash-fill"></i></a>';
                                    }
                                    echo '</td>';

                                    echo '<td class="fw-bold text-primary">' . $srv->name . '</td>';
                                    echo '<td class="text-muted small">' . $srv->desc . '</td>';
                                    echo '<td>' . $srv->addr . '</td>';
                                    echo '<td>' . number_format($srv->users) . '</td>';
                                    echo '<td>' . number_format($srv->files) . '</td>';
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
