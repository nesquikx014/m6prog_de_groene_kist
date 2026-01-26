<?php
/**
 * View: Messages Wall
 * Displays all messages using MessageData dataclass objects
 * 
 * @param MessageData[] $messages Array of MessageData objects
 */
?>

<!-- Messages Wall -->
<section class="messages-section">
    <h2>💬 Berichten (<?php echo count($messages); ?>)</h2>
    
    <?php if (empty($messages)): ?>
        <div class="no-messages">
            <p>Nog geen berichten geplaatst. Wees de eerste! 👇</p>
        </div>
    <?php else: ?>
        <div class="messages-grid">
            <?php foreach ($messages as $msg): ?>
                <!-- Message using MessageData dataclass -->
                <article class="message-card" data-message-id="<?php echo $msg->id; ?>">
                    <div class="message-header">
                        <h3 class="author"><?php echo htmlspecialchars($msg->author); ?></h3>
                        <span class="date" title="<?php echo htmlspecialchars($msg->created_at); ?>">
                            <?php 
                            $created_time = strtotime($msg->created_at);
                            echo date('d-m-Y H:i', $created_time); 
                            ?>
                        </span>
                    </div>
                    
                    <div class="message-content">
                        <?php echo nl2br(htmlspecialchars($msg->content)); ?>
                    </div>

                    <div class="message-meta">
                        <?php if (!empty($msg->email)): ?>
                            <span class="email">📧 <?php echo htmlspecialchars($msg->email); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="message-actions">
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Verwijderen?');">
                            <input type="hidden" name="delete_id" value="<?php echo $msg->id; ?>">
                            <button type="submit" class="btn btn-danger btn-small">Verwijderen</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
