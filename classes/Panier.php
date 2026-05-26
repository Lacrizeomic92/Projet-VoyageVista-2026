<?php

class Panier
{
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

        $item['id'] = uniqid();
        $item['prix'] = (float)($item['prix'] ?? 0);

        $_SESSION['panier'][] = $item;
    }

    public static function remove($id)
    {
        self::init();

        $_SESSION['panier'] = array_values(array_filter($_SESSION['panier'], function ($item) use ($id) {
            return $item['id'] !== $id;
        }));
    }

    public static function getItems()
    {
        self::init();
        return $_SESSION['panier'];
    }

    public static function getTotal()
    {
        self::init();

        $total = 0;

        foreach ($_SESSION['panier'] as $item) {
            $total += (float)($item['prix'] ?? 0);
        }

        return $total;
    }

    public static function clear()
    {
        self::init();
        $_SESSION['panier'] = [];
    }
}
