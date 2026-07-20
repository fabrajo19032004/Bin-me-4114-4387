# Explication du code - `FinanceService.php`

## But du code dans le projet

Ce service sert de couche de logique entre la base de données et le reste de l'application pour tout ce qui concerne les finances. Il récupère les dépenses, les transactions, les statistiques du tableau de bord, les alertes de dépenses récurrentes, le solde de caisse, les catégories et l'historique financier. Son rôle est de centraliser les calculs et les règles métier pour que les contrôleurs n'aient pas à refaire ces règles partout.

## Lecture générale du fichier

Ce fichier est une classe PHP nommée `FinanceService`. Une classe est un bloc qui regroupe des fonctions qui travaillent ensemble. Ici, toutes les fonctions servent à gérer les finances.

La classe contient :
- des variables internes pour parler aux modèles de base de données,
- des fonctions publiques que le reste du projet peut appeler,
- des fonctions privées qui aident les fonctions publiques à faire leur travail.

## Ordre logique d'utilisation

1. Le constructeur prépare les accès aux modèles.
2. Les fonctions du dashboard récupèrent les données visibles en page d'accueil de finance.
3. Les fonctions financières calculent les totaux et les indicateurs.
4. Les fonctions de catégories gèrent les types de dépenses.
5. Les fonctions de transactions lisent, filtrent, valident et modifient les transactions.
6. Les fonctions de dépenses enregistrent une dépense simple ou récurrente.
7. Les fonctions privées font les calculs, les filtres et les petits traitements.

---

## 1) Les propriétés de la classe

```php
protected CapitalModel $capitalModel;
protected DepenseModel $depenseModel;
protected TransactionModel $transactionModel;
protected PaiementModel $paiementModel;
protected CategorieDepenseModel $categorieDepenseModel;
```

### Explication simple

Ces lignes déclarent des variables internes qui vont contenir les objets de travail avec la base de données.

- `protected` veut dire que la classe peut les utiliser, et que les classes qui héritent de celle-ci peuvent aussi les utiliser.
- `CapitalModel`, `DepenseModel`, `TransactionModel`, `PaiementModel`, `CategorieDepenseModel` sont des modèles. Un modèle sert à lire ou écrire dans une table de la base de données.
- Le `: type` après chaque variable dit quel type de donnée on attend.

### Pseudo-code

```text
préparer des accès vers les tables importantes
```

---

## 2) Constructeur

### Fonction `__construct()`

```php
public function __construct()
{
    $this->capitalModel = new CapitalModel();
    $this->depenseModel = new DepenseModel();
    $this->transactionModel = new TransactionModel();
    $this->paiementModel = new PaiementModel();
    $this->categorieDepenseModel = new CategorieDepenseModel();
}
```

### Explication ligne par ligne

- `public function __construct()` : c'est la fonction automatique appelée quand on crée un objet `FinanceService`.
- `$this->capitalModel = new CapitalModel();` : on crée un objet pour manipuler le capital.
- `$this->depenseModel = new DepenseModel();` : on crée un objet pour manipuler les dépenses.
- `$this->transactionModel = new TransactionModel();` : on crée un objet pour manipuler les transactions.
- `$this->paiementModel = new PaiementModel();` : on crée un objet pour manipuler les paiements.
- `$this->categorieDepenseModel = new CategorieDepenseModel();` : on crée un objet pour manipuler les catégories de dépenses.

### Ce que fait l'algorithme

Au démarrage du service, il prépare tous les outils dont il aura besoin. Comme un enfant qui prépare ses crayons avant de faire ses devoirs.

### Pseudo-code

```text
au moment de créer le service
    créer les objets pour accéder aux données financières
```

---

## 3) Section Dashboard

Cette partie sert à fabriquer les données affichées sur le tableau de bord finance.

### Fonction `getRappelsEtDepensesMensuelles()`

```php
public function getRappelsEtDepensesMensuelles(): array
{
    $depensesRecurrentes = $this->recupererDepensesRecurrentes();
    $today = $this->getToday();

    $rappels = [];
    $depensesMensuelles = [];
    $alerteActive = false;

    foreach ($depensesRecurrentes as $depense) {
        $resultat = $this->traiterDepenseRecurrente($depense, $today);
        
        $rappels[] = $resultat['rappel'];
        $depensesMensuelles[] = $resultat['depenseMensuelle'];
        
        if ($resultat['estAlerte']) {
            $alerteActive = true;
        }
    }

    $this->trierParJoursRestants($depensesMensuelles);
    $this->trierParJoursRestants($rappels);

    return [
        'rappels' => $rappels,
        'depensesMensuelles' => $depensesMensuelles,
        'alerteActive' => $alerteActive,
    ];
}
```

### Explication ligne par ligne

- `public function ... : array` : la fonction est accessible depuis l'extérieur et elle renvoie un tableau.
- `$depensesRecurrentes = ...` : on récupère toutes les dépenses récurrentes enregistrées.
- `$today = ...` : on récupère la date d'aujourd'hui à minuit.
- `$rappels = [];` : on prépare une liste vide pour les rappels.
- `$depensesMensuelles = [];` : on prépare une liste vide pour les dépenses mensuelles.
- `$alerteActive = false;` : au départ, on dit qu'aucune alerte n'est active.
- `foreach (...)` : on regarde chaque dépense récurrente une par une.
- `$resultat = ...` : on traite cette dépense pour calculer son prochain passage, les jours restants, et l'alerte éventuelle.
- `$rappels[] = ...` : on ajoute le rappel dans la liste.
- `$depensesMensuelles[] = ...` : on ajoute la version simplifiée de la dépense dans la liste.
- `if ($resultat['estAlerte'])` : si cette dépense doit déclencher une alerte,
- `$alerteActive = true;` : on marque qu'il existe au moins une alerte.
- `$this->trierParJoursRestants(...)` : on trie les listes pour mettre les dates les plus proches au début.
- `return [...]` : on renvoie un tableau avec les résultats prêts à afficher.

### Algorithme expliqué simplement

1. Prendre toutes les dépenses récurrentes.
2. Pour chaque dépense, calculer quand elle doit arriver.
3. Vérifier si elle est proche et si une transaction existe déjà pour ce mois.
4. Construire deux listes propres pour l'affichage.
5. Trier ces listes par date la plus proche.
6. Dire au reste du projet si au moins une alerte existe.

### Pseudo-code

```text
prendre les dépenses récurrentes
prendre la date du jour
créer des listes vides
pour chaque dépense récurrente
    calculer le prochain paiement
    construire un rappel
    construire une dépense mensuelle
    si une alerte existe alors activer l'alerte globale
trier les listes par jours restants
retourner les listes et le drapeau d'alerte
```

### Fonction `getDernieresTransactions(int $limit = 3)`

```php
public function getDernieresTransactions(int $limit = 3): array
{
    return $this->transactionModel
        ->select('transaction.*, depense.libelle, depense.est_recurrent, categories_depense.nom as categorie_nom, categories_depense.type_parent')
        ->join('depense', 'depense.id_depense = transaction.id_depense')
        ->join('categories_depense', 'categories_depense.id_categorie = depense.id_categorie')
        ->orderBy('transaction.date_transaction', 'DESC')
        ->orderBy('transaction.id_transaction', 'DESC')
        ->limit($limit)
        ->findAll();
}
```

