<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DonneeSeeder extends Seeder
{
    public function run()
    {
        $idsCategorieProduct  = $this->seedCategorieProduct();
        $idsCategoriesDepense = $this->seedCategoriesDepense();
        $idsCategoriesPack    = $this->seedCategoriesPack();
        $idsClients           = $this->seedClients();
        $idsProduit           = $this->seedProduit($idsCategorieProduct);
        $idsEntreeStock       = $this->seedEntreeStock($idsProduit);
        $idsPacks             = $this->seedPacks($idsCategorieProduct, $idsCategoriesPack);
        $this->seedPackProduits($idsPacks, $idsProduit);
        $idsSortieStock       = $this->seedSortieStock($idsProduit);
        $this->seedDetailSortie($idsSortieStock, $idsEntreeStock);
        $idsReservations      = $this->seedReservations($idsClients);
        $this->seedReservationPack($idsReservations, $idsPacks);
        $this->seedPaiements($idsReservations);
        $this->seedRecuperations($idsReservations);
        $this->seedInteractions($idsClients);
        $idsDepense           = $this->seedDepense($idsCategoriesDepense);
        $this->seedTransaction($idsDepense);
        $this->seedCapitals();
        $this->seedArchives($idsPacks);
    }

    // ---------- Helpers ----------

    private function randDate($startYear = 2025, $endYear = 2026)
    {
        $start = strtotime("$startYear-01-01");
        $end   = strtotime("$endYear-12-31");
        return date('Y-m-d H:i:s', mt_rand($start, $end));
    }

    private function randCode($length = 8)
    {
        return strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
    }

    private function pick(array $arr)
    {
        return $arr[array_rand($arr)];
    }

    /**
     * Retourne $count elements uniques pris au hasard dans $arr.
     * Si $arr est plus petit que $count, les valeurs peuvent se repeter
     * (utile pour les tables sans contrainte d'unicite composite).
     */
    private function pickUnique(array $arr, int $count): array
    {
        $arr = array_values($arr);
        if ($count >= count($arr)) {
            shuffle($arr);
            return $arr;
        }
        $shuffled = $arr;
        shuffle($shuffled);
        return array_slice($shuffled, 0, $count);
    }

    // ---------- Seeders ----------

    private function seedCategorieProduct(): array
    {
        $noms = ['Fruits', 'Legumes', 'Produits laitiers', 'Boissons', 'Epicerie', 'Viandes', 'Cereales', 'Snacks', 'Hygiene', 'Surgeles', 'Boulangerie', 'Condiments', 'Poissonnerie', 'Patisserie'];
        $ids = [];
        foreach ($noms as $nom) {
            $this->db->table('categorie_product')->insert([
                'nom'         => $nom,
                'description' => match($nom){
                    'Fruits'=>'Fruits frais et produits derives.',
                    'Legumes'=>'Legumes frais pour la consommation.',
                    'Produits laitiers'=>'Lait, yaourts et produits laitiers.',
                    'Boissons'=>'Boissons avec ou sans alcool.',
                    'Epicerie'=>'Produits d epicerie courante.',
                    'Viandes'=>'Viandes fraiches et preparees.',
                    'Cereales'=>'Riz, mais et cereales.',
                    'Snacks'=>'Snacks et produits a grignoter.',
                    'Hygiene'=>'Produits d hygiene personnelle.',
                    default=>'Categorie de produits.'},
                'created_at'  => $this->randDate(),
                'updated_at'  => $this->randDate(),
            ]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedCategoriesDepense(): array
    {
        $data = [
            ['Loyer', 'FIXE'],
            ['Electricite', 'FIXE'],
            ['Eau', 'FIXE'],
            ['Transport', 'VARIABLE'],
            ['Fournitures', 'VARIABLE'],
            ['Salaires', 'FIXE'],
            ['Marketing', 'VARIABLE'],
            ['Maintenance', 'VARIABLE'],
            ['Assurance', 'FIXE'],
            ['Internet', 'FIXE'],
            ['Emballage', 'VARIABLE'],
            ['Divers', 'VARIABLE'],
            ['Formation', 'VARIABLE'],
            ['Impots et taxes', 'FIXE'],
        ];
        $ids = [];
        foreach ($data as [$nom, $typeParent]) {
            $this->db->table('categories_depense')->insert([
                'nom'         => $nom,
                'type_parent' => $typeParent,
            ]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedCategoriesPack(): array
    {
        $noms = ['Pack Famille', 'Pack Solo', 'Pack Etudiant', 'Pack Entreprise', 'Pack Fetes', 'Pack Economique', 'Pack Premium', 'Pack Decouverte', 'Pack Weekend', 'Pack Sante', 'Pack Voyage', 'Pack Anniversaire', 'Pack Rentree', 'Pack Saisonnier'];
        $ids = [];
        foreach ($noms as $nom) {
            $this->db->table('categories_pack')->insert(['nom' => $nom]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedClients(): array
    {
        $prenoms  = ['Hery', 'Fanja', 'Tovo', 'Nirina', 'Miora', 'Andry', 'Fara', 'Zo', 'Nomena', 'Voahangy', 'Rado', 'Malala', 'Tahiry', 'Sitraka', 'Onja', 'Lova', 'Ranto', 'Sedera'];
        $noms     = ['Rakoto', 'Rabe', 'Randria', 'Andria', 'Ravelo', 'Rasoa', 'Razafy', 'Ramanana', 'Rakotondrabe', 'Ranaivo'];
        $regions  = ['Analamanga', 'Vakinankaratra', 'Atsinanana', 'Boeny', 'Haute Matsiatra', 'Anosy', 'Atsimo Andrefana', 'Sofia'];
        $statuts  = ['nouveau', 'fidele', 'inactif'];
        $ids = [];
        for ($i = 0; $i < 10; $i++) {
            $nomComplet = $this->pick($prenoms) . ' ' . $this->pick($noms);
            $this->db->table('clients')->insert([
                'nom'            => $nomComplet,
                'telephone'      => '034' . mt_rand(1000000, 9999999),
                'email'          => strtolower(str_replace(' ', '.', $nomComplet)) . $i . '@example.mg',
                'adresse'        => 'Lot ' . mt_rand(1, 999) . ' Bis, Antananarivo',
                'region'         => $this->pick($regions),
                'district'       => 'District ' . mt_rand(1, 6),
                'statut'         => $this->pick($statuts),
                'date_creation'  => date('Y-m-d', strtotime($this->randDate())),
                'created_at'     => $this->randDate(),
                'updated_at'     => $this->randDate(),
            ]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedProduit(array $idsCategorieProduct): array
    {
        $produits = [
            ['nom'=>'Banane','categorie'=>'Fruits'],
            ['nom'=>'Pomme','categorie'=>'Fruits'],
            ['nom'=>'Tomate','categorie'=>'Legumes'],
            ['nom'=>'Oignon','categorie'=>'Legumes'],
            ['nom'=>'Lait entier','categorie'=>'Produits laitiers'],
            ['nom'=>'Huile vegetale','categorie'=>'Epicerie'],
            ['nom'=>'Sucre roux','categorie'=>'Epicerie'],
            ['nom'=>'Farine de ble','categorie'=>'Epicerie'],
            ['nom'=>'Riz blanc','categorie'=>'Cereales'],
            ['nom'=>'Poulet frais','categorie'=>'Viandes'],
        ];
        $methodes=['FIFO','LIFO','CUMP'];
        $ids=[];
        $cats=$this->db->table('categorie_product')->select('id,nom')->get()->getResultArray();
        $map=[]; foreach($cats as $c){$map[$c['nom']]=$c['id'];}
        foreach($produits as $p){
            $this->db->table('produit')->insert([
                'id_categorie'=>$map[$p['categorie']],
                'nom'=>$p['nom'],
                'methode_sortie'=>$this->pick($methodes),
                'stock_minimum'=>mt_rand(5,50),
                'created_at'=>$this->randDate(),
                'updated_at'=>$this->randDate(),
            ]);
            $ids[]=$this->db->insertID();
        }
        return $ids;
    }

    private function seedEntreeStock(array $idsProduit): array
    {
        $fournisseurs = ['Grossiste Tana', 'Cofimpor', 'Socolait', 'Star Distribution', 'Sodiat', 'Marche Anosibe', 'Import Express'];
        $origines     = ['reapprovisionnement', 'initial', 'retour'];
        $ids = [];
        for ($i = 0; $i < 10; $i++) {
            $quantite = mt_rand(50, 500);
            $this->db->table('entree_stock')->insert([
                'id_produit'        => $this->pick($idsProduit),
                'quantite'          => $quantite,
                'prix_unitaire'     => mt_rand(1000, 15000) / 10,
                'quantite_restante' => mt_rand(0, $quantite),
                'fournisseur'       => $this->pick($fournisseurs),
                'date_entree'       => $this->randDate(),
                'reference'         => 'ENT-' . $this->randCode(6),
                'origine'           => $this->pick($origines),
                'created_at'        => $this->randDate(),
                'updated_at'        => $this->randDate(),
            ]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedPacks(array $idsCategorieProduct, array $idsCategoriesPack): array
    {
        $noms = ['Pack Riz & Huile', 'Pack Petit Dejeuner', 'Pack Menage', 'Pack Boissons Fraiches', 'Pack Viandes', 'Pack Fruits & Legumes', 'Pack Hygiene Complet', 'Pack Fete', 'Pack Etudiant Malin', 'Pack Famille XL', 'Pack Express', 'Pack Bio', 'Pack Gourmand', 'Pack Economique Plus', 'Pack Cadeau', 'Pack Anniversaire Special'];
        $statuts = ['actif', 'inactif'];
        $types   = ['personnalized', 'ordinary'];
        $ids = [];
        // recuperer les noms de categories pour remplir les champs texte denormalises
        $catProduitNoms = $this->db->table('categorie_product')->select('nom')->get()->getResultArray();
        $catPackNoms    = $this->db->table('categories_pack')->select('nom')->get()->getResultArray();

        foreach ($noms as $nom) {
            $coutRevient = mt_rand(5000, 30000);
            $this->db->table('packs')->insert([
                'id_categorie'      => $this->pick($idsCategorieProduct),
                'nom'               => $nom,
                'categorie_produit' => $this->pick($catProduitNoms)['nom'],
                'categorie_pack'    => $this->pick($catPackNoms)['nom'],
                'date_creation'     => date('Y-m-d', strtotime($this->randDate())),
                'description'       => 'Description du ' . $nom,
                'prix_vente'        => $coutRevient * 1.3,
                'cout_revient'      => $coutRevient,
                'statut'            => $this->pick($statuts),
                'type'              => $this->pick($types),
                'created_at'        => $this->randDate(),
                'updated_at'        => $this->randDate(),
            ]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedPackProduits(array $idsPacks, array $idsProduit): void
    {
        // chaque pack recoit 3 a 5 produits distincts : garantit largement une
        // quinzaine de lignes au total (15 packs x ~4 produits en moyenne)
        foreach ($idsPacks as $packId) {
            $nbProduits = mt_rand(2, 3);
            $produitsChoisis = $this->pickUnique($idsProduit, $nbProduits);
            foreach ($produitsChoisis as $produitId) {
                $this->db->table('pack_produits')->insert([
                    'pack_id'    => $packId,
                    'produit_id' => $produitId,
                    'quantite'   => mt_rand(1, 2),
                ]);
            }
        }
    }

    private function seedSortieStock(array $idsProduit): array
    {
        $origines = ['vente', 'perte', 'don'];
        $ids = [];
        for ($i = 0; $i < 10; $i++) {
            $quantite = mt_rand(1, 5);
            $this->db->table('sortie_stock')->insert([
                'id_produit'  => $this->pick($idsProduit),
                'quantite'    => $quantite,
                'cout_total'  => $quantite * mt_rand(500, 5000),
                'id_agent'    => null,
                'reference'   => 'SOR-' . $this->randCode(6),
                'origine'     => $this->pick($origines),
                'created_at'  => $this->randDate(),
                'updated_at'  => $this->randDate(),
            ]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedDetailSortie(array $idsSortieStock, array $idsEntreeStock): void
    {
        // 2 a 4 details par sortie : garantit bien plus d'une vingtaine de lignes
        foreach ($idsSortieStock as $sortieId) {
            $nbDetails = 1;
            for ($i = 0; $i < $nbDetails; $i++) {
                $quantitePris = mt_rand(1, 3);
                $prixUnitaire = mt_rand(1000, 15000) / 10;
                $this->db->table('detail_sortie')->insert([
                    'id_sortie'     => $sortieId,
                    'id_entree'     => $this->pick($idsEntreeStock),
                    'quantite_pris' => $quantitePris,
                    'prix_unitaire' => $prixUnitaire,
                    'montant'       => $quantitePris * $prixUnitaire,
                    'created_at'    => $this->randDate(),
                    'updated_at'    => $this->randDate(),
                ]);
            }
        }
    }

    private function seedReservations(array $idsClients): array
    {
        $statuts = array_merge(array_fill(0,5,'confirmee'), array_fill(0,5,'en_attente')); shuffle($statuts);
        $ids = [];
        for ($i = 0; $i < 10; $i++) {
            $this->db->table('reservations')->insert([
                'client_id'       => $this->pick($idsClients),
                'statut'          => $statuts[$i],
                'code_validation' => $this->randCode(8),
                'date_expiration' => $this->randDate(2026, 2026),
                'created_at'      => $this->randDate(),
                'updated_at'      => $this->randDate(),
            ]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedReservationPack(array $idsReservations, array $idsPacks): void
    {
        foreach ($idsReservations as $reservationId) {
            $packsChoisis = $this->pickUnique($idsPacks, 1);
            foreach ($packsChoisis as $packId) {
                $this->db->table('reservation_pack')->insert([
                    'reservation_id' => $reservationId,
                    'pack_id' => $packId,
                    'quantite' => 1,
                ]);
            }
        }
    }

    private function seedPaiements(array $idsReservations): void
{
    $types   = ['acompte', 'solde', 'complet'];
    $modes   = ['mvola', 'orange_money', 'airtel_money'];
    $statuts = ['en_attente', 'valide', 'refuse'];

    // Seulement les 5 premières réservations recevront un paiement
    $reservationsAvecPaiement = array_slice($idsReservations, 0, 5);

    foreach ($reservationsAvecPaiement as $reservationId) {

        $this->db->table('paiements')->insert([
            'reservation_id' => $reservationId,
            'montant'        => mt_rand(5000, 100000),
            'type'           => $this->pick($types),
            'mode'           => $this->pick($modes),
            'reference'      => 'PAY-' . $this->randCode(8),
            'statut'         => $this->pick($statuts),
            'created_at'     => $this->randDate(),
            'updated_at'     => $this->randDate(),
        ]);
    }
}

    private function seedRecuperations(array $idsReservations): void
    {
        $statuts = ['non_recupere', 'recupere'];
        foreach ($idsReservations as $reservationId) {
            $statut = $this->pick($statuts);
            $this->db->table('recuperations')->insert([
                'reservation_id' => $reservationId,
                'statut'         => $statut,
                'date_remise'    => $statut === 'recupere' ? $this->randDate() : null,
                'created_at'     => $this->randDate(),
                'updated_at'     => $this->randDate(),
            ]);
        }
    }

    private function seedInteractions(array $idsClients): void
    {
        $types = ['appel', 'email', 'visite', 'reclamation', 'sms'];
        for ($i = 0; $i < 10; $i++) {
            $this->db->table('interactions')->insert([
                'client_id'   => $this->pick($idsClients),
                'type'        => $this->pick($types),
                'description' => 'Interaction client de type ' . $this->pick($types),
                'date'        => $this->randDate(),
                'created_at'  => $this->randDate(),
                'updated_at'  => $this->randDate(),
            ]);
        }
    }

    private function seedDepense(array $idsCategoriesDepense): array
    {
        $libelles = ['Paiement loyer mensuel', 'Facture electricite', 'Facture eau', 'Carburant livraison', 'Achat fournitures bureau', 'Salaires personnel', 'Campagne publicitaire', 'Maintenance vehicule', 'Prime assurance', 'Abonnement internet', 'Achat emballages', 'Frais divers', 'Formation du personnel', 'Paiement impots'];
        $ids = [];
        foreach ($libelles as $libelle) {
            $this->db->table('depense')->insert([
                'libelle'        => $libelle,
                'id_categorie'   => $this->pick($idsCategoriesDepense),
                'jour_echeance'  => mt_rand(1, 28),
                'est_recurrent'  => mt_rand(0, 1),
                'montant_defaut' => mt_rand(20000, 500000),
                'created_at'     => $this->randDate(),
                'updated_at'     => $this->randDate(),
            ]);
            $ids[] = $this->db->insertID();
        }
        return $ids;
    }

    private function seedTransaction(array $idsDepense): void
    {
        $statuts = ['EN_ATTENTE', 'VALIDE', 'REJETE'];
        $sources = ['MANUELLE', 'AUTOMATIQUE'];
        for ($i = 0; $i < 10; $i++) {
            $this->db->table('transaction')->insert([
                'id_depense'        => $this->pick($idsDepense),
                'montant'           => mt_rand(10000, 500000),
                'date_transaction'  => date('Y-m-d', strtotime($this->randDate())),
                'statut_validation' => $this->pick($statuts),
                'source_type'       => $this->pick($sources),
                'justificatif'      => 'justificatifs/facture_' . $this->randCode(4) . '.pdf',
                'created_at'        => $this->randDate(),
                'updated_at'        => $this->randDate(),
            ]);
        }
    }

    private function seedCapitals(): void
    {
        $sources = ['Associe principal', 'Banque BOA', 'Banque BNI', 'Subvention Etat', 'Don ONG partenaire', 'Investisseur prive', 'Associe secondaire'];
        $types   = ['apport_associe', 'investissement', 'emprunt', 'subvention', 'don', 'autre'];
        for ($i = 0; $i < 10; $i++) {
            $this->db->table('capitals')->insert([
                'source'       => $this->pick($sources),
                'montant'      => mt_rand(100000, 5000000),
                'type'         => $this->pick($types),
                'description'  => 'Entree de capital diverse',
                'date_entree'  => $this->randDate(),
                'created_at'   => $this->randDate(),
                'updated_at'   => $this->randDate(),
            ]);
        }
    }

    private function seedArchives(array $idsPacks): void
    {
        $statuts = ['actif', 'inactif'];
        $types   = ['personnalized', 'ordinary'];
        $actions = ['SUPPRESSION', 'MODIFICATION'];
        for ($i = 0; $i < 10; $i++) {
            $coutRevient = mt_rand(5000, 30000);
            $this->db->table('archives')->insert([
                'id_pack'      => $this->pick($idsPacks),
                'nom'          => 'Archive pack ' . ($i + 1),
                'description'  => 'Version archivee du pack',
                'prix_vente'   => $coutRevient * 1.3,
                'cout_revient' => $coutRevient,
                'statut'       => $this->pick($statuts),
                'type'         => $this->pick($types),
                'action'       => $this->pick($actions),
                'created_at'   => $this->randDate(),
                'updated_at'   => $this->randDate(),
            ]);
        }
    }
}