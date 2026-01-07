# URL Monitor
> Fournit un rapport journalier, hebdomadaire ou mensuel pour une URL donnée.

```bash
git clone git@github.com:julien-gassmann/url-monitor.git
```

---

## Quick Demo (TL;DR)

1. Cloner le repository :
```bash
git clone git@github.com:julien-gassmann/url-monitor.git
```

2. Copier/coller les fichiers d'env :
```bash
cp backend/.env.example backend/.env && cp frontend/.env.example frontend/.env
```

3. Dans `/backend/.env`, remplacer les variables existantes par :
```dotenv
# Config SMPT Mailtrap
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=

TIME_TRAVELLER_MODE_ENABLED=true
MAIL_SENDING_ENABLED=false
```

4. Lancer l'installation et le serveur Next :
```bash
make setup && make front-dev
```

5. Créer une surveillance :
- Se rendre sur `http://localhost:8080`
- Remplir le formulaire avec :
  - URL : `http://host.docker.internal:8080/api/ping`
  - Code HTTP : 200
  - Fréquence : Daily

6. Se préparer un bon chocolat chaud ou autre boisson chaude réconfortante (1min = 1surveillance)

7. Dans `/backend/.env`, rechanger les variables suivantes par :
```dotenv
TIME_TRAVELLER_MODE_ENABLED=false
MAIL_SENDING_ENABLED=true
```

8. Relancer la stack docker :
```bash
make up && make restart
```

9. Attendre une minute max et consulter la boîte Mailtrap → Cliquer sur le lien Consulter du dernier mail reçu.

---

## Configuration

Pour configurer l'application, copier/coller les fichiers `/backend/.env.example` et `/frontend/.env.example` 
en `/backend/.env` et `/frontend/.env`.

```bash
cp backend/.env.example backend/.env && cp frontend/.env.example frontend/.env
```

### 1. Boîte mail SMTP (requis)

L'application reposant principalement sur l'envoi de mail,
il est nécessaire de configurer une boîte mail SMTP valide en remplaçant
les variables suivantes situées dans le fichier `/backend/.env` :

```dotenv
# Config SMPT valide
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```

### 2. Ports réseaux (optionnel)

Par défaut, les ports utilisés pour ce projet sont les suivants :
- `3306` : Serveur MariaDB
- `3307` : Serveur MariaDB dédié aux tests
- `6379` : Serveur Redis
- `8080` : Serveur Nginx

Si l'un de ces ports est déjà utilisé par un autre processus sur votre machine,
vous pouvez les changer directement dans le `docker-compose.yml`.

Pour le port du serveur Nginx, changez également les variables d'environnements :
- `NEXT_PUBLIC_API_URL` situé dans le fichier `/frontend/.env`
- `FRONT_BASE_URL` situé dans le fichier `/backend/.env`

### 3. Bearer token Sanctum (optionnel)

Par défaut, le bearer token créé par Sanctum après vérification du token d'accès est configuré avec une validité de 30 minutes :

```dotenv
# Valeur en secondes
SANCTUM_TTL=1800
```

### 4. Outils de Dev / Démos (conseillé)

L'application fourni quelques outils pour faciliter le debugging et les démonstrations :

#### Garder accès au token de vérification

```dotenv
KEEP_ACCESS_TOKEN_IN_CACHE=false
```

Par défaut cette valeur est à `false`.  
Elle permet de retourner le token de vérification en clair dans les réponses des endpoints API `tokens.verify` et `tokens.refresh`.

Utile pour le développement, notamment couplé à l'environnement Bruno fourni avec le repo.

#### Accélérer la fréquence de vérification

```dotenv
TIME_TRAVELLER_MODE_ENABLED=false
```

Par défaut cette valeur est à `false`.  
Elle permet de modifier la planification des prochaines vérifications de la manière suivante :
- `Daily` -> toutes les minutes.
- `Weekly` -> toutes les 2 minutes.
- `Monthly` -> toutes les 5 minutes.

Utile pour le développement et les démonstrations.

> [!TIP]
> Je recommande chaudement d'activer cette fonction pour les démonstrations.  
> Attention cependant à ne pas la laisser active trop longtemps (plusieurs heures) pour éviter l'envoi excessif de mail.

#### Activation / Désactivation de l'envoi de mail

```dotenv
# Envoi de mail actif par défaut
MAIL_SENDING_ENABLED=true
```

Par défaut cette valeur est à `true`.  
Elle permet d'activer ou non l'envoi de mail.

Utile pour le développement.

> [!IMPORTANT]
> Après l'installation, toute modification dans la configuration nécessite de relancer les containers Docker pour qu'elle soit effective.  
> Pour cela lancer `make up` puis `make restart` à la racine du projet.

---

## Installation

### Pré-requis

Par souci de simplicité, la documentation ci-dessous suppose que Docker et Docker Desktop sont installés sur votre machine.

### Lancer l'installation

```bash
make setup
```

### Lancer les tests (optionnel)

```bash
make check
```

### Lancer le serveur Next

```bash
make front-dev
```

### Utiliser l'application

