CREATE DATABASE IF NOT EXISTS voyagevista
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE voyagevista;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reservation_items;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS favoris;
DROP TABLE IF EXISTS activites;
DROP TABLE IF EXISTS hebergements;
DROP TABLE IF EXISTS transports;
DROP TABLE IF EXISTS destinations;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(80) NOT NULL,
    lastname VARCHAR(80) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('voyageur', 'admin') NOT NULL DEFAULT 'voyageur',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    country VARCHAR(120) NOT NULL,
    category VARCHAR(120) NOT NULL,
    duration_type ENUM('court', 'long') NOT NULL DEFAULT 'court',
    budget_level ENUM('economique', 'moyen', 'premium') NOT NULL DEFAULT 'economique',
    audience VARCHAR(120) NOT NULL DEFAULT 'etudiant',
    student_tag VARCHAR(120) NOT NULL DEFAULT 'Bon plan',
    description TEXT NOT NULL,
    base_price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    daily_budget DECIMAL(10, 2) NOT NULL DEFAULT 0,
    student_score DECIMAL(4, 2) NOT NULL DEFAULT 0,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    transport_type VARCHAR(80) NOT NULL,
    departure_city VARCHAR(120) NOT NULL,
    arrival_city VARCHAR(120) NOT NULL,
    duration VARCHAR(60) NOT NULL,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    return_price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    available_seats INT NOT NULL DEFAULT 0,
    details TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transports_destination
        FOREIGN KEY (destination_id) REFERENCES destinations(id)
        ON DELETE CASCADE
);

CREATE TABLE hebergements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    city VARCHAR(120) NOT NULL,
    name VARCHAR(150) NOT NULL,
    type VARCHAR(120) NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL DEFAULT 0,
    rating DECIMAL(3, 1) NOT NULL DEFAULT 0,
    capacity INT NOT NULL DEFAULT 1,
    available_rooms INT NOT NULL DEFAULT 0,
    description TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_hebergements_destination
        FOREIGN KEY (destination_id) REFERENCES destinations(id)
        ON DELETE CASCADE
);

CREATE TABLE activites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    city VARCHAR(120) NOT NULL,
    name VARCHAR(150) NOT NULL,
    type VARCHAR(120) NOT NULL,
    season VARCHAR(120) NOT NULL DEFAULT 'ete',
    level ENUM('debutant', 'intermediaire', 'expert') NOT NULL DEFAULT 'debutant',
    price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    capacity INT NOT NULL DEFAULT 0,
    available_slots INT NOT NULL DEFAULT 0,
    description TEXT NOT NULL,
    search_tags VARCHAR(255) NOT NULL DEFAULT '',
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_activites_destination
        FOREIGN KEY (destination_id) REFERENCES destinations(id)
        ON DELETE CASCADE
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reference VARCHAR(40) NOT NULL UNIQUE,
    stay_label VARCHAR(150) NOT NULL,
    travelers INT NOT NULL DEFAULT 1,
    start_date DATE NULL,
    end_date DATE NULL,
    total_amount DECIMAL(10, 2) NOT NULL DEFAULT 0,
    payment_name VARCHAR(120) NOT NULL,
    payment_card_last4 CHAR(4) NOT NULL DEFAULT '0000',
    status ENUM('confirmée', 'annulée') NOT NULL DEFAULT 'confirmée',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservations_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE reservation_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    item_type VARCHAR(80) NOT NULL,
    source_type VARCHAR(80) NULL,
    source_id INT NULL,
    title VARCHAR(150) NOT NULL,
    details TEXT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    quantity INT NOT NULL DEFAULT 1,
    total_price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservation_items_reservation
        FOREIGN KEY (reservation_id) REFERENCES reservations(id)
        ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notifications_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_type VARCHAR(80) NOT NULL,
    source_type VARCHAR(80) NULL,
    source_id INT NULL,
    title VARCHAR(150) NOT NULL,
    details TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL DEFAULT '',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_favoris_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

INSERT INTO users (firstname, lastname, email, password_hash, role) VALUES
('Admin', 'VoyageVista', 'admin@voyagevista.fr', '$2y$10$TYue3X0hKofaf32JITeYnuF.FlvCzU2Xko6YEaGhVGNWgtjxKy0f6', 'admin'),
('Test', 'Voyageur', 'test@voyagevista.fr', '$2y$10$rqXBl.GSMMy2HwEw81E6a.0xHe.2JFOOLnIAoLUqxmWXu84APdKEm', 'voyageur');