### Explication ligne par ligne

- `int $limit = 3` : le nombre de lignes à retourner par défaut est 3.
- `select(...)` : on choisit les colonnes utiles dans la base de données.
- `join(...)` : on relie la table `transaction` avec `depense` puis avec `categories_depense`.
- `orderBy(..., 'DESC')` : on classe du plus récent au plus ancien.
- `limit($limit)` : on garde seulement le nombre demandé de lignes.
- `findAll()` : on récupère toutes les lignes trouvées.

### Pseudo-code

```text
choisir les colonnes utiles
lier transaction avec depense
lier depense avec categorie
trier du plus récent au plus ancien
garder seulement limit lignes
retourner les lignes
```

---

## 4) Section Finances

Cette partie calcule les chiffres importants: solde, budget, validation et dépenses du mois.

### Fonction `getSoldeCaisse()`

```php
public function getSoldeCaisse(): float
{
    $db = Database::connect();

    $totalEntrees = $this->getTotalEntrees($db);
    $totalSorties = $this->getTotalSortiesValidees($db);

    return $totalEntrees - $totalSorties;
}
```

### Explication ligne par ligne

- `Database::connect()` : on ouvre une connexion à la base de données.
- `$totalEntrees = ...` : on calcule tout l'argent qui est entré.
- `$totalSorties = ...` : on calcule tout l'argent qui est sorti et validé.
- `return $totalEntrees - $totalSorties;` : le solde est l'entrée moins la sortie.

### Pseudo-code

```text
ouvrir la base de données
calculer toutes les entrées
calculer toutes les sorties validées
faire entrées moins sorties
retourner le résultat
```

### Fonction `getStatistiquesValidation()`

```php
public function getStatistiquesValidation(): array
{
    $totalTransactions = $this->transactionModel->countAll();
    $totalValidees = $this->compterTransactionsValidees();
    $totalEnAttente = $this->compterTransactionsEnAttente();

    $tauxValidation = $this->calculerTauxValidation($totalTransactions, $totalValidees);

    return [
        'totalTransactions' => $totalTransactions,
        'totalValidees' => $totalValidees,
        'totalEnAttente' => $totalEnAttente,
        'tauxValidation' => $tauxValidation,
    ];
}
```

### Explication ligne par ligne

- `countAll()` : compte toutes les transactions.
- `compterTransactionsValidees()` : compte celles qui sont validées.
- `compterTransactionsEnAttente()` : compte celles en attente.
- `calculerTauxValidation(...)` : transforme les nombres en pourcentage.
- `return [...]` : renvoie les statistiques dans un tableau.

### Pseudo-code

```text
compter toutes les transactions
compter les validées
compter les en attente
calculer le pourcentage de validation
retourner les chiffres
```

### Fonction `getBudgetConsommation()`

```php
public function getBudgetConsommation(): array
{
    $db = Database::connect();

    $budgetTotal = $this->getBudgetTotal($db);
    $totalDepenses = $this->getTotalDepensesValidees($db);

    if ($budgetTotal <= 0) {
        return $this->getBudgetVideResponse();
    }

    $pourcentage = $this->calculerPourcentageBudget($totalDepenses, $budgetTotal);
    $niveauAlerte = $this->determinerNiveauAlerte($pourcentage);

    return [
        'pourcentage' => $pourcentage,
        'totalDepenses' => $totalDepenses,
        'budgetTotal' => $budgetTotal,
        'reste' => $budgetTotal - $totalDepenses,
        'niveauAlerte' => $niveauAlerte,
        'message' => null,
    ];
}
```

### Explication ligne par ligne

- on ouvre la base de données.
- on calcule le budget total.
- on calcule les dépenses validées.
- si le budget total est nul ou négatif, on renvoie une réponse vide spéciale.
- sinon, on calcule le pourcentage de consommation.
- on décide le niveau d'alerte selon ce pourcentage.
- on renvoie les valeurs utiles à l'interface.

### Pseudo-code

```text
ouvrir la base
calculer le budget total
calculer les dépenses validées
si budget = 0
    retourner un message vide
sinon
    calculer pourcentage utilisé
    choisir le niveau d'alerte
    retourner les données du budget
```

### Fonction `getDepensesMois()`

```php
public function getDepensesMois(): float
{
    $db = Database::connect();
    $moisActuel = date('m');
    $anneeActuel = date('Y');

    return $this->getDepensesParMois($db, $moisActuel, $anneeActuel);
}
```

### Explication ligne par ligne

- `date('m')` : récupère le mois actuel.
- `date('Y')` : récupère l'année actuelle.
- `getDepensesParMois(...)` : calcule toutes les dépenses du mois choisi.

### Pseudo-code

```text
ouvrir la base
prendre le mois actuel
prendre l'année actuelle
calculer les dépenses du mois
retourner le total
```

### Fonction `getTopCategorie()`

```php
public function getTopCategorie(): ?object
{
    $db = Database::connect();

    return $this->recupererTopCategorie($db);
}
```

### Explication simple

Cette fonction va chercher la catégorie qui a le plus grand total de dépenses validées.

### Pseudo-code

```text
ouvrir la base
chercher la catégorie la plus dépensée
retourner cette catégorie
```

### Fonction `getTransactionsMois()`

```php
public function getTransactionsMois(): int
{
    $moisActuel = date('m');
    $anneeActuel = date('Y');

    return $this->compterTransactionsParMois($moisActuel, $anneeActuel);
}
```

### Explication simple

Elle compte le nombre de transactions faites pendant le mois actuel.

### Pseudo-code

```text
prendre le mois actuel
prendre l'année actuelle
compter les transactions de cette période
retourner le nombre
```

### Fonction `getMouvementsFinanciers(int $limit = 20)`

```php
public function getMouvementsFinanciers(int $limit = 20): array
{
    $db = Database::connect();

    return $this->recupererMouvementsFinanciers($db, $limit);
}
```

### Explication simple

Elle récupère une liste de mouvements financiers avec une limite par défaut de 20 lignes.

### Pseudo-code

```text
ouvrir la base
demander les derniers mouvements financiers
retourner la liste
```

### Fonction `getDepenseMoyenne()`

```php
public function getDepenseMoyenne(): float
{
    $totalTransactions = $this->transactionModel->countAll();
    $montantTotal = $this->getMontantTotalTransactions();

    return $this->calculerDepenseMoyenne($totalTransactions, $montantTotal);
}
```

### Explication ligne par ligne

- `countAll()` : compte combien il y a de transactions.
- `getMontantTotalTransactions()` : additionne tous les montants.
- `calculerDepenseMoyenne(...)` : divise le total par le nombre pour obtenir une moyenne.

### Pseudo-code

```text
compter les transactions
calculer la somme totale
diviser la somme par le nombre
retourner la moyenne
```

---

## 5) Section Catégories

### Fonction `getAllCategories()`

```php
public function getAllCategories(): array
{
    return $this->categorieDepenseModel->findAll();
}
```

