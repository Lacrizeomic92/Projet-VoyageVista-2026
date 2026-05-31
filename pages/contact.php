<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars($_POST["nom"]);
    $email = htmlspecialchars($_POST["email"]);
    $sujet = htmlspecialchars($_POST["sujet"]);
    $contenu = htmlspecialchars($_POST["message"]);

    if (!empty($nom) && !empty($email) && !empty($sujet) && !empty($contenu)) {
        $message = "Votre message a bien été envoyé !";
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<section class="contact-page">

    <div class="contact-hero">
        <h1>Contactez-nous</h1>
        <p>Une question sur un voyage, une offre ou votre réservation ? Envoyez-nous un message.</p>
    </div>

    <?php if (!empty($message)) : ?>
        <div class="message-contact">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="contact-container">

        <form method="POST" class="contact-form">
            <label>Nom</label>
            <input type="text" name="nom" placeholder="Votre nom">

            <label>Email</label>
            <input type="email" name="email" placeholder="Votre email">

            <label>Sujet</label>
            <input type="text" name="sujet" placeholder="Sujet du message">

            <label>Message</label>
            <textarea name="message" rows="6" placeholder="Votre message"></textarea>

            <button type="submit">Envoyer</button>
        </form>

        <div class="contact-info">
            <h2>VoyageVista</h2>
            <p>Plateforme de voyages à petits prix pour étudiants.</p>
            <p><strong>Email :</strong> contact@voyagevista.fr</p>
            <p><strong>Téléphone :</strong> 06 12 34 56 78</p>
            <p><strong>Adresse :</strong> ECE Paris, France</p>
        </div>

    </div>

</section>