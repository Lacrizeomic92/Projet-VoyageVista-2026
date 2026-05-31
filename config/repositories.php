<?php

function fetch_all_rows($sql, array $params = [])
{
    $pdo = db();

    if (!$pdo) {
        return [];
    }

    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    return $statement->fetchAll();
}

function fetch_one_row($sql, array $params = [])
{
    $pdo = db();

    if (!$pdo) {
        return null;
    }

    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    $row = $statement->fetch();

    return $row ?: null;
}

function execute_statement($sql, array $params = [])
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    $statement = $pdo->prepare($sql);

    return $statement->execute($params);
}

function find_user_by_email($email)
{
    return fetch_one_row(
        'SELECT id, firstname, lastname, email, password_hash, role, created_at, updated_at
         FROM users
         WHERE email = ?',
        [trim((string) $email)]
    );
}

function find_user_by_id($userId)
{
    return fetch_one_row(
        'SELECT id, firstname, lastname, email, role, created_at, updated_at
         FROM users
         WHERE id = ?',
        [(int) $userId]
    );
}

function create_user(array $data)
{
    $pdo = db();

    if (!$pdo) {
        return null;
    }

    $statement = $pdo->prepare(
        'INSERT INTO users (firstname, lastname, email, password_hash, role)
         VALUES (:firstname, :lastname, :email, :password_hash, :role)'
    );

    $statement->execute([
        'firstname' => trim((string) ($data['firstname'] ?? '')),
        'lastname' => trim((string) ($data['lastname'] ?? '')),
        'email' => trim((string) ($data['email'] ?? '')),
        'password_hash' => $data['password_hash'],
        'role' => $data['role'] ?? 'voyageur',
    ]);

    return (int) $pdo->lastInsertId();
}

function update_user_profile($userId, array $data)
{
    return execute_statement(
        'UPDATE users
         SET firstname = :firstname,
             lastname = :lastname,
             email = :email,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :id',
        [
            'firstname' => trim((string) ($data['firstname'] ?? '')),
            'lastname' => trim((string) ($data['lastname'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'id' => (int) $userId,
        ]
    );
}

function update_user_password($userId, $passwordHash)
{
    return execute_statement(
        'UPDATE users
         SET password_hash = :password_hash,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :id',
        [
            'password_hash' => $passwordHash,
            'id' => (int) $userId,
        ]
    );
}

function list_users()
{
    return fetch_all_rows(
        'SELECT id, firstname, lastname, email, role, created_at
         FROM users
         ORDER BY created_at DESC'
    );
}

function update_user_role($targetUserId, $role)
{
    return execute_statement(
        'UPDATE users
         SET role = :role, updated_at = CURRENT_TIMESTAMP
         WHERE id = :id',
        [
            'role' => $role,
            'id' => (int) $targetUserId,
        ]
    );
}

function get_dashboard_stats()
{
    return [
        'users' => (int) (fetch_one_row('SELECT COUNT(*) AS total FROM users')['total'] ?? 0),
        'reservations' => (int) (fetch_one_row('SELECT COUNT(*) AS total FROM reservations')['total'] ?? 0),
        'destinations' => (int) (fetch_one_row('SELECT COUNT(*) AS total FROM destinations')['total'] ?? 0),
    ];
}

function get_recent_reservations($limit = 5)
{
    $limit = max(1, (int) $limit);

    return fetch_all_rows(
        "SELECT reservations.id,
                reservations.reference,
                reservations.stay_label,
                reservations.total_amount,
                reservations.status,
                reservations.created_at,
                users.firstname,
                users.lastname,
                users.email
         FROM reservations
         INNER JOIN users ON users.id = reservations.user_id
         ORDER BY reservations.created_at DESC
         LIMIT {$limit}"
    );
}

function get_destinations(array $filters = [])
{
    $sql = 'SELECT *
            FROM destinations
            WHERE 1 = 1';
    $params = [];

    if (!empty($filters['search'])) {
        $search = '%' . trim($filters['search']) . '%';
        $sql .= ' AND (
            name LIKE :search_name
            OR country LIKE :search_country
            OR description LIKE :search_description
            OR category LIKE :search_category
        )';
        $params['search_name'] = $search;
        $params['search_country'] = $search;
        $params['search_description'] = $search;
        $params['search_category'] = $search;
    }

    if (!empty($filters['category']) && $filters['category'] !== 'all') {
        $sql .= ' AND category LIKE :category';
        $params['category'] = '%' . trim($filters['category']) . '%';
    }

    if (!empty($filters['duration']) && $filters['duration'] !== 'all') {
        $sql .= ' AND duration_type = :duration';
        $params['duration'] = $filters['duration'];
    }

    if (!empty($filters['budget']) && $filters['budget'] !== 'all') {
        $sql .= ' AND budget_level = :budget';
        $params['budget'] = $filters['budget'];
    }

    if (!empty($filters['audience']) && $filters['audience'] !== 'all') {
        $sql .= ' AND audience LIKE :audience';
        $params['audience'] = '%' . trim($filters['audience']) . '%';
    }

    $sort = $filters['sort'] ?? 'score';
    $orderBy = [
        'prix' => 'base_price ASC',
        'nom' => 'name ASC',
        'budget_jour' => 'daily_budget ASC',
        'score' => 'student_score DESC',
    ];

    $sql .= ' ORDER BY ' . ($orderBy[$sort] ?? 'student_score DESC');

    return fetch_all_rows($sql, $params);
}

function get_destination_by_id($destinationId)
{
    return fetch_one_row(
        'SELECT * FROM destinations WHERE id = ?',
        [(int) $destinationId]
    );
}

function find_destination_by_query($query)
{
    $query = trim((string) $query);

    if ($query === '') {
        return fetch_one_row('SELECT * FROM destinations ORDER BY student_score DESC LIMIT 1');
    }

    $search = '%' . $query . '%';

    return fetch_one_row(
        'SELECT *
         FROM destinations
         WHERE name LIKE :name
            OR country LIKE :country
            OR category LIKE :category
         ORDER BY student_score DESC
         LIMIT 1',
        [
            'name' => $search,
            'country' => $search,
            'category' => $search,
        ]
    );
}

function create_destination(array $data)
{
    $pdo = db();

    if (!$pdo) {
        return null;
    }

    $statement = $pdo->prepare(
        'INSERT INTO destinations (
            name,
            country,
            category,
            duration_type,
            budget_level,
            audience,
            student_tag,
            description,
            base_price,
            daily_budget,
            student_score,
            image_url
        ) VALUES (
            :name,
            :country,
            :category,
            :duration_type,
            :budget_level,
            :audience,
            :student_tag,
            :description,
            :base_price,
            :daily_budget,
            :student_score,
            :image_url
        )'
    );

    $statement->execute([
        'name' => trim((string) ($data['name'] ?? '')),
        'country' => trim((string) ($data['country'] ?? '')),
        'category' => trim((string) ($data['category'] ?? '')),
        'duration_type' => trim((string) ($data['duration_type'] ?? 'court')),
        'budget_level' => trim((string) ($data['budget_level'] ?? 'economique')),
        'audience' => trim((string) ($data['audience'] ?? 'etudiant')),
        'student_tag' => trim((string) ($data['student_tag'] ?? 'Nouveau')),
        'description' => trim((string) ($data['description'] ?? '')),
        'base_price' => (float) ($data['base_price'] ?? 0),
        'daily_budget' => (float) ($data['daily_budget'] ?? 0),
        'student_score' => (float) ($data['student_score'] ?? 0),
        'image_url' => trim((string) ($data['image_url'] ?? '')),
    ]);

    return (int) $pdo->lastInsertId();
}

function delete_destination($destinationId)
{
    return execute_statement(
        'DELETE FROM destinations WHERE id = ?',
        [(int) $destinationId]
    );
}

function get_transports(array $filters = [])
{
    $sql = 'SELECT transports.*,
                   destinations.name AS destination_name,
                   destinations.country AS destination_country
            FROM transports
            INNER JOIN destinations ON destinations.id = transports.destination_id
            WHERE 1 = 1';
    $params = [];

    if (!empty($filters['destination_id'])) {
        $sql .= ' AND transports.destination_id = :destination_id';
        $params['destination_id'] = (int) $filters['destination_id'];
    }

   if (!empty($filters['search'])) {

    $search = '%' . trim($filters['search']) . '%';

    $sql .= ' AND (
        transports.transport_type LIKE :search1
        OR transports.departure_city LIKE :search2
        OR transports.arrival_city LIKE :search3
        OR destinations.name LIKE :search4
        OR transports.details LIKE :search5
    )';

    $params['search1'] = $search;
    $params['search2'] = $search;
    $params['search3'] = $search;
    $params['search4'] = $search;
    $params['search5'] = $search;
}

    if (!empty($filters['transport_type']) && $filters['transport_type'] !== 'all') {
        $sql .= ' AND transports.transport_type = :transport_type';
        $params['transport_type'] = $filters['transport_type'];
    }

    if (!empty($filters['departure_city']) && $filters['departure_city'] !== 'all') {
        $sql .= ' AND transports.departure_city = :departure_city';
        $params['departure_city'] = $filters['departure_city'];
    }

    if (!empty($filters['available_only'])) {
        $sql .= ' AND transports.available_seats > 0';
    }

    $sort = $filters['sort'] ?? 'price';
    $orderBy = [
        'places' => 'transports.available_seats DESC',
        'type' => 'transports.transport_type ASC',
        'price' => '(transports.price + transports.return_price) ASC',
    ];

    $sql .= ' ORDER BY ' . ($orderBy[$sort] ?? '(transports.price + transports.return_price) ASC');

    return fetch_all_rows($sql, $params);
}