INSERT INTO destinations (
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
) VALUES
('Lisbonne', 'Portugal', 'plage culture ville', 'court', 'economique', 'etudiant jeunesse', 'Petit budget', 'Capitale solaire parfaite pour un city-break étudiant entre surf, tramways et rooftops.', 189.00, 42.00, 9.40, 'https://images.unsplash.com/photo-1555881400-74d7acaacd8b?auto=format&fit=crop&w=1200&q=90'),
('Barcelone', 'Espagne', 'plage ville culture', 'court', 'moyen', 'etudiant jeunesse groupe', 'Entre amis', 'Destination idéale pour combiner plage, soirées étudiantes et visites culturelles.', 229.00, 48.00, 8.90, 'https://images.unsplash.com/photo-1583422409516-2895a77efded?auto=format&fit=crop&w=1200&q=90'),
('Rome', 'Italie', 'ville culture histoire', 'long', 'moyen', 'etudiant famille', 'Culture', 'Ville historique avec transports faciles, bons plans food et séjours très démontrables en démo.', 249.00, 52.00, 8.70, 'https://images.unsplash.com/photo-1525874684015-58379d421a52?auto=format&fit=crop&w=1200&q=90'),
('Marrakech', 'Maroc', 'culture soleil depaysant', 'court', 'economique', 'etudiant jeunesse famille', 'Soleil', 'Souks, rooftops et hébergements accessibles pour un séjour dépaysant à budget maîtrisé.', 220.00, 32.00, 8.80, 'https://images.unsplash.com/photo-1597212618440-806262de4f6b?auto=format&fit=crop&w=1200&q=90'),
('Athènes', 'Grèce', 'ville culture plage', 'long', 'moyen', 'etudiant jeunesse', 'Mediterranee', 'Base simple pour combiner patrimoine, street food et escapades en bord de mer.', 280.00, 45.00, 8.50, 'https://images.unsplash.com/photo-1555993539-1732b0258235?auto=format&fit=crop&w=1200&q=90'),
('Budapest', 'Hongrie', 'ville culture nightlife', 'court', 'economique', 'etudiant jeunesse groupe', 'Ultra budget', 'Très bon compromis prix/ambiance avec thermes, ruelles animées et vie étudiante.', 169.00, 34.00, 9.20, 'https://images.unsplash.com/photo-1541849546-216549ae216d?auto=format&fit=crop&w=1200&q=90');

