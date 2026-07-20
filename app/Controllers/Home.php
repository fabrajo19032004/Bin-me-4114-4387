<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    // Nouvelle méthode pour tester la base de données
    public function testDb()
    {
        // On tente de se connecter et d'exécuter une requête simple
        try {
            $db = \Config\Database::connect();
            $result = $db->query('SELECT 1');
            
            if ($result) {
                echo "✅ Connexion à la base SQLite réussie !";
                echo "<br>Fichier utilisé : " . $db->getDatabase();
            }
        } catch (\Exception $e) {
            echo "❌ Erreur de connexion : " . $e->getMessage();
        }
    }
}