function get_transport_by_id($transportId)
{
    return fetch_one_row(
        'SELECT transports.*,
                destinations.name AS destination_name,
                destinations.country AS destination_country
         FROM transports
         INNER JOIN destinations ON destinations.id = transports.destination_id
         WHERE transports.id = ?',
        [(int) $transportId]
    );
}

function get_hebergements(array $filters = [])
{
    $sql = 'SELECT hebergements.*,
                   destinations.name AS destination_name,
                   destinations.country AS destination_country
            FROM hebergements
            INNER JOIN destinations ON destinations.id = hebergements.destination_id
            WHERE 1 = 1';
    $params = [];

    if (!empty($filters['destination_id'])) {
        $sql .= ' AND hebergements.destination_id = :destination_id';
        $params['destination_id'] = (int) $filters['destination_id'];
    }

    if (!empty($filters['search'])) {
        $search = '%' . trim($filters['search']) . '%';
        $sql .= ' AND (
            hebergements.name LIKE :search_name
            OR hebergements.city LIKE :search_city
            OR hebergements.type LIKE :search_type
            OR destinations.name LIKE :search_destination
            OR hebergements.description LIKE :search_description
        )';
        $params['search_name'] = $search;
        $params['search_city'] = $search;
        $params['search_type'] = $search;
        $params['search_destination'] = $search;
        $params['search_description'] = $search;
    }

    if (!empty($filters['type']) && $filters['type'] !== 'all') {
        $sql .= ' AND hebergements.type = :type';
        $params['type'] = $filters['type'];
    }

    if (!empty($filters['available_only'])) {
        $sql .= ' AND hebergements.available_rooms > 0';
    }

    $sort = $filters['sort'] ?? 'price';
    $orderBy = [
        'note' => 'hebergements.rating DESC',
        'places' => 'hebergements.available_rooms DESC',
        'price' => 'hebergements.price_per_night ASC',
    ];

    $sql .= ' ORDER BY ' . ($orderBy[$sort] ?? 'hebergements.price_per_night ASC');

    return fetch_all_rows($sql, $params);
}

function get_hebergement_by_id($hebergementId)
{
    return fetch_one_row(
        'SELECT hebergements.*,
                destinations.name AS destination_name,
                destinations.country AS destination_country
         FROM hebergements
         INNER JOIN destinations ON destinations.id = hebergements.destination_id
         WHERE hebergements.id = ?',
        [(int) $hebergementId]
    );
}

function get_activites(array $filters = [])
{
    $sql = 'SELECT activites.*,
                   destinations.name AS destination_name,
                   destinations.country AS destination_country
            FROM activites
            INNER JOIN destinations ON destinations.id = activites.destination_id
            WHERE 1 = 1';
    $params = [];

    if (!empty($filters['destination_id'])) {
        $sql .= ' AND activites.destination_id = :destination_id';
        $params['destination_id'] = (int) $filters['destination_id'];
    }

    if (!empty($filters['search'])) {
        $search = '%' . trim($filters['search']) . '%';
        $sql .= ' AND (
            activites.name LIKE :search_name
            OR activites.city LIKE :search_city
            OR activites.type LIKE :search_type
            OR activites.description LIKE :search_description
            OR activites.search_tags LIKE :search_tags
        )';
        $params['search_name'] = $search;
        $params['search_city'] = $search;
        $params['search_type'] = $search;
        $params['search_description'] = $search;
        $params['search_tags'] = $search;
    }

    if (!empty($filters['season']) && $filters['season'] !== 'all') {
        $sql .= ' AND activites.season LIKE :season';
        $params['season'] = '%' . $filters['season'] . '%';
    }

    if (!empty($filters['type']) && $filters['type'] !== 'all') {
        $sql .= ' AND activites.type LIKE :type';
        $params['type'] = '%' . $filters['type'] . '%';
    }

    if (!empty($filters['level']) && $filters['level'] !== 'all') {
        $sql .= ' AND activites.level = :level';
        $params['level'] = $filters['level'];
    }

    if (!empty($filters['available_only'])) {
        $sql .= ' AND activites.available_slots > 0';
    }

    $sort = $filters['sort'] ?? 'price';
    $orderBy = [
        'places' => 'activites.available_slots DESC',
        'nom' => 'activites.name ASC',
        'price' => 'activites.price ASC',
    ];

    $sql .= ' ORDER BY ' . ($orderBy[$sort] ?? 'activites.price ASC');

    return fetch_all_rows($sql, $params);
}

function get_activite_by_id($activiteId)
{
    return fetch_one_row(
        'SELECT activites.*,
                destinations.name AS destination_name,
                destinations.country AS destination_country
         FROM activites
         INNER JOIN destinations ON destinations.id = activites.destination_id
         WHERE activites.id = ?',
        [(int) $activiteId]
    );
}

