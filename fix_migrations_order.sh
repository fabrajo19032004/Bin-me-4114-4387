#!/bin/bash

echo "🔧 Réorganisation des migrations CodeIgniter..."

cd app/Database/Migrations || exit

# =========================
# BASE CATEGORIE + PRODUIT
# =========================
mv 2026-06-22-111427_CreateCategorieProductTable.php 2026-06-22-110100_CreateCategorieProductTable.php 2>/dev/null

mv 2026-06-22-111200_CreateProduitTable.php 2026-06-22-110200_CreateProduitTable.php 2>/dev/null

# =========================
# PACKS
# =========================
mv 2026-06-22-110513_CreatePacksTable.php 2026-06-22-110300_CreatePacksTable.php 2>/dev/null

mv 2026-06-22-111600_CreatePackProduitsTable.php 2026-06-22-110400_CreatePackProduitsTable.php 2>/dev/null

# =========================
# CLIENTS / RESERVATIONS
# =========================
mv 2026-06-22-110747_CreateClientsTable.php 2026-06-22-110500_CreateClientsTable.php 2>/dev/null

mv 2026-06-22-111249_CreateReservationsTable.php 2026-06-22-110600_CreateReservationsTable.php 2>/dev/null

mv 2026-06-22-111323_CreateReservationPackTable.php 2026-06-22-110700_CreateReservationPackTable.php 2>/dev/null

mv 2026-06-22-111352_CreatePaiementsTable.php 2026-06-22-110800_CreatePaiementsTable.php 2>/dev/null

mv 2026-06-22-111359_CreateRecuperationsTable.php 2026-06-22-110900_CreateRecuperationsTable.php 2>/dev/null

# =========================
# FINANCE
# =========================
mv 2026-06-22-111403_CreateCapitalsTable.php 2026-06-22-111000_CreateCapitalsTable.php 2>/dev/null

mv 2026-06-22-111409_CreateCategoriesDepenseTable.php 2026-06-22-111100_CreateCategoriesDepenseTable.php 2>/dev/null

mv 2026-06-22-111415_CreateDepenseTable.php 2026-06-22-111200_CreateDepenseTable.php 2>/dev/null

mv 2026-06-22-111420_CreateTransactionTable.php 2026-06-22-111300_CreateTransactionTable.php 2>/dev/null

# =========================
# STOCK
# =========================
mv 2026-06-22-111437_CreateEntreeStockTable.php 2026-06-22-111400_CreateEntreeStockTable.php 2>/dev/null

mv 2026-06-22-111442_CreateSortieStockTable.php 2026-06-22-111500_CreateSortieStockTable.php 2>/dev/null

mv 2026-06-22-111447_CreateDetailSortieTable.php 2026-06-22-111600_CreateDetailSortieTable.php 2>/dev/null

# =========================
# ARCHIVES
# =========================
mv 2026-06-22-111700_CreateArchivesTable.php 2026-06-22-111700_CreateArchivesTable.php 2>/dev/null

echo "✅ Réorganisation terminée"
echo "👉 Lance maintenant : php spark migrate:refresh"
