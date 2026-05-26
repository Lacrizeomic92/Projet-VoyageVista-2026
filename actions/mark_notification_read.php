<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_login();

$notificationId = (int) ($_GET['id'] ?? 0);

if ($notificationId > 0) {
    mark_notification_as_read($notificationId, (int) current_user()['id']);
}

redirect_to('../index.php?page=profil');
