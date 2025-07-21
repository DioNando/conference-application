# API Documentation

Bienvenue dans la documentation de l'API de l'application de conférence. Cette API fournit des fonctionnalités pour gérer les utilisateurs, les événements et l'authentification.

## Table des matières

- [Documentation d'authentification](AUTH_API_DOCS.md)
- [Documentation des utilisateurs](USER_API_DOCS.md)
- [Documentation des événements](EVENT_API_DOCS.md)
- [Documentation des erreurs](ERROR_API_DOCS.md)

## Vue d'ensemble

Cette API REST utilise les conventions suivantes :

- Toutes les requêtes et réponses sont au format JSON
- Les requêtes authentifiées nécessitent un token d'authentification Bearer dans l'en-tête
- Les réponses paginées suivent une structure standard avec des métadonnées
- Les codes d'état HTTP standard sont utilisés (200, 201, 204, 400, 401, 403, 404, 422, 500)

## Authentification

Pour interagir avec les endpoints protégés de l'API, vous devez obtenir un token d'authentification via le processus d'inscription ou de connexion. Voir la [Documentation d'authentification](AUTH_API_DOCS.md) pour les détails complets.

## Modèles principaux

L'API gère ces ressources principales :

- **Utilisateurs** : Personnes utilisant le système, avec différents rôles et permissions
- **Événements** : Conférences et ateliers avec dates, lieux et descriptions
- **Organisations** : Entités participant aux événements (à venir)
- **Pays** : Référence des pays (à venir)
