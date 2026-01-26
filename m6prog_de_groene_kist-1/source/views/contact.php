<main class="container contact-page">
    <section class="contact-intro">
        <h2>Contact & Route</h2>
        <p>Wij zijn gevestigd op: <strong><?php echo htmlspecialchars($site['address'] ?? ''); ?></strong></p>
        <p><?php echo htmlspecialchars($site['location_fake']); ?></p>
        <p><?php echo htmlspecialchars($site['route']); ?></p>
    </section>

    <section class="contact-form">
        <h3>Stuur een bericht</h3>
        <form action="?page=contact" method="post">
            <label for="name">Naam</label>
            <input id="name" name="name" type="text" required>

            <label for="email">Email</label>
            <input id="email" name="email" type="email" required>

            <label for="message">Bericht</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Verstuur</button>
        </form>
        <p class="note">(Dit is een demo formulier — nog niet verbonden met een mailer.)</p>
    </section>
</main>