### Explication simple

Cette fonction récupère toutes les catégories de dépenses stockées dans la base.

### Pseudo-code

```text
demander toutes les catégories
retourner la liste
```

### Fonction `ajouterCategorie(string $nom, string $typeParent)`

```php
public function ajouterCategorie(string $nom, string $typeParent): bool
{
    if (!$this->estTypeCategorieValide($typeParent)) {
        return false;
    }

    return (bool)$this->categorieDepenseModel->insert([
        'nom' => $nom,
        'type_parent' => $typeParent
    ]);
}
```

### Explication ligne par ligne

- on reçoit le nom et le type de la catégorie.
- on vérifie si le type est autorisé.
- si le type n'est pas valide, on refuse avec `false`.
- sinon, on insère la catégorie dans la base.
- `(bool)` transforme le résultat en vrai ou faux.

### Pseudo-code

```text
recevoir le nom et le type
vérifier si le type est autorisé
si non autorisé
    refuser
sinon
    enregistrer la catégorie
    retourner succès ou échec
```

---

## 6) Section Transactions

Cette partie gère la lecture, le filtrage, la validation, la modification et l'historique des transactions.

### Fonction `getHistoriqueTransactions()`

```php
public function getHistoriqueTransactions(): array
{
    return $this->transactionModel
        ->select('transaction.*, depense.libelle, depense.est_recurrent, depense.montant_defaut, depense.jour_echeance, categories_depense.nom as categorie_nom, categories_depense.type_parent')
        ->join('depense', 'depense.id_depense = transaction.id_depense')
        ->join('categories_depense', 'categories_depense.id_categorie = depense.id_categorie')
        ->orderBy('transaction.date_transaction', 'DESC')
        ->orderBy('transaction.id_transaction', 'DESC')
        ->findAll();
}
```

### Explication simple

Cette fonction construit la liste complète de l'historique des transactions avec les infos de la dépense et de la catégorie.

### Pseudo-code

```text
choisir les colonnes utiles
lier transaction, dépense et catégorie
trier du plus récent au plus ancien
retourner tout l'historique
```

### Fonction `getHistoriqueStats(array $transactions)`

```php
public function getHistoriqueStats(array $transactions): array
{
    $montantTotal = $this->calculerMontantTotalValide($transactions);
    $dates = $this->extraireDatesTransactions($transactions);

    return [
        'montantTotal' => $montantTotal,
        'dateDebut' => $this->formaterDateMin($dates),
        'dateFin' => $this->formaterDateMax($dates),
        'totalTransactions' => count($transactions),
    ];
}
```

### Explication ligne par ligne

- `calculerMontantTotalValide(...)` : additionne seulement les transactions validées.
- `extraireDatesTransactions(...)` : récupère les dates pour savoir la période couverte.
- `count($transactions)` : compte le nombre total de transactions reçues.
- `formaterDateMin(...)` et `formaterDateMax(...)` : donnent la première et la dernière date affichable.

### Pseudo-code

```text
additionner les transactions validées
prendre toutes les dates
trouver la date la plus ancienne
trouver la date la plus récente
compter le nombre total
retourner les statistiques
```

### Fonction `getTransactionsFiltrees(...)`

```php
public function getTransactionsFiltrees(?string $type, ?string $periode, ?string $statut, int $page, int $perPage, ?string $sort = 'asc'): array
{
    $builder = $this->construireRequeteTransactions();
    
    $this->appliquerFiltreType($builder, $type);
    $this->appliquerFiltreStatut($builder, $statut);
    $this->appliquerFiltrePeriode($builder, $periode);

    // Appliquer le tri par montant
    if ($sort === 'desc') {
        $builder->orderBy('transaction.montant', 'DESC');
    } else {
        $builder->orderBy('transaction.montant', 'ASC');
    }

    return $this->executerRequeteTransactions($builder, $page, $perPage);
}
```

### Explication ligne par ligne

- la fonction reçoit des filtres : type, période, statut, page, nombre par page et ordre de tri.
- `construireRequeteTransactions()` prépare la base de la requête avec les jointures.
- `appliquerFiltreType(...)` réduit selon le type demandé.
- `appliquerFiltreStatut(...)` réduit selon l'état de validation.
- `appliquerFiltrePeriode(...)` réduit selon la période choisie.
- `if ($sort === 'desc')` : si l'utilisateur veut décroissant, on trie du plus grand au plus petit.
- sinon, on trie du plus petit au plus grand.
- `executerRequeteTransactions(...)` lance la requête finale avec pagination.

### Pseudo-code

```text
préparer la requête de base
appliquer le filtre type
appliquer le filtre statut
appliquer le filtre période
appliquer le tri montant
exécuter la requête avec pagination
retourner le résultat
```

### Fonction `getTransactionDetails(int $idTransaction)`

```php
public function getTransactionDetails(int $idTransaction): ?array
{
    return $this->transactionModel
        ->select('transaction.*, depense.libelle, depense.id_categorie, depense.est_recurrent, depense.montant_defaut, depense.jour_echeance, categories_depense.nom as categorie_nom, categories_depense.type_parent')
        ->join('depense', 'depense.id_depense = transaction.id_depense')
        ->join('categories_depense', 'categories_depense.id_categorie = depense.id_categorie')
        ->where('transaction.id_transaction', $idTransaction)
        ->first();
}
```

### Explication simple

Elle récupère une seule transaction précise avec tous ses détails.

### Pseudo-code

```text
chercher la transaction par son id
ajouter les informations de dépense et catégorie
retourner la première ligne trouvée
```

### Fonction `getTransactionsEnAttente()`

```php
public function getTransactionsEnAttente(): array
{
    return $this->transactionModel
        ->select('transaction.*, depense.libelle, depense.id_categorie, depense.est_recurrent, depense.montant_defaut, categories_depense.nom as categorie_nom, categories_depense.type_parent')
        ->join('depense', 'depense.id_depense = transaction.id_depense')
        ->join('categories_depense', 'categories_depense.id_categorie = depense.id_categorie')
        ->where('transaction.statut_validation', 'EN_ATTENTE')
        ->orderBy('transaction.date_transaction', 'DESC')
        ->orderBy('transaction.id_transaction', 'DESC')
        ->findAll();
}
```

### Explication simple

Cette fonction récupère seulement les transactions qui attendent encore une décision.

### Pseudo-code

```text
prendre les transactions en attente
ajouter les détails utiles
trier du plus récent au plus ancien
retourner la liste
```

### Fonction `approuverTransaction(int $idTransaction)`

```php
public function approuverTransaction(int $idTransaction): bool
{
    if (!$this->transactionExiste($idTransaction)) {
        return false;
    }

    return $this->transactionModel->update($idTransaction, [
        'statut_validation' => 'VALIDEE'
    ]);
}
```

### Explication ligne par ligne

- `transactionExiste(...)` : vérifie que la transaction existe vraiment.
- si elle n'existe pas, on refuse.
- sinon, on met le statut à `VALIDEE`.

### Pseudo-code

```text
vérifier si la transaction existe
si non, refuser
sinon, changer le statut en validée
retourner le résultat
```

