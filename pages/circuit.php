<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'classes/Panier.php';
Panier::init();

$depart = htmlspecialchars($_GET['depart'] ?? 'Nice');
$destination = htmlspecialchars($_GET['destination'] ?? 'Italie');
$date_depart = htmlspecialchars($_GET['date_depart'] ?? '');
$date_retour = htmlspecialchars($_GET['date_retour'] ?? '');
$voyageurs = (int)($_GET['voyageurs'] ?? 1);
$budget = (int)($_GET['budget'] ?? 500);

if ($voyageurs <= 0) {
    $voyageurs = 1;
}

$_SESSION['voyageurs'] = $voyageurs;

$destinationKey = strtolower(trim($_GET['destination'] ?? 'italie'));
$departKey = strtolower(trim($_GET['depart'] ?? 'nice'));

$transportDatabase = [
    "maroc" => [
        ["type"=>"Avion", "trajet"=>"$depart → Marrakech", "prix"=>135, "duree"=>"3h00", "info"=>"Le plus réaliste pour rejoindre le Maroc.", "image"=>"https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]],
        ["type"=>"Avion low-cost", "trajet"=>"$depart → Fès", "prix"=>89, "duree"=>"3h10", "info"=>"Option économique avec bagage cabine.", "image"=>"https://images.unsplash.com/photo-1556388158-158ea5ccacbd?auto=format&fit=crop&w=900&q=90", "possible_from"=>["paris","marseille"]],
        ["type"=>"Avion + train local", "trajet"=>"$depart → Casablanca → Marrakech", "prix"=>155, "duree"=>"5h30", "info"=>"Pratique pour commencer un circuit multi-villes.", "image"=>"https://images.unsplash.com/photo-1512389142860-9c449e58a543?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]]
    ],

    "espagne" => [
        ["type"=>"Bus", "trajet"=>"$depart → Barcelone", "prix"=>39, "duree"=>"8h00", "info"=>"Très économique pour les étudiants.", "image"=>"https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]],
        ["type"=>"Train", "trajet"=>"$depart → Barcelone", "prix"=>79, "duree"=>"6h30", "info"=>"Plus confortable que le bus.", "image"=>"https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=900&q=90", "possible_from"=>["paris","lyon","marseille"]],
        ["type"=>"Avion", "trajet"=>"$depart → Barcelone / Valence", "prix"=>92, "duree"=>"1h40", "info"=>"Rapide pour un court séjour.", "image"=>"https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]]
    ],

    "portugal" => [
        ["type"=>"Avion", "trajet"=>"$depart → Lisbonne", "prix"=>115, "duree"=>"2h25", "info"=>"Option la plus simple pour rejoindre le Portugal.", "image"=>"https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]],
        ["type"=>"Avion low-cost", "trajet"=>"$depart → Porto", "prix"=>95, "duree"=>"2h15", "info"=>"Bon plan étudiant hors bagage soute.", "image"=>"https://images.unsplash.com/photo-1556388158-158ea5ccacbd?auto=format&fit=crop&w=900&q=90", "possible_from"=>["paris","marseille"]],
        ["type"=>"Bus longue distance", "trajet"=>"$depart → Lisbonne", "prix"=>75, "duree"=>"18h00", "info"=>"Très long mais parfois moins cher.", "image"=>"https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=900&q=90", "possible_from"=>["paris","lyon"]]
    ],

    "albanie" => [
        ["type"=>"Avion", "trajet"=>"$depart → Tirana", "prix"=>145, "duree"=>"2h30", "info"=>"Le plus réaliste pour rejoindre l’Albanie.", "image"=>"https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]],
        ["type"=>"Avion low-cost", "trajet"=>"$depart → Tirana", "prix"=>110, "duree"=>"2h40", "info"=>"Option économique si réservé tôt.", "image"=>"https://images.unsplash.com/photo-1556388158-158ea5ccacbd?auto=format&fit=crop&w=900&q=90", "possible_from"=>["paris","marseille"]],
        ["type"=>"Avion + bus local", "trajet"=>"$depart → Tirana → Sarandë", "prix"=>165, "duree"=>"6h30", "info"=>"Idéal pour rejoindre la côte albanaise.", "image"=>"https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]]
    ],

    "italie" => [
        ["type"=>"Train", "trajet"=>"$depart → Milan → Rome", "prix"=>89, "duree"=>"7h30", "info"=>"Bon compromis confort/prix.", "image"=>"https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille","clermont"]],
        ["type"=>"Bus", "trajet"=>"$depart → Rome", "prix"=>54, "duree"=>"12h40", "info"=>"Option la moins chère.", "image"=>"https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]],
        ["type"=>"Avion", "trajet"=>"$depart → Rome", "prix"=>96, "duree"=>"1h45", "info"=>"Rapide si réservé à l’avance.", "image"=>"https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]]
    ],

    "grèce" => [
        ["type"=>"Avion", "trajet"=>"$depart → Athènes", "prix"=>145, "duree"=>"2h40", "info"=>"Le plus adapté pour rejoindre la Grèce.", "image"=>"https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]],
        ["type"=>"Avion low-cost", "trajet"=>"$depart → Athènes", "prix"=>110, "duree"=>"2h45", "info"=>"Prix réduit hors bagage soute.", "image"=>"https://images.unsplash.com/photo-1556388158-158ea5ccacbd?auto=format&fit=crop&w=900&q=90", "possible_from"=>["paris","marseille"]],
        ["type"=>"Avion + ferry", "trajet"=>"$depart → Athènes → Cyclades", "prix"=>175, "duree"=>"6h00", "info"=>"Idéal pour intégrer une île.", "image"=>"https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille"]]
    ],

    "france" => [
        ["type"=>"Train", "trajet"=>"$depart → grande ville française", "prix"=>45, "duree"=>"3h30", "info"=>"Pratique, rapide et sans voiture.", "image"=>"https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille","clermont"]],
        ["type"=>"Bus", "trajet"=>"$depart → grande ville française", "prix"=>25, "duree"=>"6h00", "info"=>"Le moins cher pour les étudiants.", "image"=>"https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=900&q=90", "possible_from"=>["nice","paris","lyon","marseille","clermont"]]
    ]
];

$selectedDestination = "italie";

$destinationAliases = [
    "maroc" => ["maroc", "marrakech", "fes", "fès", "agadir", "essaouira"],
    "espagne" => ["espagne", "barcelone", "valence", "madrid", "alicante"],
    "portugal" => ["portugal", "lisbonne", "porto", "faro"],
    "albanie" => ["albanie", "tirana", "sarande", "sarandë", "ksamil"],
    "italie" => ["italie", "rome", "naples", "sicile", "milan"],
    "grèce" => ["grèce", "grece", "athènes", "athenes", "paros", "naxos"],
    "france" => ["france", "paris", "lyon", "marseille", "nice", "annecy"]
];

foreach ($destinationAliases as $key => $aliases) {
    foreach ($aliases as $alias) {
        if (str_contains($destinationKey, $alias)) {
            $selectedDestination = $key;
            break 2;
        }
    }
}

$transports = array_values(array_filter($transportDatabase[$selectedDestination], function($transport) use ($departKey) {
    foreach ($transport['possible_from'] as $city) {
        if (str_contains($departKey, $city)) {
            return true;
        }
    }
    return false;
}));

if (empty($transports)) {
    $transports = $transportDatabase[$selectedDestination];
}

foreach ($transports as &$transport) {
    if (!isset($transport['retour'])) {
        $trajetParts = explode("→", $transport['trajet']);
        $arrivalCity = trim(end($trajetParts));
        $transport['retour'] = $arrivalCity . " → " . $depart;
    }

    if (!isset($transport['prix_retour'])) {
        $transport['prix_retour'] = max(10, round($transport['prix'] * 1.08));
    }

    $transport['prix_total'] = $transport['prix'] + $transport['prix_retour'];
}
unset($transport);

$circuitDatabase = [
    "maroc" => [
        ["name"=>"Maroc villes impériales", "budget"=>390, "steps"=>[
            ["days"=>"Jours 1-2","city"=>"Marrakech","desc"=>"Médina, souks et ambiance locale.","move"=>null],
            ["days"=>"Jours 3-4","city"=>"Essaouira","desc"=>"Ville côtière, plage et détente.","move"=>"Bus Marrakech → Essaouira • 3h • environ 8€"],
            ["days"=>"Jours 5-7","city"=>"Fès","desc"=>"Médina historique et culture.","move"=>"Train Marrakech → Fès • 6h30 • environ 20€"]
        ]],
        ["name"=>"Maroc soleil étudiant", "budget"=>360, "steps"=>[
            ["days"=>"Jours 1-3","city"=>"Marrakech","desc"=>"Base parfaite pour un séjour étudiant.","move"=>null],
            ["days"=>"Jours 4-5","city"=>"Agadir","desc"=>"Plage, surf et logements économiques.","move"=>"Bus Marrakech → Agadir • 3h30 • environ 10€"],
            ["days"=>"Jours 6-7","city"=>"Taghazout","desc"=>"Village surf, auberges et ambiance jeune.","move"=>"Bus Agadir → Taghazout • 45 min • environ 3€"]
        ]]
    ],

    "espagne" => [
        ["name"=>"Espagne Méditerranée", "budget"=>410, "steps"=>[
            ["days"=>"Jours 1-3","city"=>"Barcelone","desc"=>"Plage, quartiers étudiants et transports faciles.","move"=>null],
            ["days"=>"Jours 4-5","city"=>"Valence","desc"=>"Ville moins chère et agréable.","move"=>"Train Barcelone → Valence • 3h • environ 25€"],
            ["days"=>"Jours 6-7","city"=>"Alicante","desc"=>"Fin de séjour soleil à petit prix.","move"=>"Train Valence → Alicante • 2h • environ 18€"]
        ]],
        ["name"=>"Espagne culture & fête", "budget"=>450, "steps"=>[
            ["days"=>"Jours 1-2","city"=>"Madrid","desc"=>"Musées, quartiers étudiants et tapas.","move"=>null],
            ["days"=>"Jours 3-5","city"=>"Valence","desc"=>"Plage et centre historique.","move"=>"Train Madrid → Valence • 2h • environ 25€"],
            ["days"=>"Jours 6-7","city"=>"Barcelone","desc"=>"Fin de séjour animée.","move"=>"Train Valence → Barcelone • 3h • environ 25€"]
        ]]
    ],

    "portugal" => [
        ["name"=>"Portugal classique étudiant", "budget"=>430, "steps"=>[
            ["days"=>"Jours 1-3","city"=>"Lisbonne","desc"=>"Quartiers étudiants, belvédères et tramways.","move"=>null],
            ["days"=>"Jours 4-5","city"=>"Porto","desc"=>"Ville abordable et très vivante.","move"=>"Train Lisbonne → Porto • 3h • environ 25€"],
            ["days"=>"Jours 6-7","city"=>"Aveiro","desc"=>"Petite ville colorée et économique.","move"=>"Train Porto → Aveiro • 1h • environ 6€"]
        ]],
        ["name"=>"Portugal soleil", "budget"=>470, "steps"=>[
            ["days"=>"Jours 1-2","city"=>"Lisbonne","desc"=>"Départ urbain et culturel.","move"=>null],
            ["days"=>"Jours 3-5","city"=>"Faro","desc"=>"Algarve, plages et auberges.","move"=>"Train Lisbonne → Faro • 3h30 • environ 24€"],
            ["days"=>"Jours 6-7","city"=>"Lagos","desc"=>"Falaises, plages et ambiance jeune.","move"=>"Train Faro → Lagos • 1h45 • environ 8€"]
        ]]
    ],

    "albanie" => [
        ["name"=>"Albanie Riviera étudiant", "budget"=>390, "steps"=>[
            ["days"=>"Jours 1-2","city"=>"Tirana","desc"=>"Capitale abordable et animée.","move"=>null],
            ["days"=>"Jours 3-5","city"=>"Sarandë","desc"=>"Côte, plages et auberges économiques.","move"=>"Bus Tirana → Sarandë • 5h30 • environ 15€"],
            ["days"=>"Jours 6-7","city"=>"Ksamil","desc"=>"Plages turquoise à petit budget.","move"=>"Bus Sarandë → Ksamil • 30 min • environ 3€"]
        ]],
        ["name"=>"Albanie nature & mer", "budget"=>420, "steps"=>[
            ["days"=>"Jours 1-2","city"=>"Tirana","desc"=>"Arrivée et découverte de la capitale.","move"=>null],
            ["days"=>"Jours 3-4","city"=>"Berat","desc"=>"Ville historique et peu chère.","move"=>"Bus Tirana → Berat • 2h • environ 6€"],
            ["days"=>"Jours 5-7","city"=>"Vlorë","desc"=>"Mer, promenade et budget doux.","move"=>"Bus Berat → Vlorë • 2h30 • environ 8€"]
        ]]
    ],

    "italie" => [
        ["name"=>"Italie classique étudiant", "budget"=>420, "steps"=>[
            ["days"=>"Jours 1-2","city"=>"Rome","desc"=>"Centre historique et quartiers étudiants.","move"=>null],
            ["days"=>"Jours 3-4","city"=>"Naples","desc"=>"Street food et bord de mer.","move"=>"Train Rome → Naples • 1h15 • environ 18€"],
            ["days"=>"Jours 5-7","city"=>"Sicile","desc"=>"Plages et marchés locaux.","move"=>"Ferry Naples → Palerme • nuit • environ 35€"]
        ]],
        ["name"=>"Italie nord sans voiture", "budget"=>390, "steps"=>[
            ["days"=>"Jours 1-2","city"=>"Milan","desc"=>"Ville étudiante et transports faciles.","move"=>null],
            ["days"=>"Jours 3-4","city"=>"Vérone","desc"=>"Ville jolie et plus abordable.","move"=>"Train Milan → Vérone • 1h15 • environ 15€"],
            ["days"=>"Jours 5-7","city"=>"Venise","desc"=>"Canaux et ruelles hors centre.","move"=>"Train Vérone → Venise • 1h10 • environ 12€"]
        ]]
    ],

    "grèce" => [
        ["name"=>"Grèce Athènes + îles", "budget"=>520, "steps"=>[
            ["days"=>"Jours 1-3","city"=>"Athènes","desc"=>"Culture, street food et quartiers étudiants.","move"=>null],
            ["days"=>"Jours 4-5","city"=>"Paros","desc"=>"Île accessible et jeune.","move"=>"Ferry Athènes → Paros • 4h • environ 35€"],
            ["days"=>"Jours 6-7","city"=>"Naxos","desc"=>"Plages et budget plus doux.","move"=>"Ferry Paros → Naxos • 45 min • environ 15€"]
        ]],
        ["name"=>"Grèce petit budget", "budget"=>430, "steps"=>[
            ["days"=>"Jours 1-3","city"=>"Athènes","desc"=>"Base économique avec transports locaux.","move"=>null],
            ["days"=>"Jours 4-5","city"=>"Corinthe","desc"=>"Excursion facile depuis Athènes.","move"=>"Train Athènes → Corinthe • 1h10 • environ 10€"],
            ["days"=>"Jours 6-7","city"=>"Le Pirée","desc"=>"Port, balades et mer.","move"=>"Métro Athènes → Le Pirée • 30 min • environ 2€"]
        ]]
    ],

    "france" => [
        ["name"=>"France sans voiture", "budget"=>320, "steps"=>[
            ["days"=>"Jours 1-2","city"=>"Lyon","desc"=>"Ville étudiante et quartiers animés.","move"=>null],
            ["days"=>"Jours 3-4","city"=>"Annecy","desc"=>"Lac, nature et balades gratuites.","move"=>"Train Lyon → Annecy • 2h • environ 18€"],
            ["days"=>"Jours 5-7","city"=>"Marseille","desc"=>"Mer, calanques et activités petit budget.","move"=>"Train Annecy → Marseille • 4h • environ 35€"]
        ]]
    ]
];

$circuits = $circuitDatabase[$selectedDestination] ?? $circuitDatabase["italie"];

$logementDatabase = [
    "grèce" => [
        ["ville"=>"Athènes", "nom"=>"Athens Backpackers", "type"=>"Auberge étudiante", "prix"=>29, "note"=>4.5, "desc"=>"Auberge centrale, proche métro et parfaite pour rencontrer d’autres jeunes voyageurs.", "image"=>"https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Athènes", "nom"=>"Urban Rooms Athens", "type"=>"Chambre économique", "prix"=>38, "note"=>4.3, "desc"=>"Logement simple et propre, idéal pour rester proche des quartiers animés.", "image"=>"https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Athènes", "nom"=>"Student Stay Monastiraki", "type"=>"Auberge", "prix"=>26, "note"=>4.2, "desc"=>"Très bon choix pour un séjour petit budget au cœur d’Athènes.", "image"=>"https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Paros", "nom"=>"Paros Budget House", "type"=>"Guesthouse", "prix"=>42, "note"=>4.4, "desc"=>"Petit logement proche des plages avec ambiance calme et prix raisonnable.", "image"=>"https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Paros", "nom"=>"Island Youth Hostel", "type"=>"Auberge", "prix"=>35, "note"=>4.1, "desc"=>"Auberge simple et pratique pour profiter de l’île sans exploser le budget.", "image"=>"https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Naxos", "nom"=>"Naxos Chill Rooms", "type"=>"Chambre partagée", "prix"=>33, "note"=>4.3, "desc"=>"Logement économique avec accès facile à la plage et aux commerces.", "image"=>"https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Naxos", "nom"=>"Blue Island Stay", "type"=>"Petit hôtel", "prix"=>45, "note"=>4.6, "desc"=>"Bon compromis entre confort, prix et localisation.", "image"=>"https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Athènes", "nom"=>"Low Cost Athens Studio", "type"=>"Studio", "prix"=>49, "note"=>4.4, "desc"=>"Studio pratique si vous voyagez à deux ou trois.", "image"=>"https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Le Pirée", "nom"=>"Piraeus Simple Stay", "type"=>"Guesthouse", "prix"=>31, "note"=>4.0, "desc"=>"Solution pratique pour rester proche du port et des ferries.", "image"=>"https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Corinthe", "nom"=>"Corinth Budget Inn", "type"=>"Petit hôtel", "prix"=>34, "note"=>4.1, "desc"=>"Hébergement simple et abordable pour une étape courte.", "image"=>"https://images.unsplash.com/photo-1560449752-9822c7a6f94f?auto=format&fit=crop&w=900&q=90"]
    ],

    "italie" => [
        ["ville"=>"Rome", "nom"=>"Roma Student Hostel", "type"=>"Auberge", "prix"=>32, "note"=>4.4, "desc"=>"Auberge centrale, pratique pour visiter Rome à pied.", "image"=>"https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Rome", "nom"=>"Trastevere Budget Rooms", "type"=>"Chambre économique", "prix"=>44, "note"=>4.5, "desc"=>"Quartier vivant, parfait pour les soirées étudiantes.", "image"=>"https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Naples", "nom"=>"Napoli Central Guesthouse", "type"=>"Guesthouse", "prix"=>28, "note"=>4.2, "desc"=>"Proche gare, idéal pour rejoindre facilement Pompéi ou la côte.", "image"=>"https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Naples", "nom"=>"Pizza & Sleep Hostel", "type"=>"Auberge", "prix"=>25, "note"=>4.0, "desc"=>"Ambiance jeune et prix très bas.", "image"=>"https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Sicile", "nom"=>"Palermo Shared Stay", "type"=>"Logement partagé", "prix"=>35, "note"=>4.5, "desc"=>"Bon plan pour finir le séjour au soleil.", "image"=>"https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Milan", "nom"=>"Milan Urban Hostel", "type"=>"Auberge", "prix"=>39, "note"=>4.3, "desc"=>"Logement moderne et proche des transports.", "image"=>"https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Venise", "nom"=>"Venice Budget Stay", "type"=>"Chambre économique", "prix"=>48, "note"=>4.1, "desc"=>"Hébergement hors centre pour payer moins cher.", "image"=>"https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Vérone", "nom"=>"Verona Simple Rooms", "type"=>"Petit hôtel", "prix"=>36, "note"=>4.2, "desc"=>"Étape abordable entre Milan et Venise.", "image"=>"https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Rome", "nom"=>"Colosseum Low Cost", "type"=>"Studio partagé", "prix"=>51, "note"=>4.6, "desc"=>"Plus confortable pour les petits groupes.", "image"=>"https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Naples", "nom"=>"Sea Side Budget Room", "type"=>"Chambre", "prix"=>37, "note"=>4.3, "desc"=>"Proche bord de mer, bon rapport qualité-prix.", "image"=>"https://images.unsplash.com/photo-1560449752-9822c7a6f94f?auto=format&fit=crop&w=900&q=90"]
    ],

    "maroc" => [
        ["ville"=>"Marrakech", "nom"=>"Riad Student Medina", "type"=>"Riad économique", "prix"=>24, "note"=>4.4, "desc"=>"Riad simple au cœur de la médina, parfait pour un budget étudiant.", "image"=>"https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Marrakech", "nom"=>"Marrakech Youth Hostel", "type"=>"Auberge", "prix"=>18, "note"=>4.1, "desc"=>"Très économique et pratique pour rencontrer d’autres voyageurs.", "image"=>"https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Essaouira", "nom"=>"Essaouira Surf Stay", "type"=>"Auberge surf", "prix"=>22, "note"=>4.5, "desc"=>"Parfait pour plage, surf et ambiance jeune.", "image"=>"https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Fès", "nom"=>"Fès Medina Guesthouse", "type"=>"Guesthouse", "prix"=>21, "note"=>4.2, "desc"=>"Logement abordable pour découvrir la médina.", "image"=>"https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Agadir", "nom"=>"Agadir Beach Budget", "type"=>"Petit hôtel", "prix"=>27, "note"=>4.3, "desc"=>"Proche plage, pratique pour un séjour soleil.", "image"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Taghazout", "nom"=>"Taghazout Surf House", "type"=>"Auberge", "prix"=>20, "note"=>4.6, "desc"=>"Idéal pour les jeunes, surf et logements partagés.", "image"=>"https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Casablanca", "nom"=>"Casa Simple Stay", "type"=>"Chambre économique", "prix"=>30, "note"=>4.0, "desc"=>"Pratique pour une arrivée ou une étape.", "image"=>"https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Marrakech", "nom"=>"Rooftop Hostel", "type"=>"Auberge", "prix"=>19, "note"=>4.2, "desc"=>"Terrasse, ambiance jeune et prix réduit.", "image"=>"https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Fès", "nom"=>"Blue Gate Rooms", "type"=>"Guesthouse", "prix"=>23, "note"=>4.3, "desc"=>"Bon emplacement pour visiter à pied.", "image"=>"https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Essaouira", "nom"=>"Ocean Budget House", "type"=>"Chambre partagée", "prix"=>25, "note"=>4.4, "desc"=>"Petit logement avec accès rapide à la plage.", "image"=>"https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=90"]
    ]
];

$logements = $logementDatabase[$selectedDestination] ?? $logementDatabase["italie"];

$activiteDatabase = [
    "grèce" => [
        ["ville"=>"Athènes", "nom"=>"Acropole & Plaka", "type"=>"Culture", "prix"=>20, "desc"=>"Visite incontournable avec balade dans le quartier historique de Plaka.", "image"=>"https://images.unsplash.com/photo-1555993539-1732b0258235?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Athènes", "nom"=>"Coucher de soleil au Lycabette", "type"=>"Gratuit", "prix"=>0, "desc"=>"Un des meilleurs points de vue sur Athènes, parfait pour un budget étudiant.", "image"=>"https://images.unsplash.com/photo-1603565816030-6b389eeb23cb?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Athènes", "nom"=>"Street food grecque", "type"=>"Petit budget", "prix"=>12, "desc"=>"Découverte de gyros, souvlakis et spécialités locales à bas prix.", "image"=>"https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Paros", "nom"=>"Journée plage", "type"=>"Gratuit", "prix"=>0, "desc"=>"Profiter des plages accessibles sans dépenser plus.", "image"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Paros", "nom"=>"Balade dans Naoussa", "type"=>"Détente", "prix"=>0, "desc"=>"Petit port, ruelles blanches et ambiance parfaite en soirée.", "image"=>"https://images.unsplash.com/photo-1504512485720-7d83a16ee930?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Paros", "nom"=>"Sortie bateau courte", "type"=>"Expérience", "prix"=>35, "desc"=>"Activité plus chère mais très appréciée pour découvrir l’île autrement.", "image"=>"https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Naxos", "nom"=>"Temple d’Apollon", "type"=>"Culture", "prix"=>0, "desc"=>"Site emblématique accessible gratuitement, idéal au coucher du soleil.", "image"=>"https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Naxos", "nom"=>"Plage Agios Georgios", "type"=>"Gratuit", "prix"=>0, "desc"=>"Plage facile d’accès, parfaite pour une journée sans frais.", "image"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Naxos", "nom"=>"Soirée port de Naxos", "type"=>"Vie étudiante", "prix"=>10, "desc"=>"Ambiance animée, bars abordables et belle promenade en bord de mer.", "image"=>"https://images.unsplash.com/photo-1519677100203-a0e668c92439?auto=format&fit=crop&w=900&q=90"]
    ],

    "italie" => [
        ["ville"=>"Rome", "nom"=>"Balade Trastevere", "type"=>"Gratuit", "prix"=>0, "desc"=>"Quartier vivant, parfait pour manger pas cher et sortir le soir.", "image"=>"https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Rome", "nom"=>"Colisée extérieur + Forum", "type"=>"Culture", "prix"=>18, "desc"=>"Visite culturelle incontournable avec possibilité de réduire le coût.", "image"=>"https://images.unsplash.com/photo-1555992828-ca4dbe41d294?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Rome", "nom"=>"Fontaine de Trevi", "type"=>"Gratuit", "prix"=>0, "desc"=>"Passage obligatoire, gratuit et parfait pour une balade en soirée.", "image"=>"https://images.unsplash.com/photo-1525874684015-58379d421a52?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Naples", "nom"=>"Street food napolitaine", "type"=>"Petit budget", "prix"=>12, "desc"=>"Pizza, friture locale et spécialités de rue à prix étudiant.", "image"=>"https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Naples", "nom"=>"Pompéi", "type"=>"Culture", "prix"=>18, "desc"=>"Excursion très intéressante depuis Naples pour une journée.", "image"=>"https://images.unsplash.com/photo-1604580864964-0462f5d5b1a8?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Naples", "nom"=>"Bord de mer", "type"=>"Gratuit", "prix"=>0, "desc"=>"Balade gratuite avec vue sur le Vésuve.", "image"=>"https://images.unsplash.com/photo-1605130284535-11dd9eedc58a?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Sicile", "nom"=>"Marché local", "type"=>"Petit budget", "prix"=>5, "desc"=>"Découverte de produits locaux sans exploser le budget.", "image"=>"https://images.unsplash.com/photo-1514516345957-556ca7ef87d6?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Sicile", "nom"=>"Journée plage", "type"=>"Gratuit", "prix"=>0, "desc"=>"Fin de séjour détente avec activité gratuite.", "image"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Sicile", "nom"=>"Centre historique de Palerme", "type"=>"Culture", "prix"=>0, "desc"=>"Balade dans les rues historiques et marchés populaires.", "image"=>"https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?auto=format&fit=crop&w=900&q=90"]
    ],

    "maroc" => [
        ["ville"=>"Marrakech", "nom"=>"Souks de la médina", "type"=>"Gratuit", "prix"=>0, "desc"=>"Balade immersive dans les ruelles, marchés et ambiances locales.", "image"=>"https://images.unsplash.com/photo-1597212618440-806262de4f6b?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Marrakech", "nom"=>"Jardin Majorelle", "type"=>"Culture", "prix"=>15, "desc"=>"Visite colorée et incontournable, à prévoir dans le budget.", "image"=>"https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Marrakech", "nom"=>"Place Jemaa el-Fna", "type"=>"Gratuit", "prix"=>0, "desc"=>"Ambiance unique le soir, parfaite pour découvrir la ville.", "image"=>"https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Essaouira", "nom"=>"Plage & médina", "type"=>"Gratuit", "prix"=>0, "desc"=>"Journée simple entre océan, ruelles blanches et balade au port.", "image"=>"https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Essaouira", "nom"=>"Initiation surf", "type"=>"Expérience", "prix"=>25, "desc"=>"Activité accessible pour les jeunes voyageurs.", "image"=>"https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Fès", "nom"=>"Médina de Fès", "type"=>"Culture", "prix"=>0, "desc"=>"Immersion dans l’une des plus grandes médinas du monde.", "image"=>"https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=900&q=90"],

        ["ville"=>"Agadir", "nom"=>"Plage d’Agadir", "type"=>"Gratuit", "prix"=>0, "desc"=>"Activité idéale pour profiter sans dépenser.", "image"=>"https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Taghazout", "nom"=>"Sunset surf village", "type"=>"Détente", "prix"=>0, "desc"=>"Ambiance jeune, coucher de soleil et promenade au bord de l’eau.", "image"=>"https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=900&q=90"],
        ["ville"=>"Casablanca", "nom"=>"Mosquée Hassan II extérieur", "type"=>"Culture", "prix"=>0, "desc"=>"Découverte extérieure d’un monument majeur sans frais.", "image"=>"https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?auto=format&fit=crop&w=900&q=90"]
    ]
];

$activites = $activiteDatabase[$selectedDestination] ?? $activiteDatabase["italie"];

$totalTransport = 49;
$totalLogement = array_sum(array_column($logements, 'prix')) * 2;
$totalActivites = array_sum(array_column($activites, 'prix'));
$totalNourriture = 25 * 7;
$total = $totalTransport + $totalLogement + $totalActivites + $totalNourriture;
$totalGroupe = $total * $voyageurs;
?>

<section class="circuit-page">

    <div class="circuit-hero">
        <span class="circuit-badge">Circuit étudiant personnalisé</span>

        <h1>
            Ton circuit vers <?php echo $destination; ?>
        </h1>

        <p>
            Départ de <strong><?php echo $depart; ?></strong>
            <?php if ($date_depart && $date_retour) { ?>
                du <strong><?php echo date('d/m/Y', strtotime($date_depart)); ?></strong>
                au <strong><?php echo date('d/m/Y', strtotime($date_retour)); ?></strong>
            <?php } ?>
            • <?php echo $voyageurs; ?> voyageur(s)
            • Budget max : <?php echo $budget; ?>€
        </p>

        <?php
$itemsBudget = Panier::getItems();
$totalBudget = Panier::getTotal();
$totalParPersonne = $totalBudget / max(1, $voyageurs);
?>

<div class="budget-status <?php echo $totalParPersonne <= $budget ? 'ok' : 'warning'; ?>">
    Budget actuel : <?php echo number_format($totalParPersonne, 2); ?>€ / personne
    <?php echo $totalParPersonne <= $budget ? 'Compatible avec votre budget' : 'Budget à ajuster'; ?>
</div>
    </div>

    <div class="circuit-tabs">
        <button class="tab-btn active" data-tab="transport">Transports</button>
        <button class="tab-btn" data-tab="circuit">Circuit</button>
        <button class="tab-btn" data-tab="logement">Logements</button>
        <button class="tab-btn" data-tab="activites">Activités</button>
        <button class="tab-btn" data-tab="budget">Budget</button>
    </div>

    <div class="tab-content active" id="transport">

        <div class="tab-title-row">
            <div>
                <h2>Options de transport</h2>
                <p>
                    Les trajets sont adaptés à votre destination, votre ville de départ
                    et votre objectif de budget étudiant.
                </p>
            </div>
        </div>

        <div class="transport-grid">

            <?php foreach ($transports as $transport) { ?>

                <article class="transport-card">

                    <div
                        class="transport-img"
                        style="background-image:url('<?php echo $transport['image']; ?>')"
                    >
                        <span class="transport-type">
                            <?php echo htmlspecialchars($transport['type']); ?>
                        </span>
                    </div>

                    <div class="transport-content">

                        <h3>Trajet aller-retour</h3>

                        <div class="transport-route">
                            <p><strong>Aller :</strong> <?php echo htmlspecialchars($transport['trajet']); ?></p>
                            <p><strong>Retour :</strong> <?php echo htmlspecialchars($transport['retour']); ?></p>
                        </div>

                        <div class="transport-info">
                            <span>Durée aller : <?php echo htmlspecialchars($transport['duree']); ?></span>
                            <span><?php echo htmlspecialchars($transport['info']); ?></span>
                        </div>

                        <div class="transport-bottom">
                            <div>
                                <small>Aller-retour</small>
                                <strong><?php echo htmlspecialchars($transport['prix_total']); ?>€</strong>
                                <small>Aller : <?php echo htmlspecialchars($transport['prix']); ?>€ • Retour : <?php echo htmlspecialchars($transport['prix_retour']); ?>€</small>
                            </div>

                            <form action="actions/add_to_cart.php" method="POST">
                                <input type="hidden" name="type" value="Transport">
                                <input type="hidden" name="nom" value="<?php echo htmlspecialchars($transport['type']); ?>">
                                <input type="hidden" name="details" value="<?php echo htmlspecialchars($transport['trajet'] . ' / ' . $transport['retour']); ?>">
                                <input type="hidden" name="prix" value="<?php echo htmlspecialchars($transport['prix_total']); ?>">
                                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <button type="submit">Ajouter au budget</button>
                            </form>
                        </div>

                    </div>

                </article>

            <?php } ?>

        </div>

    </div>

    <div class="tab-content" id="circuit">
        <div class="tab-title-row">
            <div>
                <h2>Circuits conseillés</h2>
                <p>
                    Plusieurs itinéraires adaptés à votre destination, avec les trajets entre villes inclus.
                </p>
            </div>
        </div>

        <div class="circuit-options-grid">
            <?php foreach ($circuits as $circuit) { ?>
                <article class="route-card">
                    <div class="route-header">
                        <div>
                            <span>Circuit étudiant</span>
                            <h3><?php echo $circuit["name"]; ?></h3>
                        </div>

                        <strong><?php echo $circuit["budget"]; ?>€ estimés</strong>
                    </div>

                    <div class="route-steps">
                        <?php foreach ($circuit["steps"] as $index => $step) { ?>
                            <?php
                                $steps = $circuit["steps"];
                                $isLastStep = $index === count($steps) - 1;

                                $days = $step["days"] ?? ($step[0] ?? "");
                                $city = $step["city"] ?? ($step[1] ?? "");
                                $desc = $step["desc"] ?? ($step[2] ?? "");

                                if (!$isLastStep) {
                                    $nextMove = $steps[$index + 1]["move"] ?? ($steps[$index + 1][3] ?? "");
                                } else {
                                    $firstCity = $steps[0]["city"] ?? ($steps[0][1] ?? "");
                                    $nextMove = "Retour " . $city . " → " . $firstCity . " • trajet retour du circuit";
                                }
                            ?>

                            <div class="route-step">
                                <div class="route-dot"></div>

                                <div class="route-step-content">
                                    <span><?php echo htmlspecialchars($days); ?></span>
                                    <h4><?php echo htmlspecialchars($city); ?></h4>
                                    <p><?php echo htmlspecialchars($desc); ?></p>

                                    <?php if (!empty($nextMove)) { ?>
                                        <div class="route-move">
                                            <small>Prochain trajet</small>
                                            <strong><?php echo htmlspecialchars($nextMove); ?></strong>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <form action="actions/add_to_cart.php" method="POST">
    <input type="hidden" name="type" value="Circuit">
    <input type="hidden" name="nom" value="<?php echo htmlspecialchars($circuit['name']); ?>">
    <input type="hidden" name="details" value="<?php
        $details = [];

        foreach ($circuit['steps'] as $step) {
            $details[] = $step['days'] . ' : ' . $step['city'];
        }

        echo htmlspecialchars(implode(' | ', $details));
    ?>">
    <input type="hidden" name="prix" value="0">
    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>#circuit">

    <button type="submit" class="route-btn">
        Choisir ce circuit
    </button>
</form>
                </article>
            <?php } ?>
        </div>
    </div>

    <div class="tab-content" id="logement">

        <div class="tab-title-row">
            <div>
                <h2>Logements petit budget</h2>
                <p>
                    Des hébergements adaptés aux étudiants : auberges, chambres économiques,
                    logements partagés et bons plans proches des transports.
                </p>
            </div>
        </div>

        <div class="housing-grid">

            <?php foreach ($logements as $logement) { ?>

                <article class="housing-card">

                    <div
                        class="housing-img"
                        style="background-image:url('<?php echo $logement['image']; ?>')"
                    >
                        <span><?php echo $logement['type']; ?></span>
                    </div>

                    <div class="housing-content">

                        <div class="housing-top">
                            <div>
                                <small><?php echo $logement['ville']; ?></small>
                                <h3><?php echo $logement['nom']; ?></h3>
                            </div>

                            <strong><?php echo $logement['note']; ?>/5</strong>
                        </div>

                        <p>
                            <?php echo $logement['desc']; ?>
                        </p>

                        <div class="housing-bottom">
                            <div>
                                <small>À partir de</small>
                                <strong><?php echo $logement['prix']; ?>€ / nuit</strong>
                            </div>

                            <form action="actions/add_to_cart.php" method="POST">
                                <input type="hidden" name="type" value="Logement">
                                <input type="hidden" name="nom" value="<?php echo htmlspecialchars($logement['nom']); ?>">
                                <input type="hidden" name="details" value="<?php echo htmlspecialchars($logement['ville'] . ' - ' . $logement['type']); ?>">
                                <input type="hidden" name="prix" value="<?php echo htmlspecialchars($logement['prix']); ?>">
                                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <button type="submit">Ajouter au budget</button>
                            </form>
                        </div>

                    </div>

                </article>

            <?php } ?>

        </div>

    </div>

    <div class="tab-content" id="activites">

        <div class="tab-title-row">
            <div>
                <h2>Activités étudiantes</h2>
                <p>
                    Une sélection d’activités gratuites, culturelles ou petit budget
                    adaptées à votre circuit.
                </p>
            </div>
        </div>

        <div class="activity-grid">

            <?php foreach ($activites as $activite) { ?>

                <article class="activity-card">

                    <div
                        class="activity-img"
                        style="background-image:url('<?php echo $activite['image']; ?>')"
                    >
                        <span><?php echo $activite['type']; ?></span>
                    </div>

                    <div class="activity-content">

                        <small><?php echo $activite['ville']; ?></small>

                        <h3><?php echo $activite['nom']; ?></h3>

                        <p><?php echo $activite['desc']; ?></p>

                        <div class="activity-bottom">
                            <strong>
                                <?php echo $activite['prix']; ?>€
                            </strong>

                            <form action="actions/add_to_cart.php" method="POST">
                                <input type="hidden" name="type" value="Activité">
                                <input type="hidden" name="nom" value="<?php echo htmlspecialchars($activite['nom']); ?>">
                                <input type="hidden" name="details" value="<?php echo htmlspecialchars($activite['ville'] . ' - ' . $activite['type']); ?>">
                                <input type="hidden" name="prix" value="<?php echo htmlspecialchars($activite['prix']); ?>">
                                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <button type="submit">Ajouter au budget</button>
                            </form>
                        </div>

                    </div>

                </article>

            <?php } ?>

        </div>

    </div>

    <div class="tab-content" id="budget">
        <?php
            $itemsBudget = Panier::getItems();
            $totalBudget = Panier::getTotal();
            $voyageursBudget = $_SESSION['voyageurs'] ?? 1;

            if ($voyageursBudget <= 0) {
                $voyageursBudget = 1;
            }

            $totalParPersonne = $totalBudget / $voyageursBudget;
        ?>

        <div class="tab-title-row">
            <div>
                <h2>Budget</h2>
                <p>Votre budget se construit automatiquement selon les éléments ajoutés au circuit.</p>
            </div>
        </div>

        <div class="budget-planner">

            <?php if (empty($itemsBudget)) { ?>

                <p>Aucun élément ajouté pour le moment.</p>

            <?php } else { ?>

                <?php foreach ($itemsBudget as $item) { ?>
                    <p>
                        <span><?php echo htmlspecialchars($item['type']); ?> - <?php echo htmlspecialchars($item['nom']); ?></span>
                        <strong><?php echo htmlspecialchars($item['prix']); ?>€</strong>
                    </p>
                <?php } ?>

                <hr>

                <p class="budget-final">
                    <span>Total</span>
                    <strong><?php echo number_format($totalBudget, 2); ?>€</strong>
                </p>

                <p class="budget-final">
                    <span>Total / personne</span>
                    <strong><?php echo number_format($totalParPersonne, 2); ?>€</strong>
                </p>

                <a class="budget-cart-link" href="index.php?page=panier">
                    Voir le panier complet
                </a>

            <?php } ?>

        </div>
    </div></section>

<script>
const buttons = document.querySelectorAll('.tab-btn');
const contents = document.querySelectorAll('.tab-content');

buttons.forEach(button => {
    button.addEventListener('click', () => {
        buttons.forEach(btn => btn.classList.remove('active'));
        contents.forEach(content => content.classList.remove('active'));

        button.classList.add('active');
        document.getElementById(button.dataset.tab).classList.add('active');
    });
});
</script>