<?php
if (!isset($site)) {
    $site = ['name' => 'De Groente Boer', 'logo' => 'img/logo.svg'];
}
$currentPage = $page ?? 'home';
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($site['name']); ?> - <?php echo htmlspecialchars(ucfirst($currentPage)); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="?page=home">
                <img src="<?php echo htmlspecialchars($site['logo']); ?>" alt="<?php echo htmlspecialchars($site['name']); ?> logo" class="logo">
                <div class="brand-text">
                    <h1 class="site-title"><?php echo htmlspecialchars($site['name']); ?></h1>
                    <p class="site-tagline"><?php echo htmlspecialchars($site['tagline'] ?? 'Verse groenten & fruit'); ?></p>
                </div>
            </a>
            <nav class="main-nav">
                <a class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>" href="?page=home">Home</a>
                <a class="nav-link <?php echo $currentPage === 'products' ? 'active' : ''; ?>" href="?page=products">Producten</a>
                <a class="nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>" href="?page=contact">Contact</a>
            </nav>
        </div>
    </header>
