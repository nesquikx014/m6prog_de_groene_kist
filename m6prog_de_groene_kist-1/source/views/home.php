<main class="container main-home">
    <section class="hero">
        <h2>Welkom bij <?php echo htmlspecialchars($site['name']); ?></h2>
        <p class="lead"><?php echo htmlspecialchars($site['tagline'] ?? 'Verse producten uit de buurt.'); ?></p>
    </section>

    <section class="offers">
        <h3>Aanbiedingen deze week</h3>
        <ul class="offers-list">
            <li><strong>Wortels</strong> — 2 kg voor €3,50</li>
            <li><strong>Appels</strong> — 1 kg voor €2,00</li>
            <li><strong>Seizoenspakket</strong> — gemengd groentepakket voor €6,00</li>
        </ul>
    </section>

    <section class="about">
        <h3>Over ons</h3>
        <p><?php echo htmlspecialchars($site['about']); ?></p>
    </section>
</main>
