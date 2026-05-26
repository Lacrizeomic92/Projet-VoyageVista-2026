<?php

class Favoris
{
    private static function normalizeItem(array $item)
    {
        $item['source_type'] = strtolower((string)($item['source_type'] ?? ''));
        $item['source_id'] = isset($item['source_id']) ? (int)$item['source_id'] : 0;
        $item['db_id'] = isset($item['db_id']) ? (int)$item['db_id'] : 0;

        return $item;
    }

    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['favoris'])) {
            $_SESSION['favoris'] = [];
        }
    }

    public static function add($item)
    {
        self::init();

        $item = self::normalizeItem($item);

        foreach ($_SESSION['favoris'] as $existingItem) {
            if (
                ($existingItem['type'] ?? '') === ($item['type'] ?? '')
                && ($existingItem['nom'] ?? '') === ($item['nom'] ?? '')
                && (int)($existingItem['source_id'] ?? 0) === (int)($item['source_id'] ?? 0)
            ) {
                return;
            }
        }

        $item['id'] = $item['id'] ?? uniqid('fav_', true);
        $_SESSION['favoris'][] = $item;
    }

    public static function remove($id)
    {
        self::init();

        $_SESSION['favoris'] = array_values(array_filter($_SESSION['favoris'], function ($item) use ($id) {
            return ($item['id'] ?? '') !== $id && (string)($item['db_id'] ?? '') !== (string)$id;
        }));
    }

    public static function getItems()
    {
        self::init();

        return array_map(function ($item) {
            return self::normalizeItem($item);
        }, $_SESSION['favoris']);
    }
}