Vous pouvez maintenant vous rendre sur [http://localhost:8080](http://localhost:8080) (port par défaut).

> [!NOTE]
> Le premier chargement de la page peut être un peu long (~10s).

L'API Laravel dispose d'un endpoint dédié aux essais.  
Pour le surveiller, renseignez le champ URL du formulaire avec : `http://host.docker.internal:8080/api/ping`.  

Cette endpoint retournera à chaque ping une réponse aléatoire avec les probabilités suivantes :
- 80% de chance de retourner un statut 200 (UP)
- 15% de chance de retourner un autre code aléatoirement (DOWN)
- 5% de chance de répondre au-delà du timeout (UNREACHABLE)

### Optionnel : Dossier Bruno

**Bruno** est un client HTTP/API gratuit et open-source alternatif à **Postman** ou **Insomnia**.

Le projet fournit un dossier `/documents/Bruno` contenant tous les endpoints, variables et scripts nécessaires pour faciliter le développement avec l’application desktop **Bruno**.

---

## Architecture et choix techniques

### Diagramme ER

Le diagramme d'entité-relation est disponible [ici](documents/UML/diagram_entity_relation.puml).  
Il représente la structure de la base de données relationnelles

### Infrastructure (Docker)

Ce projet fournit une stack Docker complète définie dans le `docker-compose.yml` 
et orchestrée par un fichier `Makefile`.

La stack est constituée des containers suivants :
- `frontend` : permet de faire tourner l'application Next.
- `backend` : permet de faire tourner l'application Laravel.
- `mjml` : permet de générer des vues `*.blade.php` à partir des fichiers `*.mjml`.
- `scheduler` : exécute `php artisan monitors:checks` chaque minute pour planifier les jobs périodiques.
- `worker` : queue worker Laravel pour exécuter les jobs de vérifications planifiées.
- `worker_instant` : queue worker Laravel pour exécuter les jobs créés immédiatement lors de la création de nouvelles vérifications.
- `nginx` : reverse proxy, intercepte les requêtes sur le port `8080` et les redirige vers le container approprié (`backend` ou `frontend`). 
- `db` : base de données MariaDB principale pour l'application Laravel. 
- `db-test` : base de données MariaDB dédiée aux tests de l'application Laravel. 
- `redis` : base de données clé/valeur utilisée pour stocker les jobs en attente (queues Laravel) et pour la mise en cache des tokens d'accès lorsque l'outil est activé.


### Backend (Laravel)

#### Choix de design

L'application Laravel est basée sur l'action pattern. Chaque endpoint suit donc le cycle suivant : 

```
FormRequest
 └─ authorize() -> valide la permission
 └─ validate() -> valide les données
Controller
 └─ __invoke() -> délègue à l’Action
      Action
      └─ handle() -> exécute la logique métier
 └─ retourne la réponse
```

#### Fonctionnement général

Pour assurer la confidentialité, l'application suit le processus suivant :

1. Création d’un nouveau monitor
2. Exécution immédiate de la première vérification
3. Génération d’un token d’accès de 5 min
4. Envoi d’un mail contenant :
    - Lien “Consulter” → Si utilisé dans 5 min → création d’un bearer token Sanctum (30 min)
    - Lien “Renouveler” → Invalide les anciens tokens et renvoie un nouveau mail

De cette façon les liens reçus dans chaque mail sont à usage unique
et l'accès aux résultats implique d'avoir eu un accès à la boîte mail dans les 5 dernières minutes.  
Cela garantit qu’un lien intercepté ou récupéré plus tard ne peut pas être utilisé.


### Frontend (Next.js)

#### Choix de design

L'application Next.js est organisée autour de pages et de composants réutilisables.  
Le front consomme les endpoints API exposés par le backend Laravel et gère :
- L'affichage des surveillances paginées et triables.
- La saisie des URLs à surveiller via un formulaire avec validation dynamique.
- La navigation sécurisée basée sur les tokens d'accès envoyés par mail.

#### Fonctionnement général

1. L'utilisateur consulte une surveillance via le lien envoyé par mail.
2. La page front effectue une requête vers l'API pour vérifier le token d'accès.
3. En fonction de la réponse, elle affiche les résultats de la surveillance ou un message indiquant que le token d'accès est invalide ou expiré.
4. Pour les vérifications et mises à jour, le front utilise des hooks centralisés afin de gérer les appels API, l'état de chargement et les erreurs de manière cohérente.

---

## Commandes utiles

> [!TIP]
> Les commandes suivantes et d'autres sont détaillées dans le fichier `Makefile`.


### Général 

Lister toutes les commandes `make` disponibles avec leur description :
```bash
make help
```

Raccourci pour `docker compose up -d` :
```bash
make up
```

Installer toute l'application (backend + frontend) :
```bash
make setup
```

Supprimer toute l'application (containers + volumes + vendor + node modules) :
```bash
make clean
```

Supprime puis réinstalle entièrement l'application (`make clean` + `make setup`) :
```bash
make rebuild
```

### Backend

Intéragir avec le container Laravel (`docker compose exec backend bash`) :
```bash
make back-console
```

Exécuter les migrations (`docker compose exec backend php artisan migrate --force`) :
```bash
make migrate
```

Rafraichir la base de données (`docker compose exec backend php artisan migrate:fresh`) :
```bash
make db-fresh
```

Générer les vues Blade à partir des vues MJML :
```bash
make emails
```

### Frontend

Lancer le serveur Next (`docker compose exec frontend pnpm dev`) :
```bash
make front-dev
```

Intéragir avec le container Next (`docker compose exec frontend sh`) :
```bash
make front-console
```
