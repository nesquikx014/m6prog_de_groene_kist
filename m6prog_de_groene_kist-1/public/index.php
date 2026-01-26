
<?php
// Simple router/index file for De Groente Boer
// Views are stored in ../source/views, partials in ../source/partials

// load site data
$siteFile = __DIR__ . '/../source/data/site.php';
if (file_exists($siteFile)) {
    include $siteFile;
} else {
    $site = [
        'name' => 'De Groente Boer',
        'logo' => 'img/logo.svg',
        'about' => 'Korte omschrijving volgt...',
    ];
}

$allowed = ['home', 'products', 'contact'];
$page = $_GET['page'] ?? 'home';
if (!in_array($page, $allowed, true)) {
    $page = 'home';
}

// DB test endpoint: visit /?dbtest=1 to check DB connection and tables
if (isset($_GET['dbtest']) && $_GET['dbtest'] === '1') {
    include_once __DIR__ . '/../source/database.php';
    $conn = database_connect();
    $res = $conn->query('SELECT DATABASE() as db');
    $row = $res->fetch_assoc();
    $tables = [];
    $tres = $conn->query("SHOW TABLES");
    while ($trow = $tres->fetch_row()) { $tables[] = $trow[0]; }
    echo '<main class="container"><h2>DB Test</h2>';
    echo '<p><strong>Connected to DB:</strong> ' . htmlspecialchars($row['db']) . '</p>';
    echo '<p><strong>Tables:</strong> ' . htmlspecialchars(implode(', ', $tables)) . '</p>';
    echo '</main>';
    exit;
}

$partialsPath = __DIR__ . '/../source/partials';
$viewsPath = __DIR__ . '/../source/views';

// include header partial
if (file_exists($partialsPath . '/header.php')) {
    include $partialsPath . '/header.php';
}

// include requested view
$viewFile = $viewsPath . '/' . $page . '.php';
if (file_exists($viewFile)) {
    include $viewFile;
} else {
    echo '<main class="container"><h2>Pagina niet gevonden</h2><p>De gevraagde pagina bestaat niet.</p></main>';
}

// include footer partial
if (file_exists($partialsPath . '/footer.php')) {
    include $partialsPath . '/footer.php';
}

?>
