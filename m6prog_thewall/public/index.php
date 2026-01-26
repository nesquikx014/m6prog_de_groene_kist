<?php

require_once '../source/config.php';
require_once '../source/database.php';
require_once '../source/models/Message.php';

$message = new Message();
$messages = $message->getAll();

$form_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['delete_id'])) {
        $delete_id = (int)$_POST['delete_id'];
        if ($message->delete($delete_id)) {
            $form_message = '✅ Bericht verwijderd!';
            $messages = $message->getAll();
        } else {
            $form_message = '❌ Fout bij verwijderen bericht';
        }
    }
    else {
        $author = $_POST['author'] ?? '';
        $content = $_POST['content'] ?? '';

        if (!empty($author) && !empty($content)) {
            if ($message->create($author, $content)) {
                $form_message = '✅ Bericht geplaatst!';
                $messages = $message->getAll();
            } else {
                $form_message = '❌ Fout bij plaatsen bericht';
            }
        } else {
            $form_message = '❌ Vul alstublieft alle velden in';
        }
    }
}

$db = new Database();
$db_connected = $db->test();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🖼️ The Wall</h1>
            <p>Deel je gedachten met de wereld</p>
            <?php if ($db_connected): ?>
                <div class="status-badge success">✅ Database verbonden</div>
            <?php else: ?>
                <div class="status-badge error">❌ Database probleem</div>
            <?php endif; ?>
        </header>

        <main class="main-content">
            <section class="form-section">
                <h2>📝 Plaats je bericht</h2>
                
                <?php if (!empty($form_message)): ?>
                    <div class="alert <?php echo (strpos($form_message, '✅') !== false) ? 'success' : 'error'; ?>">
                        <?php echo $form_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="message-form">
                    <div class="form-group">
                        <label for="author">Naam:</label>
                        <input 
                            type="text" 
                            id="author" 
                            name="author" 
                            placeholder="Je naam" 
                            maxlength="50"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="content">Bericht:</label>
                        <textarea 
                            id="content" 
                            name="content" 
                            placeholder="Wat wil je zeggen?" 
                            rows="5" 
                            maxlength="500"
                            required
                        ></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Plaatsen</button>
                </form>
            </section>

            <?php include '../source/views/messages_wall.php'; ?>
        </main>

        <footer class="footer">
            <p>&copy; 2026 <?php echo APP_NAME; ?> | Version <?php echo APP_VERSION; ?></p>
        </footer>
    </div>
</body>
</html>