function get_catalog_item($sourceType, $sourceId)
{
    $sourceType = strtolower((string) $sourceType);
    $sourceId = (int) $sourceId;

    if ($sourceId <= 0) {
        return null;
    }

    if ($sourceType === 'transport') {
        $transport = get_transport_by_id($sourceId);

        if (!$transport) {
            return null;
        }

        $linePrice = (float) $transport['price'] + (float) $transport['return_price'];

        return [
            'type' => 'Transport',
            'nom' => $transport['transport_type'],
            'details' => $transport['departure_city'] . ' -> ' . $transport['arrival_city'] . ' / retour inclus',
            'unit_price' => $linePrice,
            'image' => $transport['image_url'],
            'source_type' => 'transport',
            'source_id' => $transport['id'],
        ];
    }

    if ($sourceType === 'hebergement') {
        $hebergement = get_hebergement_by_id($sourceId);

        if (!$hebergement) {
            return null;
        }

        return [
            'type' => 'Hébergement',
            'nom' => $hebergement['name'],
            'details' => $hebergement['city'] . ' - ' . $hebergement['type'],
            'unit_price' => (float) $hebergement['price_per_night'],
            'image' => $hebergement['image_url'],
            'source_type' => 'hebergement',
            'source_id' => $hebergement['id'],
        ];
    }

    if ($sourceType === 'activite') {
        $activite = get_activite_by_id($sourceId);

        if (!$activite) {
            return null;
        }

        return [
            'type' => 'Activité',
            'nom' => $activite['name'],
            'details' => $activite['city'] . ' - ' . $activite['type'],
            'unit_price' => (float) $activite['price'],
            'image' => $activite['image_url'],
            'source_type' => 'activite',
            'source_id' => $activite['id'],
        ];
    }

    return null;
}

function build_cart_item(array $data)
{
    $sourceType = strtolower((string) ($data['source_type'] ?? ''));
    $sourceId = (int) ($data['source_id'] ?? 0);
    $quantity = max(1, (int) ($data['quantity'] ?? 1));

    if (in_array($sourceType, ['transport', 'hebergement', 'activite'], true) && $sourceId > 0) {
        $catalogItem = get_catalog_item($sourceType, $sourceId);

        if (!$catalogItem) {
            return null;
        }

        if (!empty($data['date_details'])) {
            $catalogItem['details'] .= ' • ' . trim((string) $data['date_details']);
        }

        $catalogItem['quantity'] = $quantity;
        $catalogItem['prix'] = $catalogItem['unit_price'];
        $catalogItem['line_total'] = $catalogItem['unit_price'] * $quantity;

        return $catalogItem;
    }

    $unitPrice = max(0, (float) ($data['unit_price'] ?? $data['prix'] ?? 0));

    return [
        'type' => $data['type'] ?? cart_label_from_source_type($sourceType),
        'nom' => trim((string) ($data['nom'] ?? 'Séjour personnalisé')),
        'details' => trim((string) ($data['details'] ?? '')),
        'unit_price' => $unitPrice,
        'prix' => $unitPrice,
        'quantity' => $quantity,
        'line_total' => $unitPrice * $quantity,
        'image' => trim((string) ($data['image'] ?? '')),
        'source_type' => $sourceType ?: 'itineraire',
        'source_id' => $sourceId,
    ];
}

function calculate_cart_totals(array $items)
{
    $total = 0;

    foreach ($items as $item) {
        $quantity = max(1, (int) ($item['quantity'] ?? 1));
        $unitPrice = (float) ($item['unit_price'] ?? $item['prix'] ?? 0);
        $total += $unitPrice * $quantity;
    }

    return $total;
}

function get_user_notifications($userId)
{
    return fetch_all_rows(
        'SELECT *
         FROM notifications
         WHERE user_id = ?
         ORDER BY created_at DESC',
        [(int) $userId]
    );
}

function add_notification($userId, $title, $message)
{
    return execute_statement(
        'INSERT INTO notifications (user_id, title, message, is_read)
         VALUES (?, ?, ?, 0)',
        [(int) $userId, trim((string) $title), trim((string) $message)]
    );
}

function mark_notification_as_read($notificationId, $userId)
{
    return execute_statement(
        'UPDATE notifications
         SET is_read = 1
         WHERE id = ? AND user_id = ?',
        [(int) $notificationId, (int) $userId]
    );
}

function ensure_reservation_travelers_table(PDO $pdo)
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS reservation_travelers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reservation_id INT NOT NULL,
            firstname VARCHAR(120) NOT NULL,
            lastname VARCHAR(120) NOT NULL,
            email VARCHAR(180) NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_reservation_travelers_reservation
                FOREIGN KEY (reservation_id) REFERENCES reservations(id)
                ON DELETE CASCADE
        )'
    );
}

function ensure_reservation_history_table(PDO $pdo)
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS reservation_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reservation_id INT NOT NULL,
            action_label VARCHAR(150) NOT NULL,
            details TEXT NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_reservation_history_reservation
                FOREIGN KEY (reservation_id) REFERENCES reservations(id)
                ON DELETE CASCADE
        )'
    );
}

function add_reservation_history_entry(PDO $pdo, $reservationId, $actionLabel, $details)
{
    $statement = $pdo->prepare(
        'INSERT INTO reservation_history (reservation_id, action_label, details)
         VALUES (:reservation_id, :action_label, :details)'
    );

    $statement->execute([
        'reservation_id' => (int) $reservationId,
        'action_label' => trim((string) $actionLabel),
        'details' => trim((string) $details),
    ]);
}

function get_user_reservations($userId)
{
    $reservations = fetch_all_rows(
        'SELECT *
         FROM reservations
         WHERE user_id = ?
         ORDER BY created_at DESC',
        [(int) $userId]
    );

    if (empty($reservations)) {
        return [];
    }

    $reservationIds = array_map(function ($reservation) {
        return (int) $reservation['id'];
    }, $reservations);

    $placeholders = build_placeholders($reservationIds);
    $items = fetch_all_rows(
        "SELECT *
         FROM reservation_items
         WHERE reservation_id IN ($placeholders)
         ORDER BY id ASC",
        $reservationIds
    );

    $itemsByReservation = [];

    foreach ($items as $item) {
        $itemsByReservation[(int) $item['reservation_id']][] = $item;
    }

    foreach ($reservations as &$reservation) {
        $reservation['items'] = $itemsByReservation[(int) $reservation['id']] ?? [];
    }
    unset($reservation);

    return $reservations;
}

function get_user_reservation($reservationId, $userId)
{
    $pdo = db();

    if (!$pdo) {
        return null;
    }

    ensure_reservation_travelers_table($pdo);
    ensure_reservation_history_table($pdo);

    $reservation = fetch_one_row(
        'SELECT *
         FROM reservations
         WHERE id = ? AND user_id = ?',
        [(int) $reservationId, (int) $userId]
    );

    if (!$reservation) {
        return null;
    }

    $reservation['items'] = fetch_all_rows(
        'SELECT *
         FROM reservation_items
         WHERE reservation_id = ?
         ORDER BY id ASC',
        [(int) $reservationId]
    );

    $reservation['travelers_list'] = fetch_all_rows(
        'SELECT *
         FROM reservation_travelers
         WHERE reservation_id = ?
         ORDER BY created_at ASC, id ASC',
        [(int) $reservationId]
    );

    $reservation['history'] = fetch_all_rows(
        'SELECT *
         FROM reservation_history
         WHERE reservation_id = ?
         ORDER BY created_at DESC, id DESC',
        [(int) $reservationId]
    );

    if (empty($reservation['history'])) {
        $reservation['history'] = [[
            'action_label' => 'Réservation créée',
            'details' => 'Réservation ' . $reservation['reference'] . ' confirmée pour ' . format_price($reservation['total_amount']) . ' €.',
            'created_at' => $reservation['created_at'],
        ]];
    }

    return $reservation;
}

