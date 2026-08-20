# Plateforme d'Inscription Administrative Universitaire — Architecture

## Stack

- Laravel 13 (PHP 8.3), MySQL 8
- Authentification : Laravel Breeze (Blade)
- Front : Bootstrap 5, HTML/CSS/JavaScript
- Import Excel/CSV : `maatwebsite/excel`
- Reçu PDF : `barryvdh/laravel-dompdf`
- QR Code : `simplesoftwareio/simple-qrcode`

## Modèle de données

```
users (id, name, email, telephone, password, role[admin|etudiant])
  └─ 1-1 students.user_id
  └─ 1-N notifications.user_id
  └─ 1-N registration_histories.user_id

filieres (id, nom, code)          ──< students.filiere_id
niveaux (id, nom, ordre)          ──< students.niveau_id
academic_years (id, nom, actif)   ──< registrations.academic_year_id

students (id, ine UNIQUE, nom, prenom, email, telephone, filiere_id,
          niveau_id, promotion, user_id, date_naissance, lieu_naissance,
          sexe, nationalite, adresse)
  └─ 1-N registrations

registrations (id, student_id, academic_year_id, numero_inscription UNIQUE,
               statut, date_soumission, date_validation, commentaire_admin)
  └─ 1-N registration_histories

notifications (id, user_id, titre, message, lu)
```

Contraintes clés :

- `students.ine` unique → un INE ne peut correspondre qu'à un seul dossier étudiant.
- `students.user_id` unique → un INE ne peut être rattaché qu'à un seul compte.
- `registrations (student_id, academic_year_id)` unique → une seule demande par année académique.

## Statuts d'inscription

`en_attente` → `en_cours_verification` → `validee` | `rejetee` | `correction_demandee`

Après une `correction_demandee`, l'étudiant corrige son dossier et resoumet (`en_attente`).

## Arborescence cible

```
app/
  Exports/StudentsTemplateExport.php     modèle CSV/Excel à télécharger
  Http/
    Controllers/
      Admin/                             DashboardController, StudentController,
                                         StudentImportController, FiliereController,
                                         NiveauController, AcademicYearController,
                                         RegistrationController, StatistiqueController
      Auth/                              contrôleurs Breeze + IneVerificationController
      Student/                           DashboardController, ProfileController,
                                         RegistrationController, NotificationController,
                                         ReceiptController
      PublicPageController.php           accueil, présentation, comment ça marche
    Middleware/EnsureUserIsAdmin.php
    Middleware/EnsureUserIsStudent.php
    Requests/                            FormRequests de validation
  Imports/StudentsImport.php
  Models/                                User, Student, Filiere, Niveau,
                                         AcademicYear, Registration,
                                         RegistrationHistory, Notification
  Services/                              IneVerificationService, RegistrationService,
                                         NotificationService, ReceiptService
database/
  migrations/                            schéma ci-dessus
  seeders/                               Filiere, Niveau, AcademicYear, Admin, Student
resources/views/
  layouts/                               app (public), student, admin
  public/                                accueil, presentation, comment-ca-marche
  auth/                                  verification INE, inscription, connexion
  student/                               dashboard, profil, inscription, notifications
  admin/                                 dashboard, etudiants, import, filieres,
                                         niveaux, annees, inscriptions, statistiques
  receipts/                              reçu PDF
routes/web.php                           routes publiques, /etudiant, /admin
```

## Sécurité

- Mots de passe hachés (cast `hashed`), sessions Laravel, protection CSRF.
- Middlewares de rôle : `EnsureUserIsAdmin`, `EnsureUserIsStudent`.
- Un étudiant n'accède qu'à ses propres données (scoping par `auth()->user()->student`).
- Validation systématique via FormRequests.

## Étapes de développement

1. **(fait)** Projet Laravel, MySQL, migrations, modèles, relations, seeders.
2. Authentification Breeze + interface Bootstrap 5 + middlewares de rôle.
3. Vérification de l'INE avant création de compte.
4. Espace étudiant (dossier, statut, historique).
5. Espace administrateur (gestion des inscriptions, filières, niveaux, années).
6. Import CSV/Excel des étudiants + modèle de fichier.
7. Notifications.
8. Reçu PDF, numéro unique d'inscription et QR Code de vérification.