### Fonction `rejeterTransaction(int $idTransaction)`

```php
public function rejeterTransaction(int $idTransaction): bool
{
    if (!$this->transactionExiste($idTransaction)) {
        return false;
    }

    return $this->transactionModel->update($idTransaction, [
        'statut_validation' => 'REJETEE'
    ]);
}
```

### Explication simple

Elle fait la même chose que l'approbation, mais met le statut à `REJETEE`.

### Pseudo-code

```text
vérifier si la transaction existe
si non, refuser
sinon, changer le statut en rejetée
retourner le résultat
```

### Fonction `modifierTransaction(int $idTransaction, array $data)`

```php
public function modifierTransaction(int $idTransaction, array $data): array
{
    $db = Database::connect();
    $db->transStart();

    try {
        $transaction = $this->transactionModel->find($idTransaction);
        
        if (!$transaction) {
            throw new Exception('Transaction introuvable');
        }

        $this->mettreAJourTransaction($idTransaction, $data);
        $this->mettreAJourDepense($transaction['id_depense'], $data);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new Exception('Erreur lors de la transaction');
        }

        return ['success' => true, 'message' => 'Transaction modifiée avec succès'];

    } catch (Exception $e) {
        $db->transRollback();
        return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
    }
}
```

### Explication ligne par ligne

- `Database::connect()` : ouvre la base.
- `transStart()` : démarre une transaction SQL. Cela veut dire que plusieurs écritures doivent réussir ensemble.
- `find($idTransaction)` : cherche la transaction à modifier.
- si elle n'existe pas, on lance une exception.
- `mettreAJourTransaction(...)` : met à jour le montant et la date.
- `mettreAJourDepense(...)` : met à jour le libellé et la catégorie de la dépense liée.
- `transComplete()` : demande à la base de terminer la transaction.
- `transStatus()` : vérifie si la transaction s'est bien passée.
- si une erreur arrive, `catch` la récupère.
- `transRollback()` : annule tout ce qui a été fait dans cette transaction.
- on renvoie un tableau avec succès ou erreur.

### Pseudo-code

```text
ouvrir la base
démarrer une transaction SQL
essayer
    chercher la transaction
    si absente, générer une erreur
    mettre à jour la transaction
    mettre à jour la dépense liée
    terminer la transaction
    si la base dit que ça a échoué, générer une erreur
    retourner succès
si erreur
    annuler toutes les modifications
    retourner le message d'erreur
```

---

## 7) Section Dépenses

### Fonction `enregistrerDepense(array $data)`

```php
public function enregistrerDepense(array $data): array
{
    $estRecurrent = $data['est_recurrent'] ?? false;

    $db = Database::connect();
    $db->transStart();

    try {
        $idDepense = $this->insererDepense($data, $estRecurrent);

        if (!$idDepense) {
            throw new Exception('Erreur lors de l\'enregistrement de la dépense');
        }

        $message = $estRecurrent 
            ? $this->enregistrerDepenseRecurrente($data)
            : $this->enregistrerDepenseNonRecurrente($idDepense, $data);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new Exception('Erreur lors de la transaction');
        }

        return ['success' => true, 'message' => $message];

    } catch (Exception $e) {
        $db->transRollback();
        return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
    }
}
```

### Explication ligne par ligne

- `$estRecurrent = ...` : on regarde si la dépense doit revenir chaque mois.
- `transStart()` : on démarre une transaction pour sécuriser l'écriture.
- `insererDepense(...)` : on enregistre la dépense dans la table `depense`.
- si l'insertion échoue, on déclenche une erreur.
- si la dépense est récurrente, on prépare un message spécial.
- sinon, on crée aussi une transaction normale liée à cette dépense.
- `transComplete()` : on termine la transaction SQL.
- si la base refuse, on déclenche une erreur.
- on retourne un message de succès.
- en cas d'erreur, on annule tout avec `transRollback()`.

### Pseudo-code

```text
vérifier si la dépense est récurrente
ouvrir la base
démarrer la transaction
essayer
    enregistrer la dépense
    si échec, arrêter avec erreur
    si récurrente
        préparer le message récurrent
    sinon
        enregistrer aussi la transaction normale
    terminer la transaction
    si la base refuse, générer une erreur
    retourner succès
si erreur
    annuler tout
    retourner l'erreur
```

---

## 8) Méthodes privées de dashboard

Ces fonctions servent de petits blocs de travail pour `getRappelsEtDepensesMensuelles()`.

### Fonction `recupererDepensesRecurrentes()`

```php
private function recupererDepensesRecurrentes(): array
{
    return $this->depenseModel
        ->select('depense.*, categories_depense.nom as categorie_nom, categories_depense.type_parent')
        ->join('categories_depense', 'categories_depense.id_categorie = depense.id_categorie')
        ->where('depense.est_recurrent', 1)
        ->where('depense.deleted_at IS NULL')
        ->findAll();
}
```

### Explication simple

Elle récupère toutes les dépenses récurrentes qui ne sont pas supprimées.

### Pseudo-code

```text
prendre les dépenses récurrentes
lier la catégorie
ignorer les dépenses supprimées
retourner la liste
```

### Fonction `getToday()`

```php
private function getToday(): DateTime
{
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    return $today;
}
```

### Explication ligne par ligne

- `new DateTime()` : crée la date et l'heure actuelles.
- `setTime(0, 0, 0)` : enlève l'heure et met minuit.
- on retourne cette date propre.

### Pseudo-code

```text
créer la date d'aujourd'hui
mettre l'heure à zéro
retourner la date
```

### Fonction `traiterDepenseRecurrente(array $depense, DateTime $today)`

```php
private function traiterDepenseRecurrente(array $depense, DateTime $today): array
{
    $prochaineDate = $this->calculerProchaineEcheance($depense['jour_echeance'], $today);
    $joursRestants = $this->calculerJoursRestants($prochaineDate, $today);
    $transactionExistante = $this->transactionExistePourMois($depense['id_depense']);
    $datePrelevement = $prochaineDate->format('d/m/Y');
    
    $estAlerte = $this->verifierAlerte($transactionExistante, $joursRestants);
    $montant = (int)$depense['montant_defaut'];

    return [
        'rappel' => $this->formaterRappel($depense, $prochaineDate, $datePrelevement, $joursRestants, $estAlerte, $montant, $transactionExistante),
        'depenseMensuelle' => $this->formaterDepenseMensuelle($depense, $datePrelevement, $joursRestants, $transactionExistante),
        'estAlerte' => $estAlerte,
    ];
}
```

### Explication ligne par ligne

- on calcule la prochaine date d'échéance.
- on calcule combien de jours il reste.
- on vérifie si une transaction existe déjà pour ce mois.
- on transforme la date en format lisible.
- on décide si une alerte doit être affichée.
- on transforme le montant en nombre entier.
- on renvoie deux objets: un rappel complet et une dépense mensuelle simplifiée.

### Pseudo-code

```text
calculer la prochaine échéance
calculer les jours restants
vérifier si le mois a déjà une transaction
former la date lisible
détecter l'alerte
préparer le rappel
préparer la dépense mensuelle
retourner le tout
```