function add_reservation_traveler($reservationId, $userId, array $data)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_travelers_table($pdo);

    $firstname = trim((string) ($data['firstname'] ?? ''));
    $lastname = trim((string) ($data['lastname'] ?? ''));
    $email = trim((string) ($data['email'] ?? ''));

    if ($firstname === '' || $lastname === '') {
        return false;
    }

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        $insertTraveler = $pdo->prepare(
            'INSERT INTO reservation_travelers (reservation_id, firstname, lastname, email)
             VALUES (:reservation_id, :firstname, :lastname, :email)'
        );

        $insertTraveler->execute([
            'reservation_id' => (int) $reservationId,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email !== '' ? $email : null,
        ]);

        $travelerCount = (int) (fetch_one_row(
            'SELECT COUNT(*) AS total
             FROM reservation_travelers
             WHERE reservation_id = ?',
            [(int) $reservationId]
        )['total'] ?? 0);

        $updateReservation = $pdo->prepare(
            'UPDATE reservations
             SET travelers = :travelers,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :reservation_id AND user_id = :user_id'
        );

        $updateReservation->execute([
            'travelers' => max(1, $travelerCount),
            'reservation_id' => (int) $reservationId,
            'user_id' => (int) $userId,
        ]);

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function delete_reservation_traveler($travelerId, $reservationId, $userId)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_travelers_table($pdo);

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        $deleteTraveler = $pdo->prepare(
            'DELETE FROM reservation_travelers
             WHERE id = ? AND reservation_id = ?'
        );

        $deleteTraveler->execute([(int) $travelerId, (int) $reservationId]);

        if ($deleteTraveler->rowCount() !== 1) {
            $pdo->rollBack();
            return false;
        }

        $travelerCount = (int) (fetch_one_row(
            'SELECT COUNT(*) AS total
             FROM reservation_travelers
             WHERE reservation_id = ?',
            [(int) $reservationId]
        )['total'] ?? 0);

        $updateReservation = $pdo->prepare(
            'UPDATE reservations
             SET travelers = :travelers,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :reservation_id AND user_id = :user_id'
        );

        $updateReservation->execute([
            'travelers' => max(1, $travelerCount),
            'reservation_id' => (int) $reservationId,
            'user_id' => (int) $userId,
        ]);

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function cancel_reservation($reservationId, $userId)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_history_table($pdo);

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        restore_reservation_transport_seats($pdo, (int) $reservationId);
        restore_reservation_hebergement_rooms($pdo, (int) $reservationId);
        restore_reservation_activity_slots($pdo, (int) $reservationId);

        $updateReservation = $pdo->prepare(
            'UPDATE reservations
             SET status = "annulée", updated_at = CURRENT_TIMESTAMP
             WHERE id = ? AND user_id = ?'
        );

        $updateReservation->execute([(int) $reservationId, (int) $userId]);

        add_reservation_history_entry(
            $pdo,
            (int) $reservationId,
            'Réservation annulée',
            'La réservation complète ' . $reservation['reference'] . ' a été annulée.'
        );

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function update_transport_seats(PDO $pdo, $transportId, $quantityChange)
{
    $transportId = (int) $transportId;
    $quantityChange = (int) $quantityChange;

    if ($transportId <= 0 || $quantityChange === 0) {
        return true;
    }

    if ($quantityChange < 0) {
        $quantityToReserve = abs($quantityChange);
        $statement = $pdo->prepare(
            'UPDATE transports
             SET available_seats = available_seats - ?
             WHERE id = ? AND available_seats >= ?'
        );

        $statement->execute([$quantityToReserve, $transportId, $quantityToReserve]);

        return $statement->rowCount() === 1;
    }

    $statement = $pdo->prepare(
        'UPDATE transports
         SET available_seats = available_seats + ?
         WHERE id = ?'
    );

    $statement->execute([$quantityChange, $transportId]);

    return $statement->rowCount() === 1;
}

function restore_reservation_transport_seats(PDO $pdo, $reservationId)
{
    $transportItems = fetch_all_rows(
        'SELECT source_id, quantity
         FROM reservation_items
         WHERE reservation_id = ? AND source_type = "transport"',
        [(int) $reservationId]
    );

    foreach ($transportItems as $item) {
        update_transport_seats(
            $pdo,
            (int) ($item['source_id'] ?? 0),
            max(1, (int) ($item['quantity'] ?? 1))
        );
    }
}

function update_hebergement_rooms(PDO $pdo, $hebergementId, $quantityChange)
{
    $hebergementId = (int) $hebergementId;
    $quantityChange = (int) $quantityChange;

    if ($hebergementId <= 0 || $quantityChange === 0) {
        return true;
    }

    if ($quantityChange < 0) {
        $quantityToReserve = abs($quantityChange);
        $statement = $pdo->prepare(
            'UPDATE hebergements
             SET available_rooms = available_rooms - ?
             WHERE id = ? AND available_rooms >= ?'
        );

        $statement->execute([$quantityToReserve, $hebergementId, $quantityToReserve]);

        return $statement->rowCount() === 1;
    }

    $statement = $pdo->prepare(
        'UPDATE hebergements
         SET available_rooms = available_rooms + ?
         WHERE id = ?'
    );

    $statement->execute([$quantityChange, $hebergementId]);

    return $statement->rowCount() === 1;
}

function restore_reservation_hebergement_rooms(PDO $pdo, $reservationId)
{
    $hebergementItems = fetch_all_rows(
        'SELECT source_id, quantity
         FROM reservation_items
         WHERE reservation_id = ? AND source_type = "hebergement"',
        [(int) $reservationId]
    );

    foreach ($hebergementItems as $item) {
        update_hebergement_rooms(
            $pdo,
            (int) ($item['source_id'] ?? 0),
            max(1, (int) ($item['quantity'] ?? 1))
        );
    }
}

function update_activity_slots(PDO $pdo, $activiteId, $quantityChange)
{
    $activiteId = (int) $activiteId;
    $quantityChange = (int) $quantityChange;

    if ($activiteId <= 0 || $quantityChange === 0) {
        return true;
    }

    if ($quantityChange < 0) {
        $quantityToReserve = abs($quantityChange);
        $statement = $pdo->prepare(
            'UPDATE activites
             SET available_slots = available_slots - ?
             WHERE id = ? AND available_slots >= ?'
        );

        $statement->execute([$quantityToReserve, $activiteId, $quantityToReserve]);

        return $statement->rowCount() === 1;
    }

    $statement = $pdo->prepare(
        'UPDATE activites
         SET available_slots = available_slots + ?
         WHERE id = ?'
    );

    $statement->execute([$quantityChange, $activiteId]);

    return $statement->rowCount() === 1;
}