INSERT INTO transports (
    destination_id,
    transport_type,
    departure_city,
    arrival_city,
    duration,
    price,
    return_price,
    available_seats,
    details,
    image_url
) VALUES
(1, 'Avion', 'Paris', 'Lisbonne', '2h25', 109.00, 119.00, 18, 'Vol direct, bagage cabine inclus et horaires adaptés à un court séjour.', 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90'),
(1, 'Avion low-cost', 'Lyon', 'Lisbonne', '2h15', 95.00, 102.00, 9, 'Option économique très pratique pour un week-end étudiant.', 'https://images.unsplash.com/photo-1556388158-158ea5ccacbd?auto=format&fit=crop&w=900&q=90'),
(2, 'Train', 'Lyon', 'Barcelone', '5h50', 79.00, 82.00, 12, 'Trajet confortable, arrivée en centre-ville sans voiture.', 'https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=900&q=90'),
(2, 'Bus', 'Paris', 'Barcelone', '12h40', 52.00, 55.00, 6, 'Le plus économique pour les petits budgets, de nuit.', 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=900&q=90'),
(3, 'Train', 'Nice', 'Rome', '7h20', 88.00, 90.00, 7, 'Bon compromis confort/prix avec arrivée simple près du centre.', 'https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=900&q=90'),
(3, 'Avion', 'Paris', 'Rome', '2h00', 104.00, 109.00, 16, 'Vol direct rapide pour maximiser le temps sur place.', 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90'),
(4, 'Avion', 'Paris', 'Marrakech', '3h20', 112.00, 118.00, 15, 'Solution la plus simple pour un séjour soleil.', 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90'),
(4, 'Avion low-cost', 'Marseille', 'Marrakech', '2h55', 85.00, 89.00, 0, 'Très bon prix mais quota épuisé pour montrer la gestion des disponibilités.', 'https://images.unsplash.com/photo-1556388158-158ea5ccacbd?auto=format&fit=crop&w=900&q=90'),
(5, 'Avion', 'Paris', 'Athènes', '3h10', 124.00, 129.00, 11, 'Vol direct avec horaires adaptés à une semaine sur place.', 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90'),
(5, 'Avion', 'Marseille', 'Athènes', '2h45', 118.00, 121.00, 4, 'Alternative pratique depuis le sud de la France.', 'https://images.unsplash.com/photo-1556388158-158ea5ccacbd?auto=format&fit=crop&w=900&q=90'),
(6, 'Avion', 'Paris', 'Budapest', '2h15', 89.00, 95.00, 13, 'Destination très rentable pour un séjour étudiant de quelques jours.', 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90'),
(6, 'Train de nuit', 'Paris', 'Budapest', '13h50', 72.00, 72.00, 3, 'Option originale et économique pour une démo transport différente.', 'https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=900&q=90');

INSERT INTO hebergements (
    destination_id,
    city,
    name,
    type,
    price_per_night,
    rating,
    capacity,
    available_rooms,
    description,
    image_url
) VALUES
(1, 'Lisbonne', 'Lisbon Student Hub', 'Auberge', 29.00, 4.5, 4, 6, 'Auberge centrale avec espaces communs, idéale pour un groupe d’étudiants.', 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=900&q=90'),
(1, 'Lisbonne', 'Alfama Budget Rooms', 'Chambre economique', 41.00, 4.3, 2, 3, 'Petit hébergement bien situé pour visiter la ville à pied.', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=90'),
(2, 'Barcelone', 'Barcelona Beach Hostel', 'Auberge', 33.00, 4.4, 6, 4, 'Parfait pour combiner plage et sorties le soir.', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=90'),
(2, 'Barcelone', 'Raval City Stay', 'Petit hotel', 58.00, 4.1, 2, 1, 'Solution simple proche des transports et du centre.', 'https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?auto=format&fit=crop&w=900&q=90'),
(3, 'Rome', 'Roma Student Hostel', 'Auberge', 32.00, 4.4, 4, 5, 'Bon point de départ pour visiter Rome sans voiture.', 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=900&q=90'),
(3, 'Rome', 'Trastevere Budget Rooms', 'Chambre economique', 45.00, 4.5, 2, 2, 'Quartier vivant, pratique pour une soirée étudiante.', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=90'),
(4, 'Marrakech', 'Riad Student Medina', 'Riad economique', 24.00, 4.4, 3, 7, 'Riad simple au cœur de la médina, parfait pour un budget étudiant.', 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=90'),
(4, 'Marrakech', 'Rooftop Hostel', 'Auberge', 19.00, 4.2, 6, 0, 'Ambiance jeune et prix réduit, complet pour illustrer le blocage des disponibilités.', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=90'),
(5, 'Athènes', 'Athens Backpackers', 'Auberge etudiante', 31.00, 4.5, 4, 4, 'Auberge centrale proche du métro et des quartiers animés.', 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=900&q=90'),
(5, 'Athènes', 'Urban Rooms Athens', 'Chambre economique', 43.00, 4.3, 2, 2, 'Logement simple, propre et pratique pour une semaine.', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=90'),
(6, 'Budapest', 'Danube Youth Hostel', 'Auberge', 21.00, 4.6, 6, 8, 'Très bon plan pour un séjour entre amis.', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=90'),
(6, 'Budapest', 'Thermal Budget Inn', 'Petit hotel', 36.00, 4.2, 2, 3, 'Hébergement simple près des transports et des bains.', 'https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?auto=format&fit=crop&w=900&q=90');

INSERT INTO activites (
    destination_id,
    city,
    name,
    type,
    season,
    level,
    price,
    capacity,
    available_slots,
    description,
    search_tags,
    image_url
) VALUES
(1, 'Lisbonne', 'Coucher de soleil à l’Alfama', 'Gratuit', 'ete printemps automne', 'debutant', 0.00, 40, 25, 'Balade simple et parfaite pour une fin de journée à petit budget.', 'lisbonne alfama coucher soleil gratuit ville culture', 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?q=80&w=1200&auto=format&fit=crop'),
(1, 'Lisbonne', 'Session surf découverte', 'Surf', 'ete printemps', 'debutant', 18.00, 12, 8, 'Initiation accessible pour étudiants sur la côte lisboète.', 'lisbonne surf plage debutant ocean', 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=900&q=90'),
(1, 'Lisbonne', 'Food tour étudiant', 'Petit budget', 'toutes saisons', 'debutant', 14.00, 20, 11, 'Parcours gourmand avec spécialités locales et budget léger.', 'lisbonne food tour etudiant pas cher culture', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=90'),
(2, 'Barcelone', 'Balade gothique et tapas', 'Culture', 'toutes saisons', 'debutant', 16.00, 25, 9, 'Découverte simple du centre historique puis tapas abordables.', 'barcelone gothique tapas culture ville', 'https://images.unsplash.com/photo-1539037116277-4db20889f2d4?auto=format&fit=crop&w=900&q=90'),
(2, 'Barcelone', 'Après-midi plage Barceloneta', 'Plage', 'ete printemps', 'debutant', 0.00, 50, 22, 'Classique incontournable pour un séjour entre amis.', 'barcelone plage barceloneta gratuit mer', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=90'),
(2, 'Barcelone', 'Soirée rooftop étudiante', 'Vie etudiante', 'ete printemps automne', 'intermediaire', 22.00, 18, 0, 'Sortie populaire mais complète pour démontrer la gestion des capacités.', 'barcelone rooftop vie etudiante soiree groupe', 'https://images.unsplash.com/photo-1519677100203-a0e668c92439?auto=format&fit=crop&w=900&q=90'),
(3, 'Rome', 'Balade Trastevere', 'Gratuit', 'toutes saisons', 'debutant', 0.00, 35, 20, 'Quartier vivant parfait pour manger pas cher et sortir le soir.', 'rome trastevere gratuit culture ville', 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&w=900&q=90'),
(3, 'Rome', 'Colisee et Forum', 'Culture', 'toutes saisons', 'debutant', 18.00, 20, 6, 'Visite culturelle simple à expliquer en démo.', 'rome colisee forum histoire culture', 'https://images.unsplash.com/photo-1555992828-ca4dbe41d294?auto=format&fit=crop&w=900&q=90'),
(3, 'Rome', 'Street food romaine', 'Petit budget', 'toutes saisons', 'debutant', 12.00, 15, 10, 'Découverte locale rapide et démontrable pour compléter un séjour.', 'rome street food etudiant budget', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=900&q=90'),
(4, 'Marrakech', 'Souks de la medina', 'Gratuit', 'toutes saisons', 'debutant', 0.00, 40, 30, 'Balade immersive dans les ruelles et marchés locaux.', 'marrakech souks medina gratuit culture', 'https://images.unsplash.com/photo-1597212618440-806262de4f6b?auto=format&fit=crop&w=900&q=90'),
(4, 'Marrakech', 'Jardin Majorelle', 'Culture', 'toutes saisons', 'debutant', 15.00, 25, 12, 'Visite colorée et facile à intégrer à un séjour étudiant.', 'marrakech jardin majorelle culture', 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=90'),
(4, 'Marrakech', 'Atelier cuisine marocaine', 'Experience', 'toutes saisons', 'intermediaire', 28.00, 10, 4, 'Expérience plus marquante pour enrichir la démo.', 'marrakech cuisine experience groupe', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=90'),
(5, 'Athènes', 'Acropole et Plaka', 'Culture', 'toutes saisons', 'debutant', 20.00, 25, 7, 'Visite emblématique suivie d’une balade dans le quartier historique.', 'athenes acropole plaka culture', 'https://images.unsplash.com/photo-1555993539-1732b0258235?auto=format&fit=crop&w=900&q=90'),
(5, 'Athènes', 'Coucher de soleil au Lycabette', 'Gratuit', 'ete printemps automne', 'debutant', 0.00, 35, 21, 'Point de vue très populaire pour une sortie gratuite.', 'athenes lycabette gratuit coucher soleil', 'https://images.unsplash.com/photo-1603565816030-6b389eeb23cb?auto=format&fit=crop&w=900&q=90'),
(5, 'Athènes', 'Street food grecque', 'Petit budget', 'toutes saisons', 'debutant', 13.00, 20, 9, 'Dégustation simple de spécialités locales à petit prix.', 'athenes street food grecque budget', 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=900&q=90'),
(6, 'Budapest', 'Bains Szechenyi', 'Detente', 'toutes saisons', 'debutant', 19.00, 30, 13, 'Classique de Budapest, facile à intégrer dans un city break.', 'budapest bains szechenyi detente', 'https://images.unsplash.com/photo-1541849546-216549ae216d?auto=format&fit=crop&w=900&q=90'),
(6, 'Budapest', 'Ruin bars à pied', 'Vie etudiante', 'toutes saisons', 'intermediaire', 10.00, 18, 5, 'Soirée très démontrable avec ambiance locale.', 'budapest ruin bars nightlife etudiant', 'https://images.unsplash.com/photo-1519677100203-a0e668c92439?auto=format&fit=crop&w=900&q=90'),
(6, 'Budapest', 'Balade Danube de nuit', 'Gratuit', 'toutes saisons', 'debutant', 0.00, 50, 34, 'Balade simple et gratuite au bord du Danube.', 'budapest danube nuit gratuit ville', 'https://images.unsplash.com/photo-1519677100203-a0e668c92439?auto=format&fit=crop&w=900&q=90');

INSERT INTO reservations (
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
) VALUES
(2, 'VV-20260501-AB12CD', 'Lisbonne Student Trip', 2, '2026-06-12', '2026-06-16', 351.00, 'Test Voyageur', '4242', 'confirmée'),
(2, 'VV-20260410-CD34EF', 'Barcelone entre amis', 3, '2026-05-22', '2026-05-26', 309.00, 'Test Voyageur', '1111', 'annulée'),
(1, 'VV-20260402-GH56IJ', 'Rome admin review', 1, '2026-06-02', '2026-06-05', 277.00, 'Admin VoyageVista', '9999', 'confirmée');

INSERT INTO reservation_items (
    reservation_id,
    item_type,
    source_type,
    source_id,
    title,
    details,
    unit_price,
    quantity,
    total_price
) VALUES
(1, 'Transport', 'transport', 1, 'Avion', 'Paris -> Lisbonne / retour inclus', 228.00, 1, 228.00),
(1, 'Hébergement', 'hebergement', 1, 'Lisbon Student Hub', 'Lisbonne - Auberge', 29.00, 3, 87.00),
(1, 'Activité', 'activite', 2, 'Session surf découverte', 'Lisbonne - Surf', 18.00, 2, 36.00),
(2, 'Transport', 'transport', 3, 'Train', 'Lyon -> Barcelone / retour inclus', 161.00, 1, 161.00),
(2, 'Hébergement', 'hebergement', 3, 'Barcelona Beach Hostel', 'Barcelone - Auberge', 33.00, 4, 132.00),
(2, 'Activité', 'activite', 4, 'Balade gothique et tapas', 'Barcelone - Culture', 16.00, 1, 16.00),
(3, 'Transport', 'transport', 6, 'Avion', 'Paris -> Rome / retour inclus', 213.00, 1, 213.00),
(3, 'Hébergement', 'hebergement', 5, 'Roma Student Hostel', 'Rome - Auberge', 32.00, 2, 64.00);

INSERT INTO notifications (user_id, title, message, is_read) VALUES
(2, 'Réservation confirmée', 'Votre réservation VV-20260501-AB12CD a bien été enregistrée. Bon voyage à Lisbonne !', 0),
(2, 'Réservation annulée', 'Votre réservation VV-20260410-CD34EF a été annulée et archivée dans votre historique.', 1),
(1, 'Nouvelle réservation', 'Une nouvelle réservation test a été créée et apparaît dans le tableau de bord admin.', 0);

INSERT INTO favoris (
    user_id,
    item_type,
    source_type,
    source_id,
    title,
    details,
    image_url
) VALUES
(2, 'Destination', 'destination', 1, 'Lisbonne', 'Destination étudiante petit budget', 'https://images.unsplash.com/photo-1555881400-74d7acaacd8b?auto=format&fit=crop&w=1200&q=90'),
(2, 'Activité', 'activite', 14, 'Coucher de soleil au Lycabette', 'Athènes - Gratuit', 'https://images.unsplash.com/photo-1603565816030-6b389eeb23cb?auto=format&fit=crop&w=900&q=90');