### Fonction `calculerProchaineEcheance(int $jourEcheance, DateTime $today)`

```php
private function calculerProchaineEcheance(int $jourEcheance, DateTime $today): DateTime
{
    $prochaineDate = new DateTime();
    $prochaineDate->setDate(
        $prochaineDate->format('Y'),
        $prochaineDate->format('m'),
        $jourEcheance
    );

    if ($prochaineDate < $today) {
        $prochaineDate->modify('+1 month');
    }

    return $prochaineDate;
}
```

### Explication ligne par ligne

- on crée une date de base.
- `setDate(...)` remplace l'année, le mois et le jour.
- le jour est celui de l'échéance.
- si la date construite est déjà passée par rapport à aujourd'hui,
- on ajoute un mois pour aller au prochain passage.
- on renvoie la prochaine date de prélèvement.

### Pseudo-code

```text
créer une date avec le jour d'échéance
si cette date est déjà passée
    avancer d'un mois
retourner la prochaine date
```

### Fonction `calculerJoursRestants(DateTime $prochaineDate, DateTime $today)`

```php
private function calculerJoursRestants(DateTime $prochaineDate, DateTime $today): int
{
    $interval = $today->diff($prochaineDate);
    $joursRestants = (int)$interval->format('%r%a');
    return max($joursRestants, 0);
}
```

### Explication simple

Elle calcule combien de jours séparent aujourd'hui de la prochaine date.

### Pseudo-code

```text
calculer la différence entre aujourd'hui et la prochaine date
transformer cette différence en nombre de jours
si le nombre est négatif, retourner 0
```

### Fonction `transactionExistePourMois(int $idDepense)`

```php
private function transactionExistePourMois(int $idDepense): bool
{
    return (bool)$this->transactionModel
        ->where('id_depense', $idDepense)
        ->where('MONTH(date_transaction)', date('m'))
        ->where('YEAR(date_transaction)', date('Y'))
        ->first();
}
```

### Explication simple

Cette fonction vérifie si une transaction existe déjà pour cette dépense pendant le mois courant.

### Pseudo-code

```text
chercher une transaction de cette dépense
vérifier si elle est dans le mois actuel
retourner vrai ou faux
```

### Fonction `verifierAlerte(bool $transactionExistante, int $joursRestants)`

```php
private function verifierAlerte(bool $transactionExistante, int $joursRestants): bool
{
    $rappelJours = 3;
    return !$transactionExistante && $joursRestants <= $rappelJours && $joursRestants >= 0;
}
```

### Explication ligne par ligne

- `rappelJours = 3` : on décide qu'on alerte trois jours avant.
- `!$transactionExistante` : il ne faut pas qu'une transaction existe déjà.
- `$joursRestants <= 3` : la date doit être proche.
- `$joursRestants >= 0` : on ne veut pas une date déjà passée.

### Pseudo-code

```text
si aucune transaction n'existe encore
    et si la date arrive dans 3 jours ou moins
    et si la date n'est pas passée
        alors alerte
sinon pas d'alerte
```

### Fonction `formaterRappel(...)`

```php
private function formaterRappel(array $depense, DateTime $prochaineDate, string $datePrelevement, int $joursRestants, bool $estAlerte, int $montant, bool $transactionExistante): array
{
    return [
        'id' => $depense['id_depense'],
        'libelle' => $depense['libelle'],
        'montant' => $montant,
        'jour_echeance' => (int)$depense['jour_echeance'],
        'prochaine_date' => $prochaineDate->format('Y-m-d'),
        'prochaine_date_affichage' => $datePrelevement,
        'jours_restants' => $joursRestants,
        'est_alerte' => $estAlerte,
        'message_alerte' => $this->genererMessageAlerte($depense['libelle'], $joursRestants, $montant),
        'categorie' => $depense['categorie_nom'],
        'transaction_existante' => $transactionExistante,
    ];
}
```

### Explication simple

Cette fonction transforme une dépense en objet prêt à afficher dans le tableau de bord.

### Pseudo-code

```text
prendre les données de la dépense
les ranger dans un format simple et lisible
ajouter le message d'alerte
retourner le tout
```

### Fonction `genererMessageAlerte(string $libelle, int $joursRestants, int $montant)`

```php
private function genererMessageAlerte(string $libelle, int $joursRestants, int $montant): string
{
    return $libelle . ' dans ' . $joursRestants . ' jours → prévoir ' . number_format($montant, 0, '', ' ') . ' Ar';
}
```

### Explication ligne par ligne

- la fonction construit une phrase en collant plusieurs morceaux avec `.`.
- `number_format(...)` affiche le montant avec des espaces pour mieux lire les milliers.
- `Ar` indique la monnaie.

### Pseudo-code

```text
construire un texte
mettre le nom de la dépense
mettre le nombre de jours restants
mettre le montant bien formaté
retourner le message
```

### Fonction `formaterDepenseMensuelle(...)`

```php
private function formaterDepenseMensuelle(array $depense, string $datePrelevement, int $joursRestants, bool $transactionExistante): array
{
    return [
        'libelle' => $depense['libelle'],
        'montant' => (int)$depense['montant_defaut'],
        'prochaine_date' => $datePrelevement,
        'jours_restants' => $joursRestants,
        'categorie' => $depense['categorie_nom'],
        'transaction_existante' => $transactionExistante,
    ];
}
```

### Explication simple

Elle fabrique une version courte de la dépense pour l'affichage mensuel.

### Pseudo-code

```text
garder les infos importantes
retourner un objet simple
```

### Fonction `trierParJoursRestants(array &$tableau)`

```php
private function trierParJoursRestants(array &$tableau): void
{
    usort($tableau, fn($a, $b) => $a['jours_restants'] - $b['jours_restants']);
}
```

### Explication ligne par ligne

- `array &$tableau` : le `&` veut dire que la fonction modifie directement le tableau reçu.
- `usort(...)` : trie le tableau avec une règle personnalisée.
- `fn($a, $b)` : petite fonction courte.
- `$a['jours_restants'] - $b['jours_restants']` : trie du plus petit nombre de jours au plus grand.

### Pseudo-code

```text
prendre la liste
la trier par jours restants croissants
```

---

## 9) Méthodes privées de finances

### Fonction `getTotalEntrees($db)`

```php
private function getTotalEntrees($db): float
{
    $query = $db->query(" 
        SELECT COALESCE(SUM(entree), 0) AS total
        FROM v_mouvements_financiers
        WHERE entree > 0
    ");
    return (float)($query->getRow()->total ?? 0);
}
```

### Explication simple

Elle additionne toutes les entrées d'argent positives dans la vue des mouvements financiers.

### Pseudo-code

```text
demander la somme de toutes les entrées
si rien n'existe, prendre 0
retourner le total
```

### Fonction `getTotalSortiesValidees($db)`

```php
private function getTotalSortiesValidees($db): float
{
    $query = $db->query(" 
        SELECT COALESCE(SUM(montant), 0) AS total
        FROM transaction
        WHERE statut_validation IN ('VALIDEE', 'AUTO_VALIDEE')
    ");
    return (float)($query->getRow()->total ?? 0);
}
```

