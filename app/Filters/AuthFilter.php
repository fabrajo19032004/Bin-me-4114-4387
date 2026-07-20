<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Roles autorisés par filtre.
     * Exemple d'usage dans Routes.php :
     *   $routes->group('admin', ['filter' => 'auth:admin'], function($routes) { ... });
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Non connecté → login
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('auth/login'))
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du rôle si précisé
        if (!empty($arguments)) {
            // CI4 peut passer les arguments comme ['agent_commercial,admin']
            // (string avec virgule) ou ['agent_commercial', 'admin'] (tableau).
            // On normalise dans les deux cas.
            $rolesAutorises  = [];
            foreach ($arguments as $arg) {
                foreach (explode(',', $arg) as $r) {
                    $rolesAutorises[] = trim($r);
                }
            }

            $roleUtilisateur = session()->get('role');

            if (!in_array($roleUtilisateur, $rolesAutorises)) {
                // Connecté mais mauvais rôle → retour à son propre dashboard
                $dashMap = [
                    'agent_commercial' => 'operator/prefixes',
                    'magasinier'       => 'operator/prefixes',
                    'livreur'          => 'operator/prefixes',
                    'admin'            => 'operator/prefixes',
                ];
                $redirect = $dashMap[$roleUtilisateur] ?? 'auth/login';
                return redirect()->to(base_url($redirect))
                    ->with('error', 'Accès non autorisé pour votre rôle.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ─────────────────────────────────────────────────────────────────
        // Empêche le navigateur de mettre en cache les pages protégées.
        // Sans ça, un F5, un retour arrière, ou une réouverture de l'onglet
        // après déconnexion peut réafficher la dernière page vue depuis le
        // cache local SANS repasser par before() → faille de sécurité
        // (un utilisateur déconnecté/changé de rôle voit une page à laquelle
        // il ne devrait plus avoir accès).
        // ─────────────────────────────────────────────────────────────────
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                 ->setHeader('Pragma', 'no-cache')
                 ->setHeader('Expires', '0');

        return $response;
    }
}
