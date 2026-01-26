    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-col">
                <h4>Over ons</h4>
                <p><?php echo htmlspecialchars($site['about'] ?? 'Korte omschrijving.'); ?></p>
            </div>
            <div class="footer-col">
                <h4>Adres</h4>
                <address>
                    <?php echo nl2br(htmlspecialchars($site['address'] ?? '')); ?>
                </address>
                <p><a href="?page=contact">Route & contact</a></p>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <p>Email: <a href="mailto:<?php echo htmlspecialchars($site['contact_email'] ?? 'info@example.com'); ?>"><?php echo htmlspecialchars($site['contact_email'] ?? 'info@example.com'); ?></a></p>
            </div>
        </div>
        <div class="container footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site['name']); ?> — Alle rechten voorbehouden.</p>
        </div>
    </footer>
</body>
</html>