function restore_reservation_activity_slots(PDO $pdo, $reservationId)
{
    $activityItems = fetch_all_rows(
        'SELECT source_id, quantity
         FROM reservation_items
         WHERE reservation_id = ? AND source_type = "activite"',
        [(int) $reservationId]
    );

    foreach ($activityItems as $item) {
        update_activity_slots(
            $pdo,
            (int) ($item['source_id'] ?? 0),
            max(1, (int) ($item['quantity'] ?? 1))
        );
    }
}

function cancel_reservation_activity($reservationId, $reservationItemId, $userId)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_history_table($pdo);

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        $reservationItem = fetch_one_row(
            'SELECT *
             FROM reservation_items
             WHERE id = ? AND reservation_id = ? AND source_type = "activite"
             FOR UPDATE',
            [(int) $reservationItemId, (int) $reservationId]
        );

        if (!$reservationItem) {
            $pdo->rollBack();
            return false;
        }

        $quantity = max(1, (int) ($reservationItem['quantity'] ?? 1));

        update_activity_slots($pdo, (int) $reservationItem['source_id'], $quantity);

        $deleteActivity = $pdo->prepare(
            'DELETE FROM reservation_items
             WHERE id = ? AND reservation_id = ?'
        );
        $deleteActivity->execute([(int) $reservationItemId, (int) $reservationId]);

        $newTotal = recalculate_reservation_total($pdo, (int) $reservationId, (int) $userId);

        $notification = $pdo->prepare(
            'INSERT INTO notifications (user_id, title, message, is_read)
             VALUES (:user_id, :title, :message, 0)'
        );

        $notification->execute([
            'user_id' => (int) $userId,
            'title' => 'Activité annulée',
            'message' => 'L’activité ' . $reservationItem['title'] . ' a été retirée de votre réservation ' . $reservation['reference'] . '. Nouveau total : ' . format_price($newTotal) . ' €.',
        ]);

        add_reservation_history_entry(
            $pdo,
            (int) $reservationId,
            'Activité annulée',
            $reservationItem['title'] . ' a été retirée. Nouveau total : ' . format_price($newTotal) . ' €.'
        );

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function cancel_reservation_hebergement($reservationId, $reservationItemId, $userId)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_history_table($pdo);

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        $reservationItem = fetch_one_row(
            'SELECT *
             FROM reservation_items
             WHERE id = ? AND reservation_id = ? AND source_type = "hebergement"
             FOR UPDATE',
            [(int) $reservationItemId, (int) $reservationId]
        );

        if (!$reservationItem) {
            $pdo->rollBack();
            return false;
        }

        $quantity = max(1, (int) ($reservationItem['quantity'] ?? 1));

        update_hebergement_rooms($pdo, (int) $reservationItem['source_id'], $quantity);

        $deleteHebergement = $pdo->prepare(
            'DELETE FROM reservation_items
             WHERE id = ? AND reservation_id = ?'
        );
        $deleteHebergement->execute([(int) $reservationItemId, (int) $reservationId]);

        $newTotal = recalculate_reservation_total($pdo, (int) $reservationId, (int) $userId);

        $notification = $pdo->prepare(
            'INSERT INTO notifications (user_id, title, message, is_read)
             VALUES (:user_id, :title, :message, 0)'
        );

        $notification->execute([
            'user_id' => (int) $userId,
            'title' => 'Hébergement annulé',
            'message' => 'L’hébergement ' . $reservationItem['title'] . ' a été retiré de votre réservation ' . $reservation['reference'] . '. Nouveau total : ' . format_price($newTotal) . ' €.',
        ]);

        add_reservation_history_entry(
            $pdo,
            (int) $reservationId,
            'Hébergement annulé',
            $reservationItem['title'] . ' a été retiré. Nouveau total : ' . format_price($newTotal) . ' €.'
        );

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function recalculate_reservation_total(PDO $pdo, $reservationId, $userId)
{
    $totalRow = fetch_one_row(
        'SELECT COALESCE(SUM(total_price), 0) AS total
         FROM reservation_items
         WHERE reservation_id = ?',
        [(int) $reservationId]
    );

    $newTotal = (float) ($totalRow['total'] ?? 0);

    $updateReservation = $pdo->prepare(
        'UPDATE reservations
         SET total_amount = :total_amount,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :reservation_id AND user_id = :user_id'
    );

    $updateReservation->execute([
        'total_amount' => $newTotal,
        'reservation_id' => (int) $reservationId,
        'user_id' => (int) $userId,
    ]);

    return $newTotal;
}

