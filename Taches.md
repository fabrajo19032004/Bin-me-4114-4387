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

    - Création de Taches.md (binôme)
        - Lister les tâches du projet (ok)
        - Répartir les tâches entre les deux étudiants (ok)
        - Mettre à jour le fichier après chaque livraison (ok)


2. Côté Opérateur (En cours) (4387)

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

    - Connexion (ok)
        - Connexion avec le numéro de téléphone (ok)
        - Vérifier que le préfixe est autorisé (ok)
        - Créer automatiquement le client s'il n'existe pas (ok)

    - Gestion du compte (ok)
        - Consulter le solde (ok)

    - Dépôt (ok)
        - Effectuer un dépôt (ok)
        - Mettre à jour le solde (ok)
        - Enregistrer la transaction (ok)

    - Retrait (ok)
        - Vérifier le solde disponible (ok)
        - Calculer les frais (ok)
        - Déduire le montant et les frais (ok)
        - Enregistrer la transaction (ok)

    - Transfert
        - Vérifier le destinataire (ok)
        - Vérifier le solde (ok)
        - Calculer les frais (ok)
        - Débiter l'expéditeur (ok)
        - Créditer le destinataire (ok)
        - Enregistrer la transaction (ok)

    - Historique
        - Afficher toutes les transactions du client (ok)



5. Développement (4387-4114)

    - Créer les Models (ok)
    - Créer les Controllers (ok)
    - Créer les Views (ok)
    - Mettre en place les routes (ok)
    - Ajouter les validations des formulaires (ok)
    - Gérer les messages de succès et d'erreur (ok)



6. Interface 

    - Créer la page de connexion (ok)
    - Créer le Dashboard Opérateur (ok)
    - Créer le Dashboard Client (ok)
    - Créer les pages CRUD (ok)
    - Adapter l'interface avec Bootstrap (non fini)
    - Vérifier le responsive   (ok)  


-- Version 2:

1. Base de données (En cours)

    - Modifier les tables existantes si nécessaire
        - Ajouter les nouveaux préfixes des autres opérateurs
        - Ajouter le pourcentage de commission pour les transferts inter-opérateurs
        - Mettre à jour base.sql

2. Côté Opérateur (4387)

    - Gestion des préfixes  (ok)
        - Ajouter les préfixes des autres opérateurs (031, 032...) (ok)
        - Modifier les préfixes (ok)
        - Supprimer les préfixes (ok)
        - Identifier l'opérateur associé à chaque préfixe (ok)

    - Configuration des commissions (ok)
        - Ajouter un pourcentage de commission (ok)
        - Modifier le pourcentage (ok)
        - Appliquer la commission uniquement pour les transferts vers les autres opérateurs (ok)

    - Situation des gains (ok)
        - Séparer les gains des transferts internes (ok)
        - Séparer les gains des transferts vers les autres opérateurs (ok)
        - Afficher les frais de retrait (ok)
        - Afficher les frais de transfert (ok)

    - Situation des montants à envoyer (ok)
        - Calculer les montants destinés à chaque opérateur (ok)
        - Afficher le total par opérateur (ok)
        - Générer un récapitulatif des montants (ok)


3. Côté Client (4114)

    - Transfert avec frais de retrait (ok)
        - Ajouter l'option "Inclure les frais de retrait" (ok)
        - Calculer automatiquement le montant total à débiter (ok)
        - Mettre à jour le solde (ok)
        - Enregistrer la transaction (ok)

    - Envoi multiple (ok)
        - Permettre la saisie de plusieurs numéros (ok)
        - Vérifier les numéros destinataires (ok)
        - Diviser automatiquement le montant entre les bénéficiaires (ok)
        - Calculer les frais correspondants (ok)
        - Effectuer tous les transferts (ok)
        - Enregistrer toutes les transactions (ok)