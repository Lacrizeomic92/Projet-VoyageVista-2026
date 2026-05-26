<?php

function db()
{
    static $pdo = false;
    static $errorMessage = null;

    if ($pdo !== false) {
        return $pdo;
    }

    try {
        $pdo = get_pdo_connection();
    } catch (Throwable $exception) {
        $pdo = null;
        $errorMessage = "Connexion MySQL impossible. Importez 'database/voyagevista.sql' puis vérifiez la configuration dans 'config/database.php'.";
    }

    $GLOBALS['voyagevista_db_error'] = $errorMessage;

    return $pdo;
}

function db_is_available()
{
    return db() instanceof PDO;
}

function db_error_message()
{
    db();

    return $GLOBALS['voyagevista_db_error'] ?? null;
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function format_price($amount)
{
    return number_format((float) $amount, 2, ',', ' ');
}

function format_date_fr($date)
{
    if (empty($date)) {
        return 'Non précisée';
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return 'Date invalide';
    }

    return date('d/m/Y', $timestamp);
}

function set_flash($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash()
{
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function redirect_to($url)
{
    header('Location: ' . $url);
    exit;
}

function redirect_with_flash($url, $type, $message)
{
    set_flash($type, $message);
    redirect_to($url);
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in()
{
    return !empty($_SESSION['user']);
}

function is_admin()
{
    return (current_user()['role'] ?? '') === 'admin';
}

function require_login()
{
    if (!is_logged_in()) {
        redirect_with_flash('index.php?page=login', 'error', 'Connectez-vous pour accéder à cette page.');
    }
}

function require_admin()
{
    if (!is_admin()) {
        redirect_with_flash('index.php', 'error', 'Cette page est réservée aux administrateurs.');
    }
}

function refresh_session_user($userId = null)
{
    if ($userId === null) {
        $userId = current_user()['id'] ?? null;
    }

    if (!$userId) {
        return;
    }

    $user = find_user_by_id((int) $userId);

    if ($user) {
        $_SESSION['user'] = $user;
    }
}

function remember_stay_context(array $context)
{
    $_SESSION['stay_context'] = array_merge([
        'destination_id' => null,
        'destination_name' => '',
        'depart' => '',
        'date_depart' => '',
        'date_retour' => '',
        'voyageurs' => 1,
        'budget' => 500,
        'dates_valid' => true,
    ], $context);
}

function get_stay_context()
{
    return $_SESSION['stay_context'] ?? [
        'destination_id' => null,
        'destination_name' => '',
        'depart' => '',
        'date_depart' => '',
        'date_retour' => '',
        'voyageurs' => 1,
        'budget' => 500,
        'dates_valid' => true,
    ];
}

function are_dates_valid($dateDepart, $dateRetour)
{
    if (empty($dateDepart) || empty($dateRetour)) {
        return true;
    }

    $depart = strtotime($dateDepart);
    $retour = strtotime($dateRetour);

    if ($depart === false || $retour === false) {
        return false;
    }

    return $retour > $depart;
}

function build_placeholders(array $values)
{
    if (empty($values)) {
        return '';
    }

    return implode(', ', array_fill(0, count($values), '?'));
}

function cart_label_from_source_type($sourceType)
{
    $labels = [
        'transport' => 'Transport',
        'hebergement' => 'Hébergement',
        'activite' => 'Activité',
        'itineraire' => 'Itinéraire',
        'destination' => 'Destination',
    ];

    return $labels[$sourceType] ?? 'Élément';
}

function is_favorite_item($type, $sourceId, $title)
{
    $favorites = $_SESSION['favoris'] ?? [];

    foreach ($favorites as $favorite) {
        $favoriteSourceId = isset($favorite['source_id']) ? (int) $favorite['source_id'] : 0;
        $currentSourceId = $sourceId ? (int) $sourceId : 0;

        if (
            ($favorite['type'] ?? '') === $type
            && ($favorite['nom'] ?? '') === $title
            && $favoriteSourceId === $currentSourceId
        ) {
            return true;
        }
    }

    return false;
}

function sync_favorites_after_login($userId)
{
    if (!db_is_available()) {
        return;
    }

    $sessionFavorites = $_SESSION['favoris'] ?? [];

    foreach ($sessionFavorites as $favorite) {
        save_favorite_for_user((int) $userId, $favorite);
    }

    $favorites = get_user_favorites((int) $userId);
    $_SESSION['favoris'] = [];

    foreach ($favorites as $favorite) {
        $_SESSION['favoris'][] = [
            'id' => (string) $favorite['id'],
            'db_id' => (int) $favorite['id'],
            'type' => $favorite['item_type'],
            'nom' => $favorite['title'],
            'details' => $favorite['details'],
            'image' => $favorite['image_url'],
            'source_type' => $favorite['source_type'],
            'source_id' => $favorite['source_id'],
        ];
    }
}
