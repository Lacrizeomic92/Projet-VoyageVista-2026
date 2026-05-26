<?php

class Favoris
{
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

        $item['id'] = uniqid();
        $_SESSION['favoris'][] = $item;
    }

    public static function remove($id)
    {
        self::init();

        $_SESSION['favoris'] = array_values(array_filter($_SESSION['favoris'], function ($item) use ($id) {
            return $item['id'] !== $id;
        }));
    }

    public static function getItems()
    {
        self::init();
        return $_SESSION['favoris'];
    }
}
