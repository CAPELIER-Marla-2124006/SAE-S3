<main>
    <h1>Erreur <?= $A_view["CODE"] ?> </h1>
    <?php
    if (isset($A_view["MSG"]) && !empty($A_view["MSG"])) {
        echo '<p>Message d\'erreur&nbsp;: ' . $A_view["MSG"] .'</p>';
    }
    ?>
</main>