### Explication simple

Elle additionne seulement les sorties qui ont été validées.

### Pseudo-code

```text
additionner les montants validés
retourner le total
```

### Fonction `compterTransactionsValidees()`

```php
private function compterTransactionsValidees(): int
{
    return $this->transactionModel
        ->whereIn('statut_validation', ['VALIDEE', 'AUTO_VALIDEE'])
        ->countAllResults();
}
```

### Explication simple

Elle compte toutes les transactions dont le statut dit qu'elles sont validées.

### Pseudo-code

```text
chercher les transactions validées
compter les résultats
retourner le nombre
```

### Fonction `compterTransactionsEnAttente()`

```php
private function compterTransactionsEnAttente(): int
{
    return $this->transactionModel
        ->where('statut_validation', 'EN_ATTENTE')
        ->countAllResults();
}
```

### Explication simple

Elle compte les transactions qui attendent encore une validation.

### Pseudo-code

```text
chercher les transactions en attente
les compter
retourner le nombre
```

### Fonction `calculerTauxValidation(int $total, int $validees)`

```php
private function calculerTauxValidation(int $total, int $validees): int
{
    return ($total > 0) ? round(($validees / $total) * 100) : 0;
}
```

### Explication ligne par ligne

- si `total > 0`, on peut faire une division.
- `($validees / $total) * 100` donne un pourcentage.
- `round(...)` arrondit le résultat.
- sinon, on renvoie 0 pour éviter de diviser par zéro.

### Pseudo-code

```text
si total est supérieur à zéro
    calculer le pourcentage validé
sinon
    retourner zéro
```

### Fonction `getBudgetTotal($db)`

```php
private function getBudgetTotal($db): float
{
    $query = $db->query(" 
        SELECT COALESCE(SUM(entree), 0) AS total
        FROM v_mouvements_financiers
        WHERE entree > 0
    ");
    return (float)($query->getRow()->total ?? 0);
}
```

### Explication simple

Elle calcule le budget total à partir de toutes les entrées positives.

### Pseudo-code

```text
additionner toutes les entrées
retourner le total
```

### Fonction `getTotalDepensesValidees($db)`

```php
private function getTotalDepensesValidees($db): float
{
    $query = $db->query(" 
        SELECT COALESCE(SUM(montant), 0) AS total
        FROM transaction
        WHERE statut_validation IN ('VALIDEE', 'AUTO_VALIDEE')
    ");
    return (float)($query->getRow()->total ?? 0);
}
```

### Explication simple

Elle calcule combien a été dépensé parmi les transactions validées.

### Pseudo-code

```text
additionner les montants validés
retourner le total
```

### Fonction `getBudgetVideResponse()`

```php
private function getBudgetVideResponse(): array
{
    return [
        'pourcentage' => 0,
        'totalDepenses' => 0,
        'budgetTotal' => 0,
        'reste' => 0,
        'niveauAlerte' => 'normal',
        'message' => 'Aucun capital initial enregistré'
    ];
}
```

### Explication simple

Quand il n'y a pas de budget, cette fonction retourne une réponse vide mais propre.

### Pseudo-code

```text
retourner des zéros
mettre le niveau d'alerte à normal
afficher un message d'absence de capital
```

### Fonction `calculerPourcentageBudget(float $depenses, float $budget)`

```php
private function calculerPourcentageBudget(float $depenses, float $budget): int
{
    return round(($depenses / $budget) * 100);
}
```

### Explication simple

Elle calcule la part du budget déjà utilisée.

### Pseudo-code

```text
diviser dépenses par budget
multiplier par 100
arrondir le résultat
retourner le pourcentage
```

### Fonction `determinerNiveauAlerte(int $pourcentage)`

```php
private function determinerNiveauAlerte(int $pourcentage): string
{
    if ($pourcentage >= 90) return 'critique';
    if ($pourcentage >= 75) return 'alerte';
    if ($pourcentage >= 50) return 'attention';
    return 'normal';
}
```

### Explication ligne par ligne

- si la consommation est très haute, on retourne `critique`.
- si elle est un peu moins haute, on retourne `alerte`.
- si elle est moyenne, on retourne `attention`.
- sinon, on retourne `normal`.

### Pseudo-code

```text
si pourcentage >= 90 alors critique
sinon si pourcentage >= 75 alors alerte
sinon si pourcentage >= 50 alors attention
sinon normal
```

### Fonction `getDepensesParMois($db, string $mois, string $annee)`

```php
private function getDepensesParMois($db, string $mois, string $annee): float
{
    $query = $db->query(" 
        SELECT COALESCE(SUM(montant), 0) AS total
        FROM transaction
        WHERE statut_validation IN ('VALIDEE', 'AUTO_VALIDEE')
        AND MONTH(date_transaction) = ?
        AND YEAR(date_transaction) = ?
    ", [$mois, $annee]);

    return (float)($query->getRow()->total ?? 0);
}
```

### Explication simple

Elle additionne les dépenses validées pour un mois et une année donnés.

### Pseudo-code

```text
prendre seulement les transactions validées
garder celles du mois demandé
garder celles de l'année demandée
additionner les montants
retourner le total
```

### Fonction `recupererTopCategorie($db)`

```php
private function recupererTopCategorie($db): ?object
{
    $query = $db->query(" 
        SELECT 
            categories_depense.nom,
            SUM(transaction.montant) as total
        FROM transaction
        JOIN depense ON depense.id_depense = transaction.id_depense
        JOIN categories_depense ON categories_depense.id_categorie = depense.id_categorie
        WHERE transaction.statut_validation IN ('VALIDEE', 'AUTO_VALIDEE')
        GROUP BY categories_depense.id_categorie
        ORDER BY total DESC
        LIMIT 1
    ");

    return $query->getRow();
}
```

### Explication simple

Cette fonction cherche la catégorie qui a reçu le plus d'argent dépensé.

### Pseudo-code

```text
relier transaction, dépense et catégorie
garder seulement les transactions validées
additionner par catégorie
trier du plus grand au plus petit
prendre la première catégorie
```

### Fonction `compterTransactionsParMois(string $mois, string $annee)`

```php
private function compterTransactionsParMois(string $mois, string $annee): int
{
    return $this->transactionModel
        ->where('MONTH(date_transaction)', $mois)
        ->where('YEAR(date_transaction)', $annee)
        ->countAllResults();
}
```

### Explication simple

Elle compte les transactions d'un mois précis.

### Pseudo-code

```text
filtrer par mois
filtrer par année
compter les lignes
retourner le nombre
```

### Fonction `recupererMouvementsFinanciers($db, int $limit)`

```php
private function recupererMouvementsFinanciers($db, int $limit): array
{
    $query = $db->query(" 
        SELECT 
            date_mouvement,
            origine,
            entree,
            sortie,
            (SELECT SUM(entree) - SUM(sortie) 
             FROM v_mouvements_financiers v2 
             WHERE v2.date_mouvement <= v1.date_mouvement) AS solde_cumule
        FROM v_mouvements_financiers v1
        ORDER BY date_mouvement DESC
        LIMIT ?
    ", [$limit]);

    return $query->getResultArray();
}
```

