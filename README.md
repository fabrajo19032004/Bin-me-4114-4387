# CodeIgniter 4 Framework

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds the distributable version of the framework.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Contributing

We welcome contributions from the community.

Please read the [*Contributing to CodeIgniter*](https://github.com/codeigniter4/CodeIgniter4/blob/develop/CONTRIBUTING.md) section in the development repository.

## Server Requirements

PHP version 8.2 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - The end of life date for PHP 8.1 was December 31, 2025.
> - If you are still using below PHP 8.2, you should upgrade immediately.
> - The end of life date for PHP 8.2 will be December 31, 2026.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

# Lancement de l'application
php spark serve 

# Création de nos migrations !!! NE PAS FAIRE !!!
php spark make:migration CreateUsersTable
php spark make:migration CreatePacksTable
php spark make:migration CreateArchivesTable
php spark make:migration CreateClientsTable
php spark make:migration CreatePackProduitsTable
php spark make:migration CreateCategoriesPackTable
php spark make:migration CreateReservationsTable
php spark make:migration CreateReservationPackTable
php spark make:migration CreatePaiementsTable
php spark make:migration CreateRecuperationsTable
php spark make:migration CreateCapitalsTable
php spark make:migration CreateCategoriesDepenseTable
php spark make:migration CreateDepenseTable
php spark make:migration CreateTransactionTable
php spark make:migration CreateCategorieProductTable
php spark make:migration CreateProduitTable
php spark make:migration CreateEntreeStockTable
php spark make:migration CreateSortieStockTable
php spark make:migration CreateDetailSortieTable
php spark make:migration CreateDatabaseViews
php spark make:migration AddRecurringExpenseEvent
php spark make:migration SeedCapitalInitial
php spark make:migration AddPurchaseTransactionsEvent
php spark make:migration AddRecurringExpenseTrigger
php spark make:migration CreatePaiementTrigger
php spark make:migration CreatePaiementTrigger2

# Migration
php spark migrate

# Refresh migration 
php spark migrate:refresh

# Création de nos seeder !!! NE PAS FAIRE !!!
php spark make:seeder MainSeeder 
php spark make:seeder CategorieProductSeeder 
php spark make:seeder ProductSeeder 
php spark make:seeder CategoriesPackSeeder 
php spark make:seeder PackSeeder 
php spark make:seeder PackProductSeeder 
php spark make:seeder TestDataSeeder
php spark make:seeder StockSeeder
php spark make:seeder DonneeSeeder

# Insértion de donnée dans nos base de donnée
# Ancient
php spark db:seed MainSeeder
php spark db:seed CategorieProductSeeder 
php spark db:seed ProductSeeder 
php spark db:seed CategoriesPackSeeder 
php spark db:seed PackSeeder 
php spark db:seed PackProductSeeder 
php spark db:seed TestDataSeeder
php spark db:seed StockSeeder

# Nouveau
php spark db:seed MainSeeder
php spark db:seed DonneeSeeder

# Creation de nos model !!! NE PAS FAIRE !!!
php spark make:model UserModel
php spark make:model CategoriesPackModel
php spark make:model ArchiveModel