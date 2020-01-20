##LAZERBLOCK

Contexte :
-

"Shinigami Laser" est un laser game assez connu en Bourgogne. Malheureusement, le suivi des joueurs est aujourd'hui assez

chaotique avec des fiches papier sur lesquelles sont écrits le nom, le surnom, l'adresse, le numéro de téléphone et la date de

naissance d'un joueur. Ces fiches sont devenues totalement obsolètes, aussi nous aimerions nous doter d'un système de

cartes de fidélité, cartes physiques ou numériques (avec une application sur smartphone comme interface).

Pour ce faire, nous avons contacté un fournisseur de cartes qui pourrait nous imprimer des cartes avec une puce NFC/RFID, un

QR code optionnel, et bien sûr des informations précieuses sur notre club.

La carte elle-même aura un numéro. Celui-ci servira pour nos références internes, mais aussi à nos clients qui pourront les

rattacher à leur compte en ligne pour suivre différentes informations : leurs visites, leurs scores, leurs offres, etc

  
  
  
  

Fonctionnalités :
-

  
  

SecurityController

Un client pourra ouvrir un compte

- register

- login

- logout

- forget_password

- reset_password

- renew_password

  

UserController

Il aura la possibilité d'afficher et de modifier ses informations personnelles

- account => Affiche le compte de l'utilisateur + Les meilleurs scores + Les dernières visites

- account/profile => Permet la modification des informations personnelles

  

CardController

Il aura la possibilité de rattacher sa carte de fidélité délivrée en boutique

- account/card

  

Il aura accès à son historique de scores et de visites

- score / La caissière pourra via un formulaire saisir les scores

- visits

  
  
  

L'utilisateur pourra acheter plusieurs tickets, et les réserver ensuite.

La réservation pourra se faire pour plusieurs tickets

La réservation de plusieurs tickets sera liéé à un compte utilisateur

Une réservation pourra se faire direction à la caisse sans compte utilisateur

Les scores seront saisis par la caissière.

La caissière saisira le score et le pseudo de la réservation en faisant une recherche par le serial

Concernant la fidélité

Possibilités d'échanger les points contre des cadeaux lors de votre prochaine partie

Lors de l'anniversaire, une place gratuite

Possibilité d'envoyer les places achetées à des amis

Définir le périmètre technologies	
-

Html css scss bootstrap jquery php symfony mysql fontawesome

mysql workbench docker git trello

mobile pc derniers navigateurs

Définition des sprints
-
Sprint 1:
- Maquette first Page
- Fixtures
- SecurityController
- UserController
- CardController
- GiftController

Sprint 2:
- Maquette
- EasyAdmin
- BookingController

Sprint 3:
- Maquette ticket
- TicketController
- EasyAdmin

Sprint 4
- ApiPlateform

Sprint 5
- phpUnit

Améliorations 
- Parrainage

Tableau de routage
-
"/" HomeController index() homepage
"/register" SecurityController register() register Anonymous // Permet d'enregistrer l'utilisateur
"/login" SecurityController login() login
"/logout" SecurityController logout() logout
"/activate" SecurityController activate() activate // Active le compte de l'utilisateur
"/forget-password" SecurityController forgetPassword() forget_password // Permet d'initier la méthode de reset password
"/reset-password" SecurityController resetPassword() reset_password // Permet de réintialiser son mot de passe
"/renew-password" SecurityController renewPassword() renew_password ROLE_USER // Permet de changer de mot de passe

  
  

"/account" AccountController account() account ROLE_USER //Permet d'afficher le profile de l'utilisateur
"/account/update" AccountController accountUpdate() account_update // Permet de modifier le profile de l'utilisateur
"/account/card/create" CardController card() card_create // Permet d'ajouter la carte de fidélité à l'utilisateur

"/account/booking/create" BookingController create() booking_create // Permet d'enregistrer une réservation
"/account/ticket/buy" TicketController buy() ticket_buy // Permet d'acheter un ou plusieurs ticket

"/gift" GiftController showGift() show_gift ROLE_USER // Afficher le catalogue des cadeaux
"/gift/buy" GiftController buyGift() buy_gift // Permet d'acheter un cadeau

"/admin" Easyadmin ROLE_ADMIN ROLE_CASHIER // Affiche l'interface d'administration