### Explication simple

Cette fonction récupère les derniers mouvements financiers et calcule pour chacun un solde cumulé.

### Pseudo-code

```text
prendre les derniers mouvements
pour chaque mouvement
    calculer le solde cumulé jusqu'à cette date
trier du plus récent au plus ancien
retourner la liste
```

### Fonction `getMontantTotalTransactions()`

```php
private function getMontantTotalTransactions(): float
{
    $query = $this->transactionModel->selectSum('montant')->first();
    return (float)($query['montant'] ?? 0);
}
```

### Explication simple

Elle additionne tous les montants de transaction.

### Pseudo-code

```text
additionner tous les montants
retourner la somme
```

### Fonction `calculerDepenseMoyenne(int $total, float $montantTotal)`

```php
private function calculerDepenseMoyenne(int $total, float $montantTotal): float
{
    return ($total > 0) ? round($montantTotal / $total) : 0;
}
```

### Explication simple

Elle divise la somme totale par le nombre de transactions pour avoir une moyenne.

### Pseudo-code

```text
si le nombre de transactions est supérieur à zéro
    diviser le total par ce nombre
sinon
    retourner 0
```

---

## 10) Méthodes privées de catégories

### Fonction `estTypeCategorieValide(string $type)`

```php
private function estTypeCategorieValide(string $type): bool
{
    $typesAutorises = ['fixe_mensuelle', 'fixe_ponctuelle', 'variable', 'note_frais'];
    return in_array($type, $typesAutorises);
}
```

### Explication simple

Elle vérifie si le type donné fait partie des types autorisés.

### Pseudo-code

```text
créer la liste des types permis
vérifier si le type demandé est dans la liste
retourner vrai ou faux
```

---

## 11) Méthodes privées de transactions

### Fonction `calculerMontantTotalValide(array $transactions)`

```php
private function calculerMontantTotalValide(array $transactions): int
{
    $total = 0;
    foreach ($transactions as $t) {
        if ($this->estTransactionValidee($t)) {
            $total += (int)$t['montant'];
        }
    }
    return $total;
}
```

### Explication ligne par ligne

- `$total = 0;` : on commence à zéro.
- `foreach` : on lit chaque transaction.
- `estTransactionValidee(...)` : on vérifie si elle doit compter.
- si oui, on ajoute son montant au total.
- on renvoie la somme.

### Pseudo-code

```text
mettre total à zéro
pour chaque transaction
    si elle est validée
        ajouter son montant au total
retourner total
```

### Fonction `estTransactionValidee(array $transaction)`

```php
private function estTransactionValidee(array $transaction): bool
{
    return $transaction['statut_validation'] === 'VALIDEE' || 
           $transaction['statut_validation'] === 'AUTO_VALIDEE';
}
```

### Explication simple

Elle dit qu'une transaction est validée si son statut est `VALIDEE` ou `AUTO_VALIDEE`.

### Pseudo-code

```text
si le statut est validée ou auto-validée
    retourner vrai
sinon
    retourner faux
```

### Fonction `extraireDatesTransactions(array $transactions)`

```php
private function extraireDatesTransactions(array $transactions): array
{
    $dates = [];
    foreach ($transactions as $t) {
        $dates[] = $t['date_transaction'];
    }
    return $dates;
}
```

### Explication simple

Elle prend toutes les dates des transactions et les met dans une liste.

### Pseudo-code

```text
créer une liste vide
pour chaque transaction
    ajouter sa date dans la liste
retourner la liste des dates
```

### Fonction `formaterDateMin(array $dates)`

```php
private function formaterDateMin(array $dates): string
{
    return !empty($dates) ? date('d/m/Y', strtotime(min($dates))) : date('d/m/Y');
}
```

### Explication simple

Elle retourne la date la plus ancienne, écrite dans un format lisible.

### Pseudo-code

```text
si la liste n'est pas vide
    prendre la plus petite date
    la reformater
sinon
    retourner la date du jour
```

### Fonction `formaterDateMax(array $dates)`

```php
private function formaterDateMax(array $dates): string
{
    return !empty($dates) ? date('d/m/Y', strtotime(max($dates))) : date('d/m/Y');
}
```

### Explication simple

Elle retourne la date la plus récente, écrite dans un format lisible.

### Pseudo-code

```text
si la liste n'est pas vide
    prendre la plus grande date
    la reformater
sinon
    retourner la date du jour
```

### Fonction `construireRequeteTransactions()`

```php
private function construireRequeteTransactions()
{
    return $this->transactionModel
        ->select('transaction.*, depense.libelle, depense.est_recurrent, depense.montant_defaut, depense.jour_echeance, categories_depense.nom as categorie_nom, categories_depense.type_parent')
        ->join('depense', 'depense.id_depense = transaction.id_depense')
        ->join('categories_depense', 'categories_depense.id_categorie = depense.id_categorie');
}
```

### Explication simple

Cette fonction prépare la base de la requête commune à plusieurs recherches de transactions.

### Pseudo-code

```text
préparer une requête avec les jointures utiles
retourner cette requête
```

### Fonction `appliquerFiltreType($builder, ?string $type)`

```php
private function appliquerFiltreType($builder, ?string $type): void
{
    if (empty($type) || $type === 'Tous') return;

    $typeMap = [
        'Fixe' => ['fixe_mensuelle', 'fixe_ponctuelle'],
        'Variable' => ['variable'],
        'Note' => ['note_frais']
    ];

    if (isset($typeMap[$type])) {
        $builder->whereIn('categories_depense.type_parent', $typeMap[$type]);
    }
}
```

### Explication ligne par ligne

- si le type est vide ou `Tous`, on ne filtre rien.
- `typeMap` associe un mot lisible à une ou plusieurs valeurs internes.
- si le type existe dans la carte,
- `whereIn(...)` garde seulement les catégories correspondant à ce type.

### Pseudo-code

```text
si aucun type ou type = Tous
    ne rien faire
sinon
    transformer le type affiché en type(s) de base
    filtrer la requête avec ces types
```

### Fonction `appliquerFiltreStatut($builder, ?string $statut)`

```php
private function appliquerFiltreStatut($builder, ?string $statut): void
{
    if (empty($statut) || $statut === 'Tous') return;

    $statutMap = [
        'Validé' => ['VALIDEE', 'AUTO_VALIDEE'],
        'En attente' => ['EN_ATTENTE'],
        'Rejeté' => ['REJETEE']
    ];

    if (isset($statutMap[$statut])) {
        $builder->whereIn('transaction.statut_validation', $statutMap[$statut]);
    }
}
```

### Explication simple

Elle transforme un statut lisible en statut technique de base de données.

### Pseudo-code

```text
si aucun statut ou statut = Tous
    ne rien faire
sinon
    chercher la traduction technique du statut
    filtrer la requête
```

### Fonction `appliquerFiltrePeriode($builder, ?string $periode)`

