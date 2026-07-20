<?php

namespace App\Controllers;

class AuthController extends BaseController
{

    public function testDb()
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT 1');
        echo 'Connexion réussie !';
    }
}