function cancel_reservation_transport($reservationId, $reservationItemId, $userId)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_history_table($pdo);

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        $reservationItem = fetch_one_row(
            'SELECT *
             FROM reservation_items
             WHERE id = ? AND reservation_id = ? AND source_type = "transport"
             FOR UPDATE',
            [(int) $reservationItemId, (int) $reservationId]
        );

        if (!$reservationItem) {
            $pdo->rollBack();
            return false;
        }

        $quantity = max(1, (int) ($reservationItem['quantity'] ?? 1));

        update_transport_seats($pdo, (int) $reservationItem['source_id'], $quantity);

        $deleteFee = $pdo->prepare(
            'DELETE FROM reservation_items
             WHERE reservation_id = :reservation_id
               AND source_type = "transport_modification_fee"
               AND source_id = :source_id'
        );
        $deleteFee->execute([
            'reservation_id' => (int) $reservationId,
            'source_id' => (int) $reservationItemId,
        ]);

        $deleteTransport = $pdo->prepare(
            'DELETE FROM reservation_items
             WHERE id = ? AND reservation_id = ?'
        );
        $deleteTransport->execute([(int) $reservationItemId, (int) $reservationId]);

        $newTotal = recalculate_reservation_total($pdo, (int) $reservationId, (int) $userId);

        $notification = $pdo->prepare(
            'INSERT INTO notifications (user_id, title, message, is_read)
             VALUES (:user_id, :title, :message, 0)'
        );

        $notification->execute([
            'user_id' => (int) $userId,
            'title' => 'Transport annulé',
            'message' => 'Le transport ' . $reservationItem['title'] . ' a été retiré de votre réservation ' . $reservation['reference'] . '. Nouveau total : ' . format_price($newTotal) . ' €.',
        ]);

        add_reservation_history_entry(
            $pdo,
            (int) $reservationId,
            'Transport annulé',
            $reservationItem['title'] . ' a été retiré. Nouveau total : ' . format_price($newTotal) . ' €.'
        );

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function update_reservation_activity($reservationId, $reservationItemId, $userId, $activiteId)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_history_table($pdo);

    $activite = get_activite_by_id($activiteId);

    if (!$activite) {
        return false;
    }

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        $reservationItem = fetch_one_row(
            'SELECT *
             FROM reservation_items
             WHERE id = ? AND reservation_id = ?
             FOR UPDATE',
            [(int) $reservationItemId, (int) $reservationId]
        );

        if (!$reservationItem || strtolower((string) $reservationItem['source_type']) !== 'activite') {
            $pdo->rollBack();
            return false;
        }

        $currentActivity = get_activite_by_id((int) ($reservationItem['source_id'] ?? 0));

        if (
            $currentActivity
            && (int) $currentActivity['destination_id'] !== (int) $activite['destination_id']
        ) {
            $pdo->rollBack();
            return false;
        }

        $quantity = max(1, (int) ($reservationItem['quantity'] ?? 1));
        $unitPrice = (float) $activite['price'];
        $currentActivityId = (int) ($reservationItem['source_id'] ?? 0);
        $newActivityId = (int) $activite['id'];

        if ($currentActivityId !== $newActivityId) {
            if ($currentActivityId > 0) {
                update_activity_slots($pdo, $currentActivityId, $quantity);
            }

            if (!update_activity_slots($pdo, $newActivityId, -$quantity)) {
                $pdo->rollBack();
                return false;
            }
        }

        $updateItem = $pdo->prepare(
            'UPDATE reservation_items
             SET source_id = :source_id,
                 title = :title,
                 details = :details,
                 unit_price = :unit_price,
                 total_price = :total_price
             WHERE id = :item_id AND reservation_id = :reservation_id'
        );

        $updateItem->execute([
            'source_id' => (int) $activite['id'],
            'title' => $activite['name'],
            'details' => $activite['city'] . ' - ' . $activite['type'],
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $quantity,
            'item_id' => (int) $reservationItemId,
            'reservation_id' => (int) $reservationId,
        ]);

        recalculate_reservation_total($pdo, (int) $reservationId, (int) $userId);

        $notification = $pdo->prepare(
            'INSERT INTO notifications (user_id, title, message, is_read)
             VALUES (:user_id, :title, :message, 0)'
        );

        $notification->execute([
            'user_id' => (int) $userId,
            'title' => 'Réservation modifiée',
            'message' => 'Une activité de votre réservation ' . $reservation['reference'] . ' a été remplacée par ' . $activite['name'] . '.',
        ]);

        add_reservation_history_entry(
            $pdo,
            (int) $reservationId,
            'Activité modifiée',
            $reservationItem['title'] . ' a été remplacée par ' . $activite['name'] . '.'
        );

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function update_reservation_hebergement($reservationId, $reservationItemId, $userId, $hebergementId)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_history_table($pdo);

    $hebergement = get_hebergement_by_id($hebergementId);

    if (!$hebergement) {
        return false;
    }

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        $reservationItem = fetch_one_row(
            'SELECT *
             FROM reservation_items
             WHERE id = ? AND reservation_id = ?
             FOR UPDATE',
            [(int) $reservationItemId, (int) $reservationId]
        );

        if (!$reservationItem || strtolower((string) $reservationItem['source_type']) !== 'hebergement') {
            $pdo->rollBack();
            return false;
        }

        $currentHebergement = get_hebergement_by_id((int) ($reservationItem['source_id'] ?? 0));

        if (
            $currentHebergement
            && (int) $currentHebergement['destination_id'] !== (int) $hebergement['destination_id']
        ) {
            $pdo->rollBack();
            return false;
        }

        $quantity = max(1, (int) ($reservationItem['quantity'] ?? 1));
        $unitPrice = (float) $hebergement['price_per_night'];
        $currentHebergementId = (int) ($reservationItem['source_id'] ?? 0);
        $newHebergementId = (int) $hebergement['id'];

        if ($currentHebergementId !== $newHebergementId) {
            if ($currentHebergementId > 0) {
                update_hebergement_rooms($pdo, $currentHebergementId, $quantity);
            }

            if (!update_hebergement_rooms($pdo, $newHebergementId, -$quantity)) {
                $pdo->rollBack();
                return false;
            }
        }

        $updateItem = $pdo->prepare(
            'UPDATE reservation_items
             SET source_id = :source_id,
                 title = :title,
                 details = :details,
                 unit_price = :unit_price,
                 total_price = :total_price
             WHERE id = :item_id AND reservation_id = :reservation_id'
        );

        $updateItem->execute([
            'source_id' => (int) $hebergement['id'],
            'title' => $hebergement['name'],
            'details' => $hebergement['city'] . ' - ' . $hebergement['type'],
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $quantity,
            'item_id' => (int) $reservationItemId,
            'reservation_id' => (int) $reservationId,
        ]);

        recalculate_reservation_total($pdo, (int) $reservationId, (int) $userId);

        $notification = $pdo->prepare(
            'INSERT INTO notifications (user_id, title, message, is_read)
             VALUES (:user_id, :title, :message, 0)'
        );

        $notification->execute([
            'user_id' => (int) $userId,
            'title' => 'Réservation modifiée',
            'message' => 'Un hébergement de votre réservation ' . $reservation['reference'] . ' a été remplacé par ' . $hebergement['name'] . '.',
        ]);

        add_reservation_history_entry(
            $pdo,
            (int) $reservationId,
            'Hébergement modifié',
            $reservationItem['title'] . ' a été remplacé par ' . $hebergement['name'] . '.'
        );

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function update_reservation_transport($reservationId, $reservationItemId, $userId, $transportId)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    ensure_reservation_history_table($pdo);

    $transport = get_transport_by_id($transportId);

    if (!$transport) {
        return false;
    }

    try {
        $pdo->beginTransaction();

        $reservation = fetch_one_row(
            'SELECT *
             FROM reservations
             WHERE id = ? AND user_id = ? AND status = "confirmée"
             FOR UPDATE',
            [(int) $reservationId, (int) $userId]
        );

        if (!$reservation) {
            $pdo->rollBack();
            return false;
        }

        $reservationItem = fetch_one_row(
            'SELECT *
             FROM reservation_items
             WHERE id = ? AND reservation_id = ?
             FOR UPDATE',
            [(int) $reservationItemId, (int) $reservationId]
        );

        if (!$reservationItem || strtolower((string) $reservationItem['source_type']) !== 'transport') {
            $pdo->rollBack();
            return false;
        }

        $currentTransport = get_transport_by_id((int) ($reservationItem['source_id'] ?? 0));

        if (
            $currentTransport
            && (int) $currentTransport['destination_id'] !== (int) $transport['destination_id']
        ) {
            $pdo->rollBack();
            return false;
        }

        $quantity = max(1, (int) ($reservationItem['quantity'] ?? 1));
        $unitPrice = (float) $transport['price'] + (float) $transport['return_price'];
        $transportDetails = $transport['departure_city'] . ' -> ' . $transport['arrival_city'] . ' / retour inclus';
        $currentTransportId = (int) ($reservationItem['source_id'] ?? 0);
        $newTransportId = (int) $transport['id'];

        if ($currentTransportId !== $newTransportId) {
            if ($currentTransportId > 0) {
                update_transport_seats($pdo, $currentTransportId, $quantity);
            }

            if (!update_transport_seats($pdo, $newTransportId, -$quantity)) {
                $pdo->rollBack();
                return false;
            }
        }

        $updateItem = $pdo->prepare(
            'UPDATE reservation_items
             SET source_id = :source_id,
                 title = :title,
                 details = :details,
                 unit_price = :unit_price,
                 total_price = :total_price
             WHERE id = :item_id AND reservation_id = :reservation_id'
        );

        $updateItem->execute([
            'source_id' => (int) $transport['id'],
            'title' => $transport['transport_type'],
            'details' => $transportDetails,
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $quantity,
            'item_id' => (int) $reservationItemId,
            'reservation_id' => (int) $reservationId,
        ]);

        $feeAmount = 10.00;
        $feeSourceType = 'transport_modification_fee';
        $isPlane = str_contains(strtolower((string) $transport['transport_type']), 'avion');

        $deleteFee = $pdo->prepare(
            'DELETE FROM reservation_items
             WHERE reservation_id = :reservation_id
               AND source_type = :source_type
               AND source_id = :source_id'
        );

        if (!$isPlane) {
            $deleteFee->execute([
                'reservation_id' => (int) $reservationId,
                'source_type' => $feeSourceType,
                'source_id' => (int) $reservationItemId,
            ]);
        } else {
            $feeItem = fetch_one_row(
                'SELECT *
                 FROM reservation_items
                 WHERE reservation_id = ? AND source_type = ? AND source_id = ?
                 LIMIT 1',
                [(int) $reservationId, $feeSourceType, (int) $reservationItemId]
            );

            if ($feeItem) {
                $updateFee = $pdo->prepare(
                    'UPDATE reservation_items
                     SET title = :title,
                         details = :details,
                         unit_price = :unit_price,
                         quantity = 1,
                         total_price = :total_price
                     WHERE id = :fee_id AND reservation_id = :reservation_id'
                );

                $updateFee->execute([
                    'title' => 'Frais modification avion',
                    'details' => 'Frais de modification avion pour ' . $transport['transport_type'],
                    'unit_price' => $feeAmount,
                    'total_price' => $feeAmount,
                    'fee_id' => (int) $feeItem['id'],
                    'reservation_id' => (int) $reservationId,
                ]);
            } else {
                $insertFee = $pdo->prepare(
                    'INSERT INTO reservation_items (
                        reservation_id,
                        item_type,
                        source_type,
                        source_id,
                        title,
                        details,
                        unit_price,
                        quantity,
                        total_price
                    ) VALUES (
                        :reservation_id,
                        :item_type,
                        :source_type,
                        :source_id,
                        :title,
                        :details,
                        :unit_price,
                        1,
                        :total_price
                    )'
                );

                $insertFee->execute([
                    'reservation_id' => (int) $reservationId,
                    'item_type' => 'Frais',
                    'source_type' => $feeSourceType,
                    'source_id' => (int) $reservationItemId,
                    'title' => 'Frais modification avion',
                    'details' => 'Frais de modification avion pour ' . $transport['transport_type'],
                    'unit_price' => $feeAmount,
                    'total_price' => $feeAmount,
                ]);
            }
        }

        recalculate_reservation_total($pdo, (int) $reservationId, (int) $userId);

        $notification = $pdo->prepare(
            'INSERT INTO notifications (user_id, title, message, is_read)
             VALUES (:user_id, :title, :message, 0)'
        );

        $feeMessage = $isPlane ? ' Des frais de modification avion de 10 € ont été ajoutés.' : '';

        $notification->execute([
            'user_id' => (int) $userId,
            'title' => 'Réservation modifiée',
            'message' => 'Un transport de votre réservation ' . $reservation['reference'] . ' a été remplacé par ' . $transport['transport_type'] . '.' . $feeMessage,
        ]);

        add_reservation_history_entry(
            $pdo,
            (int) $reservationId,
            'Transport modifié',
            $reservationItem['title'] . ' a été remplacé par ' . $transport['transport_type'] . '.' . $feeMessage
        );

        $pdo->commit();

        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function create_reservation_from_cart($userId, array $cartItems, array $paymentData, array $stayContext = [])
{
    $pdo = db();

    if (!$pdo || empty($cartItems)) {
        return null;
    }

    $normalizedItems = [];

    foreach ($cartItems as $item) {
        $normalizedItem = build_cart_item($item);

        if ($normalizedItem) {
            $normalizedItems[] = $normalizedItem;
        }
    }

    if (empty($normalizedItems)) {
        return null;
    }

    $totalAmount = calculate_cart_totals($normalizedItems);
    $travelersData = [];

    foreach (($paymentData['travelers'] ?? []) as $traveler) {
        $firstname = trim((string) ($traveler['firstname'] ?? ''));
        $lastname = trim((string) ($traveler['lastname'] ?? ''));
        $email = trim((string) ($traveler['email'] ?? ''));

        if ($firstname !== '' && $lastname !== '') {
            $travelersData[] = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
            ];
        }
    }

    $travelers = max(1, count($travelersData), (int) ($stayContext['voyageurs'] ?? 1));
    $stayLabel = trim((string) ($stayContext['destination_name'] ?? 'Séjour VoyageVista'));
    $reference = 'VV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    $cardNumber = preg_replace('/\D+/', '', (string) ($paymentData['card_number'] ?? ''));
    $cardLast4 = substr($cardNumber, -4);

    if ($cardLast4 === '') {
        $cardLast4 = '0000';
    }

    ensure_reservation_travelers_table($pdo);
    ensure_reservation_history_table($pdo);

    try {
        $pdo->beginTransaction();

        $reservationStatement = $pdo->prepare(
            'INSERT INTO reservations (
                user_id,
                reference,
                stay_label,
                travelers,
                start_date,
                end_date,
                total_amount,
                payment_name,
                payment_card_last4,
                status
            ) VALUES (
                :user_id,
                :reference,
                :stay_label,
                :travelers,
                :start_date,
                :end_date,
                :total_amount,
                :payment_name,
                :payment_card_last4,
                "confirmée"
            )'
        );

        $reservationStatement->execute([
            'user_id' => (int) $userId,
            'reference' => $reference,
            'stay_label' => $stayLabel,
            'travelers' => $travelers,
            'start_date' => $stayContext['date_depart'] ?: null,
            'end_date' => $stayContext['date_retour'] ?: null,
            'total_amount' => $totalAmount,
            'payment_name' => trim((string) ($paymentData['card_name'] ?? 'Paiement simulé')),
            'payment_card_last4' => $cardLast4,
        ]);

        $reservationId = (int) $pdo->lastInsertId();

        $itemStatement = $pdo->prepare(
            'INSERT INTO reservation_items (
                reservation_id,
                item_type,
                source_type,
                source_id,
                title,
                details,
                unit_price,
                quantity,
                total_price
            ) VALUES (
                :reservation_id,
                :item_type,
                :source_type,
                :source_id,
                :title,
                :details,
                :unit_price,
                :quantity,
                :total_price
            )'
        );

        foreach ($normalizedItems as $item) {
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $unitPrice = (float) ($item['unit_price'] ?? $item['prix'] ?? 0);
            $sourceType = strtolower((string) ($item['source_type'] ?? ''));
            $sourceId = (int) ($item['source_id'] ?? 0);

            if ($sourceType === 'transport' && !update_transport_seats($pdo, $sourceId, -$quantity)) {
                throw new RuntimeException('Transport indisponible');
            }

            if ($sourceType === 'hebergement' && !update_hebergement_rooms($pdo, $sourceId, -$quantity)) {
                throw new RuntimeException('Hébergement indisponible');
            }

            if ($sourceType === 'activite' && !update_activity_slots($pdo, $sourceId, -$quantity)) {
                throw new RuntimeException('Activité indisponible');
            }

            $itemStatement->execute([
                'reservation_id' => $reservationId,
                'item_type' => $item['type'],
                'source_type' => $item['source_type'] ?? '',
                'source_id' => $sourceId ?: null,
                'title' => $item['nom'],
                'details' => $item['details'],
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'total_price' => $unitPrice * $quantity,
            ]);
        }

        if (!empty($travelersData)) {
            $travelerStatement = $pdo->prepare(
                'INSERT INTO reservation_travelers (reservation_id, firstname, lastname, email)
                 VALUES (:reservation_id, :firstname, :lastname, :email)'
            );

            foreach ($travelersData as $traveler) {
                $travelerStatement->execute([
                    'reservation_id' => $reservationId,
                    'firstname' => $traveler['firstname'],
                    'lastname' => $traveler['lastname'],
                    'email' => $traveler['email'] !== '' ? $traveler['email'] : null,
                ]);
            }
        }

        $notificationStatement = $pdo->prepare(
            'INSERT INTO notifications (user_id, title, message, is_read)
             VALUES (:user_id, :title, :message, 0)'
        );

        $notificationStatement->execute([
            'user_id' => (int) $userId,
            'title' => 'Réservation confirmée',
            'message' => 'Votre réservation ' . $reference . ' a bien été enregistrée pour un total de ' . format_price($totalAmount) . ' €.',
        ]);

        add_reservation_history_entry(
            $pdo,
            $reservationId,
            'Réservation créée',
            'Réservation ' . $reference . ' confirmée pour ' . format_price($totalAmount) . ' €.'
        );

        $pdo->commit();

        return [
            'id' => $reservationId,
            'reference' => $reference,
            'total_amount' => $totalAmount,
        ];
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return null;
    }
}

