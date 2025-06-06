# EcoRide - Application de Covoiturage

EcoRide est une application de covoiturage permettant aux conducteurs et passagers de se rencontrer pour partager des trajets écologiques et économiques. Ce projet est développé dans le cadre de mon ECF (Examen de Certification Finale) pour valider mes compétences en développement web.

## Prérequis

Avant de commencer, assurez-vous que vous avez les outils suivants installés :

- **PHP** (version 7.4 ou supérieure)
- **MySQL** via **phpMyAdmin** (inclus avec XAMPP)
- **XAMPP** pour héberger le serveur local
- **VSCode** pour éditer le code
- **Git** pour la gestion de versions

## Déployer l'Application en Local

### Étape 1 : Cloner le Repository

Clonez ce projet en utilisant la commande Git suivante dans votre terminal ou dans VSCode :

```bash
git clone https://github.com/solunik/Ecoride.git
```

### Étape 2 : Installer les Dépendances

Dans VSCode, ouvrez le terminal intégré et exécutez cette commande pour installer les dépendances PHP :

```bash
composer install
```

### Étape 3 : Lancer XAMPP

- Ouvrez XAMPP et démarrez les services Apache et MySQL.
- Si MySQL ne fonctionne pas directement, vérifiez dans phpMyAdmin que tout est bien configuré.

### Étape 4 : Créer la Base de Données

- Ouvrez phpMyAdmin via l'interface de XAMPP (http://localhost/phpmyadmin).
- Créez une nouvelle base de données nommée covoiturage.
- Importation de la base de données

Pour importer la base de données `covoiturage`:

 - Téléchargez le fichier `covoiturage.sql` dans le dossier `/sql` de ce dépôt.
 - Utilisez phpMyAdmin ou un autre outil de gestion de base de données pour importer ce fichier.
   - Ouvrez phpMyAdmin, sélectionnez votre base de données.
   - Allez dans l'onglet **Importer**.
   - Sélectionnez le fichier `covoiturage.sql` et cliquez sur **Exécuter**.

### Étape 5 : Lancer l'Application

Placez le projet dans le dossier htdocs de XAMPP (par défaut sur windows, `C:\xampp\htdocs`), puis accédez à l'application dans votre navigateur à l'adresse suivante :

`http://localhost/covoiturage`

## Structure des Branches Git

Ce projet utilise Git pour la gestion de versions. Voici la structure des branches et les bonnes pratiques à suivre :

1. Branche Principale (_master_)

La branche master contient le code prêt pour la production.

2. Branche de Développement (_dev_)

La branche dev contient le code en développement, avant d'être testé et validé.

3. Branches de Fonctionnalité

Chaque nouvelle fonctionnalité doit être développée dans une branche dédiée, issue de la branche dev :

```bash
git switch -c feature/nom_fonctionnalite
```

4. Processus de Fusion (Merge)

Une fois la fonctionnalité terminée et testée, fusionnez-la dans la branche dev :

```bash
git switch dev
git merge feature/nom_fonctionnalite
```

Lorsque la branche dev est stable et prête pour la production, fusionnez-la dans la branche master.

5. Commandes Git Utiles
 - Créer une branche :
```bash
git switch -c feature-nom_fonctionnalite
```

- Ajouter des fichiers :
```bash
git add nom_du_fichier
```
> Pour un fichier spécifique
```bash
git add .
```
> Pour tous les fichiers modifiés

- Committer les changements :
```bash
git commit -m "Message"
```

- Pousser les changements sur GitHub :
```bash
git push origin feature-nom_fonctionnalite
```

## Documentation et Fichiers Supplémentaires

**Manuel d'Utilisation (PDF)** : Ce document présente l'application et fournit des identifiants pour tester les différents parcours. [link](https://github.com/solunik/Ecoride/blob/dev/Manuel%20d'utilisation%20Ecoride.pdf)


**Charte Graphique (PDF)** : Inclut la palette de couleurs et la police utilisée dans l'application. [link](https://github.com/solunik/Ecoride/blob/dev/Charte%20Graphique.pdf)

**Documentation Technique (PDF)** :

- **Réflexions sur les choix technologiques du projet**.
- **Configuration de l'environnement de travail**.
- **Modèle Conceptuel de Données (MCD)**.
- **Diagramme d'Utilisation et Diagramme de Séquence**.
- **Documentation de Déploiement expliquant les différentes étapes**.

## Gestion de Projet

Le projet utilise une gestion de projet sous forme de Kanban. Voici le lien :

[Trello](https://trello.com/invite/b/67a9c2d26a467a2a5bdc66b0/ATTI52cc37a9449ef68dcc64ce3b704ad04eC3CFCD26/kanban-ecoride)

## Auteurs

[HAMMOUMI Sofiène](https://github.com/solunik)
