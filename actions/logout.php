<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

unset($_SESSION['user']);

redirect_with_flash('../index.php', 'success', 'Vous êtes maintenant déconnecté.');
