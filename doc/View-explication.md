Voici l’utilité des **4 views SQL** en bref 👇

---

# 📊 1. `v_mouvements_financiers`

👉 Sert à voir **tout l’argent qui entre et sort**

✔ capital
✔ paiements clients
✔ dépenses
✔ achats stock

➡️ = tableau global de la **trésorerie**

---

# 📦 2. `v_stock_produits`

👉 Sert à voir le **stock actuel de chaque produit**

✔ stock restant
✔ stock minimum
✔ état (ok / critique / rupture)

➡️ = suivi du stock en temps réel

---

# 🧾 3. `v_reservations_details`

👉 Sert à voir **une réservation complète**

✔ client
✔ packs commandés
✔ total à payer
✔ montant payé
✔ reste à payer
✔ statut récupération

➡️ = résumé complet d’une commande

---

# 🚨 4. `v_alertes_stock`

👉 Sert à voir **uniquement les problèmes de stock**

✔ produits en rupture
✔ produits critiques
✔ quantités manquantes

➡️ = tableau d’alerte pour agir vite

---

# ⚡ Résumé ultra simple

* 💰 finances → `v_mouvements_financiers`
* 📦 stock → `v_stock_produits`
* 🧾 commandes → `v_reservations_details`
* 🚨 alertes → `v_alertes_stock`