function get_user_favorites($userId)
{
    return fetch_all_rows(
        'SELECT *
         FROM favoris
         WHERE user_id = ?
         ORDER BY created_at DESC',
        [(int) $userId]
    );
}

function save_favorite_for_user($userId, array $favorite)
{
    $pdo = db();

    if (!$pdo) {
        return false;
    }

    $sourceType = strtolower((string) ($favorite['source_type'] ?? ''));
    $sourceId = (int) ($favorite['source_id'] ?? 0);
    $title = trim((string) ($favorite['nom'] ?? ''));
    $itemType = trim((string) ($favorite['type'] ?? cart_label_from_source_type($sourceType)));

    $existing = fetch_one_row(
        'SELECT id
         FROM favoris
         WHERE user_id = :user_id
           AND item_type = :item_type
           AND title = :title
           AND source_type = :source_type
           AND COALESCE(source_id, 0) = :source_id',
        [
            'user_id' => (int) $userId,
            'item_type' => $itemType,
            'title' => $title,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]
    );

    if ($existing) {
        return true;
    }

    $statement = $pdo->prepare(
        'INSERT INTO favoris (
            user_id,
            item_type,
            source_type,
            source_id,
            title,
            details,
            image_url
        ) VALUES (
            :user_id,
            :item_type,
            :source_type,
            :source_id,
            :title,
            :details,
            :image_url
        )'
    );

    return $statement->execute([
        'user_id' => (int) $userId,
        'item_type' => $itemType,
        'source_type' => $sourceType,
        'source_id' => $sourceId ?: null,
        'title' => $title,
        'details' => trim((string) ($favorite['details'] ?? '')),
        'image_url' => trim((string) ($favorite['image'] ?? '')),
    ]);
}

