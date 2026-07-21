TODO Liste - Version 1 (4114-4387)

1. Initialisation
    - Création du projet CodeIgniter 4 (4114)
        - Installer CodeIgniter 4 (pour 4114) (ok)
        - Vérifier le bon fonctionnement du projet: (ok)
            -Test avec projet existant (ok)
        - Étudier les anciens cours et PDF de Monsieur :(ok)
            -Pour mise en place et révision codegniter (ok)

    - Configuration SQLite (4114)
        - Installer/activer SQLite (Pour 4114-4387) (ok)
        - Intégrer SQLite dans le projet (4114) (ok)
        - Configurer la connexion à la base de données (ok)

    - Initialisation Git (4387)
        - Créer un dépôt GitHub public (ok)
        - Initialiser Git (ok)
        - Envoyer la structure du projet (ok)
        - Inviter le binôme  (ok)

    - Création de base.sql (4387)
        - Créer le fichier à la racine du projet (ok)
        - Ajouter  les scripts de création des tables (ok)
        - Ajouter les données nécessaires (si besoin) (ok)

    - Création de Taches.md (4114-4387)
        - Lister les tâches du projet (ok)
        - Répartir les tâches entre les deux étudiants (ok)
        - Mettre à jour le fichier après chaque livraison (ok)

2. Côté Opérateur (4387)

    - Gestion des préfixes
        - Ajouter un préfixe (033, 037...)
        - Modifier un préfixe
        - Supprimer un préfixe
        - Lister les préfixes

    - Gestion des types d'opérations
        - Ajouter un dépôt
        - Ajouter un retrait
        - Ajouter un transfert
        - Modifier un type d'opération

    - Gestion des barèmes de frais
        - Ajouter une tranche de frais
        - Modifier une tranche
        - Supprimer une tranche
        - Calculer automatiquement les frais selon le montant

    - Consultation
        - Afficher les gains sur les retraits
        - Afficher les gains sur les transferts
        - Afficher la situation des comptes clients

3. Base de données ( 4114)

    - Créer la table clients (ok)
    - Créer la table operateurs (ok)
    - Créer la table prefixes (ok)
    - Créer la table types_operations (ok)
    - Créer la table baremes_frais (ok)
    - Créer la table transactions (ok)

    - Définir les clés primaires (ok)
    - Définir les clés étrangères (ok)
    - Vérifier les relations entre les tables (ok)

4. Côté Client (4114)

    - Connexion
        - Connexion avec le numéro de téléphone
        - Vérifier que le préfixe est autorisé
        - Créer automatiquement le client s'il n'existe pas

    - Gestion du compte
        - Consulter le solde

    - Dépôt
        - Effectuer un dépôt
        - Mettre à jour le solde
        - Enregistrer la transaction

    - Retrait
        - Vérifier le solde disponible
        - Calculer les frais
        - Déduire le montant et les frais
        - Enregistrer la transaction

    - Transfert
        - Vérifier le destinataire
        - Vérifier le solde
        - Calculer les frais
        - Débiter l'expéditeur
        - Créditer le destinataire
        - Enregistrer la transaction

    - Historique
        - Afficher toutes les transactions du client

5. Développement (4387-4114)

    - Créer les Models
    - Créer les Controllers
    - Créer les Views
    - Mettre en place les routes
    - Ajouter les validations des formulaires
    - Gérer les messages de succès et d'erreur

6. Interface

    - Créer la page de connexion
    - Créer le Dashboard Opérateur
    - Créer le Dashboard Client
    - Créer les pages CRUD
    - Adapter l'interface avec Bootstrap (non fini)
    - Vérifier le responsive