```php
private function appliquerFiltrePeriode($builder, ?string $periode): void
{
    if (empty($periode)) return;

    $now = new DateTime();

    switch ($periode) {
        case 'Ce mois':
            $builder->where('MONTH(transaction.date_transaction)', $now->format('m'));
            $builder->where('YEAR(transaction.date_transaction)', $now->format('Y'));
            break;
        case 'Dernier mois':
            $lastMonth = clone $now;
            $lastMonth->modify('-1 month');
            $builder->where('MONTH(transaction.date_transaction)', $lastMonth->format('m'));
            $builder->where('YEAR(transaction.date_transaction)', $lastMonth->format('Y'));
            break;
        case 'Cette année':
            $builder->where('YEAR(transaction.date_transaction)', $now->format('Y'));
            break;
    }
}
```

### Explication ligne par ligne

- si la période est vide, on ne filtre rien.
- on prend la date actuelle.
- `switch` choisit le bon cas selon le texte reçu.
- `Ce mois` : on filtre sur le mois et l'année actuels.
- `Dernier mois` : on recule d'un mois puis on filtre sur ce mois.
- `Cette année` : on filtre sur l'année en cours.

### Pseudo-code

```text
si la période est vide
    ne rien faire
sinon
    selon la période choisie
        filtrer sur le mois actuel
        ou le mois précédent
        ou l'année actuelle
```

### Fonction `executerRequeteTransactions($builder, int $page, int $perPage)`

```php
private function executerRequeteTransactions($builder, int $page, int $perPage): array
{
    $total = $builder->countAllResults(false);
    $offset = ($page - 1) * $perPage;

    $transactions = $builder
        ->orderBy('transaction.date_transaction', 'DESC')
        ->orderBy('transaction.id_transaction', 'DESC')
        ->limit($perPage, $offset)
        ->findAll();

    return [
        'transactions' => $transactions,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage,
        'totalPages' => ceil($total / $perPage)
    ];
}
```

### Explication ligne par ligne

- `countAllResults(false)` : compte les résultats sans casser la requête pour la suite.
- `$offset` : calcule combien de lignes sauter selon la page.
- `limit($perPage, $offset)` : prend seulement la page demandée.
- `ceil($total / $perPage)` : calcule le nombre total de pages en arrondissant vers le haut.

### Pseudo-code

```text
compter tous les résultats
calculer l'offset de la page
prendre seulement les lignes de la page
retourner la liste et les infos de pagination
```

### Fonction `transactionExiste(int $idTransaction)`

```php
private function transactionExiste(int $idTransaction): bool
{
    return (bool)$this->transactionModel->find($idTransaction);
}
```

### Explication simple

Elle vérifie si une transaction existe dans la base.

### Pseudo-code

```text
chercher la transaction par son id
retourner vrai si elle existe
sinon faux
```

### Fonction `mettreAJourTransaction(int $idTransaction, array $data)`

```php
private function mettreAJourTransaction(int $idTransaction, array $data): void
{
    $this->transactionModel->update($idTransaction, [
        'montant' => $data['montant'],
        'date_transaction' => $data['date_transaction']
    ]);
}
```

### Explication simple

Elle met à jour le montant et la date de la transaction.

### Pseudo-code

```text
prendre le nouvel argent et la nouvelle date
mettre à jour la transaction
```

### Fonction `mettreAJourDepense(int $idDepense, array $data)`

```php
private function mettreAJourDepense(int $idDepense, array $data): void
{
    $this->depenseModel->update($idDepense, [
        'libelle' => $data['libelle'],
        'id_categorie' => $data['id_categorie']
    ]);
}
```

### Explication simple

Elle met à jour le nom de la dépense et sa catégorie.

### Pseudo-code

```text
prendre le nouveau libellé et la nouvelle catégorie
mettre à jour la dépense
```

---

## 12) Méthodes privées de dépenses

### Fonction `insererDepense(array $data, bool $estRecurrent)`

```php
private function insererDepense(array $data, bool $estRecurrent): int
{
    $depenseData = [
        'libelle' => $data['libelle'],
        'id_categorie' => $data['id_categorie'],
        'est_recurrent' => $estRecurrent,
        'jour_echeance' => $estRecurrent ? $data['jour_echeance'] : null,
        'montant_defaut' => $estRecurrent ? $data['montant'] : null,
    ];

    return (int)$this->depenseModel->insert($depenseData);
}
```

### Explication ligne par ligne

- on prépare un tableau avec les données à enregistrer.
- `jour_echeance` n'est rempli que si la dépense est récurrente.
- `montant_defaut` n'est aussi rempli que pour une dépense récurrente.
- `insert(...)` ajoute la dépense dans la base.
- `(int)` transforme l'identifiant obtenu en entier.

### Pseudo-code

```text
préparer les données de dépense
si récurrente
    garder le jour d'échéance et le montant par défaut
sinon
    mettre ces champs à vide
enregistrer la dépense
retourner l'identifiant créé
```

### Fonction `enregistrerDepenseRecurrente(array $data)`

```php
private function enregistrerDepenseRecurrente(array $data): string
{
    return 'Dépense récurrente enregistrée avec succès. Les transactions seront créées automatiquement le ' . $data['jour_echeance'] . ' de chaque mois.';
}
```

### Explication simple

Cette fonction ne crée pas une transaction tout de suite. Elle prépare juste un message de succès pour une dépense qui reviendra chaque mois.

### Pseudo-code

```text
construire un message expliquant que la dépense sera automatique chaque mois
retourner ce message
```

### Fonction `enregistrerDepenseNonRecurrente(int $idDepense, array $data)`

```php
private function enregistrerDepenseNonRecurrente(int $idDepense, array $data): string
{
    $transactionData = [
        'id_depense' => $idDepense,
        'montant' => $data['montant'],
        'date_transaction' => $data['date_transaction'],
        'statut_validation' => 'EN_ATTENTE',
        'source_type' => 'MANUELLE',
        'justificatif' => null,
    ];

    $idTransaction = $this->transactionModel->insert($transactionData);

    if (!$idTransaction) {
        throw new Exception('Erreur lors de l\'enregistrement de la transaction');
    }

    return 'Dépense enregistrée avec succès (Statut: EN_ATTENTE)';
}
```

### Explication ligne par ligne

- on prépare les données de transaction.
- `statut_validation` commence à `EN_ATTENTE`.
- `source_type` indique que la saisie vient d'un humain.
- `justificatif` est mis à `null`.
- on insère la transaction dans la base.
- si l'insertion échoue, on lance une erreur.
- sinon, on renvoie un message de succès.

### Pseudo-code

```text
préparer la transaction liée à la dépense
mettre le statut en attente
enregistrer la transaction
si erreur, arrêter
sinon retourner le message de succès
```

---

## Résumé simple du fonctionnement de tout le service

Ce service prend les données de la base, les trie, les filtre, les additionne et les transforme en informations faciles à utiliser pour l'interface. Il sert surtout à éviter de mélanger les règles de calcul avec le reste du projet. Les contrôleurs peuvent appeler ce service pour obtenir directement des réponses prêtes à afficher.

## Remarque importante

Le fichier à expliquer dans ce projet est `app/Services/FinanceService.php`, pas `FinanceService.java`. Le projet est organisé en PHP avec CodeIgniter, donc toute la logique décrite ici correspond bien au fichier réellement présent.