function remove_favorite_for_user($favoriteId, $userId)
{
    return execute_statement(
        'DELETE FROM favoris
         WHERE id = ? AND user_id = ?',
        [(int) $favoriteId, (int) $userId]
    );
}

function build_itinerary_suggestions(array $destination, array $transports, array $hebergements, array $activites, array $stayContext = [])
{
    $nightCount = 3;

    if (!empty($stayContext['date_depart']) && !empty($stayContext['date_retour'])) {
        $diff = strtotime($stayContext['date_retour']) - strtotime($stayContext['date_depart']);

        if ($diff > 0) {
            $nightCount = max(2, min(7, (int) floor($diff / 86400)));
        }
    }

    $transport = $transports[0] ?? null;
    $hebergement = $hebergements[0] ?? null;
    $hebergementAlt = $hebergements[1] ?? $hebergement;
    $activityOne = $activites[0] ?? null;
    $activityTwo = $activites[1] ?? $activityOne;
    $city = $destination['name'];

    $suggestions = [];

    $budgetOne = 0;
    if ($transport) {
        $budgetOne += (float) $transport['price'] + (float) $transport['return_price'];
    }
    if ($hebergement) {
        $budgetOne += (float) $hebergement['price_per_night'] * $nightCount;
    }
    if ($activityOne) {
        $budgetOne += (float) $activityOne['price'];
    }

    $suggestions[] = [
        'name' => 'Séjour express à ' . $city,
        'budget' => $budgetOne,
        'steps' => [
            [
                'days' => 'Jour 1',
                'city' => $city,
                'desc' => $transport
                    ? 'Arrivée via ' . $transport['transport_type'] . ' et installation.'
                    : 'Arrivée et découverte du centre-ville.',
                'move' => null,
            ],
            [
                'days' => 'Jours 2-' . max(2, $nightCount),
                'city' => $hebergement['city'] ?? $city,
                'desc' => $hebergement
                    ? 'Hébergement conseillé : ' . $hebergement['name'] . '.'
                    : 'Sélection d’un hébergement étudiant.',
                'move' => $activityOne
                    ? 'Activité recommandée : ' . $activityOne['name']
                    : 'Ajoutez une activité pour personnaliser le séjour.',
            ],
            [
                'days' => 'Dernier jour',
                'city' => $city,
                'desc' => $activityTwo
                    ? 'Temps libre puis ' . strtolower($activityTwo['name']) . '.'
                    : 'Retour après une dernière balade sur place.',
                'move' => 'Retour vers votre ville de départ',
            ],
        ],
    ];

    $budgetTwo = 0;
    if ($transport) {
        $budgetTwo += (float) $transport['price'] + (float) $transport['return_price'];
    }
    if ($hebergementAlt) {
        $budgetTwo += (float) $hebergementAlt['price_per_night'] * ($nightCount + 1);
    }
    if ($activityOne) {
        $budgetTwo += (float) $activityOne['price'];
    }
    if ($activityTwo) {
        $budgetTwo += (float) $activityTwo['price'];
    }

    $suggestions[] = [
        'name' => 'Séjour découverte à ' . $city,
        'budget' => $budgetTwo,
        'steps' => [
            [
                'days' => 'Jours 1-2',
                'city' => $city,
                'desc' => 'Installation, repérage du quartier et premières visites.',
                'move' => $activityOne ? 'Temps fort : ' . $activityOne['name'] : null,
            ],
            [
                'days' => 'Jours 3-4',
                'city' => $hebergementAlt['city'] ?? $city,
                'desc' => $hebergementAlt
                    ? 'Base recommandée : ' . $hebergementAlt['name'] . '.'
                    : 'Hébergement confortable proche des transports.',
                'move' => $activityTwo ? 'Expérience complémentaire : ' . $activityTwo['name'] : null,
            ],
            [
                'days' => 'Jour final',
                'city' => $city,
                'desc' => 'Derniers achats et retour selon votre transport sélectionné.',
                'move' => 'Retour vers votre point de départ',
            ],
        ],
    ];

    return $suggestions;
}
