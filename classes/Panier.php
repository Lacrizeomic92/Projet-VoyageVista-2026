<?php

class Panier
{
    private static function normalizeItem(array $item)
    {
        $item['source_type'] = strtolower((string)($item['source_type'] ?? ''));
        $item['source_id'] = isset($item['source_id']) ? (int)$item['source_id'] : 0;
        $item['quantity'] = max(1, (int)($item['quantity'] ?? 1));
        $item['unit_price'] = (float)($item['unit_price'] ?? $item['prix'] ?? 0);
        $item['prix'] = $item['unit_price'];
        $item['line_total'] = $item['unit_price'] * $item['quantity'];

        return $item;
    }

    private static function findExistingIndex(array $item)
    {
        foreach ($_SESSION['panier'] as $index => $existingItem) {
            $sameSource = !empty($item['source_type'])
                && ($existingItem['source_type'] ?? '') === $item['source_type']
                && (int)($existingItem['source_id'] ?? 0) === (int)($item['source_id'] ?? 0);

            $sameCustomItem =
                ($existingItem['type'] ?? '') === ($item['type'] ?? '')
                && ($existingItem['nom'] ?? '') === ($item['nom'] ?? '')
                && ($existingItem['details'] ?? '') === ($item['details'] ?? '')
                && empty($item['source_type']);

            if ($sameSource || $sameCustomItem) {
                return $index;
            }
        }

        return null;
    }

    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
    }

    public static function add($item)
    {
        self::init();

        $item = self::normalizeItem($item);
        $existingIndex = self::findExistingIndex($item);

        if ($existingIndex !== null) {
            $_SESSION['panier'][$existingIndex]['quantity'] += $item['quantity'];
            $_SESSION['panier'][$existingIndex] = self::normalizeItem($_SESSION['panier'][$existingIndex]);
            return;
        }

        $item['id'] = $item['id'] ?? uniqid('cart_', true);
        $_SESSION['panier'][] = $item;
    }

    public static function remove($id)
    {
        self::init();

        $_SESSION['panier'] = array_values(array_filter($_SESSION['panier'], function ($item) use ($id) {
            return $item['id'] !== $id;
        }));
    }

    public static function update($id, array $data)
    {
        self::init();

        foreach ($_SESSION['panier'] as $index => $item) {
            if (($item['id'] ?? '') !== $id) {
                continue;
            }

            $_SESSION['panier'][$index]['quantity'] = max(1, (int)($data['quantity'] ?? 1));
            $_SESSION['panier'][$index] = self::normalizeItem($_SESSION['panier'][$index]);

            return true;
        }

        return false;
    }

    public static function getItems()
    {
        self::init();

        return array_map(function ($item) {
            return self::normalizeItem($item);
        }, $_SESSION['panier']);
    }

    public static function getTotal()
    {
        self::init();

        $total = 0;

        foreach ($_SESSION['panier'] as $item) {
            $normalizedItem = self::normalizeItem($item);
            $total += (float)($normalizedItem['line_total'] ?? 0);
        }

        return $total;
    }

    public static function clear()
    {
        self::init();
        $_SESSION['panier'] = [];
    }
}
