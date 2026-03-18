# Project Context - MediCare Pro (CRM Hospital V2)

## Project Overview
**MediCare Pro** is a hospital management CRM built with Laravel 9. It manages the complete patient journey from reception to billing, including medical consultations, pharmacy, and administration.

## Tech Stack
- **Backend**: Laravel 9 (PHP 8.4)
- **Frontend**: Blade templates, Tailwind CSS, Alpine.js, Vite
- **Database**: MySQL (via MAMP, port 8889)
- **Auth**: Laravel Breeze

## Architecture
- Monolithic MVC (Laravel)
- Role-based access: admin, reception, medecin, caisse, pharmacie
- Custom CSS framework (public/css/style.css) - no Bootstrap

## Modules

### 1. Reception
- Patient registration (CRUD)
- Consultation management
- Queue management (file d'attente)
- Invoice forwarding

### 2. Medecin (Doctor)
- Patient queue view
- Consultation workflow (start, notes, complete)
- Treatment sheets (fiches de traitement)
- Prescriptions/Ordonnances

### 3. Caisse (Cashier)
- Invoice management
- Payment processing (especes, mobile money, carte, virement)
- Daily journal
- Transaction history

### 4. Pharmacie (Pharmacy)
- Medication inventory (CRUD)
- Stock movements (in/out)
- Dispensation of prescriptions
- Stock alerts (low stock, rupture)
- Supply orders (approvisionnements)

### 5. Administration
- Doctor management (with photo upload)
- Planning/scheduling
- Hospitalization management (room assignment)
- Financial oversight

## Database Schema (24 tables)
- users, patients, medecins, chambres
- medicaments, actes_medicaux
- dossiers_medicaux, consultations
- hospitalisations, rendezvous
- file_attente, planning
- fiches_traitement, fiche_traitement_actes
- ordonnances, ordonnance_medicaments
- prescriptions
- fiches_approvisionnement, approvisionnement_lignes
- mouvements_stock
- factures, facture_lignes
- paiements, transactions

## UI/UX Patterns
- Custom modal system (modal-overlay + modal classes)
- Searchable selects (auto-converted for >4 options)
- table-patients class for styled tables (dark header, zebra, hover)
- Stat cards with colored left border
- Action cards with gradient backgrounds
- Autocomplete inputs for patient/doctor selection
- Avatar with photo upload capability (doctors)

## Current State
- All modules functional with seeded test data
- Modern UI redesign completed across all views
- BMAD framework installed for AI-driven development

## Communication Language
French (application and user interface)
