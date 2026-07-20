BASE DE DONNE
    initialisation database 4064-4284

    TABLE
        prefixes - 4064 - stocke les prefixes valables des operateurs
        numero_telephone - 4064 - stocke les comptes clients et leur solde
        frais_retrait - 4064 - tranches de frais appliquees au retrait
        frais_transfert - 4064 - tranches de frais appliquees au transfert
        depot - 4284 - enregistre les depots effectues
        retrait - 4284 - enregistre les retraits effectues
        transfert - 4284 - enregistre les transferts entre comptes

    INSERTION
        prefixes et numero_telephone - 4064 - donnees de test des operateurs et clients
        frais_retrait et frais_transfert - 4064 - donnees de test des baremes
        depot, retrait, transfert - 4284 - donnees de test des operations

    VIEW
        v_compte_client - 4064 - lecture rapide dun compte avec son operateur
        v_login_rapide - 4064 - verification du prefixe et numero a la connexion
        v_gains_par_operation - 4064 - gains totaux regroupes par type doperation
        v_statistiques_globales - 4064 - chiffres cles pour le dashboard operateur
        v_gains_mensuels - 4064 - evolution des gains mois par mois
        v_repartition_gains - 4064 - repartition des gains par tranche de montant
        v_performance_operateur - 4064 - performance de chaque operateur telephonique
        v_historique_client - 4284 - historique complet cote client
        v_resume_client - 4284 - resume du compte pour le dashboard client
        v_operations_client - 4284 - detail des operations cote operateur
        v_frais_tranches - 4284 - liste des tranches de frais existantes
        v_solde_moyen_operateur - 4284 - solde moyen par operateur
        v_clients_actifs - 4284 - classement des clients les plus actifs
        v_anomalies - 4284 - detection des soldes ou frais anormaux
        v_reporting_quotidien - 4284 - resume des operations du jour
        v_gains_journaliers - 4284 - gains regroupes par jour

INIT CODE IGNITER
    installation et configuration de base - 4064

STRUCUTRE DOSSIER
    /operateur - 4064 - espace de gestion pour loperateur
    /client - 4284 - espace utilisateur pour les clients

CONFIG
    Database.php - 4064 - connexion vers la base sqlite existante
    Routes.php - 4064 - toutes les routes operateur et client
    Filters.php - 4284 - filtre dauthentification client

MODELS
    PrefixeModel - 4064 - gestion des prefixes operateur
    NumeroTelephoneModel - 4064 - gestion des comptes et ajustement du solde
    FraisRetraitModel - 4064 - calcul et gestion des tranches de retrait
    FraisTransfertModel - 4064 - calcul et gestion des tranches de transfert
    GainModel - 4064 - lecture des vues de gains pour le dashboard
    DepotModel - 4284 - creation des depots
    RetraitModel - 4284 - creation des retraits
    TransfertModel - 4284 - creation des transferts
    CompteModel - 4284 - lecture du solde et historique cote operateur
    ClientModel - 4284 - authentification et recherche dun destinataire

CONTROLLERS
    Prefixe - 4064 - CRUD des prefixes operateur
    Creation_operation - 4064 - depot, retrait, transfert, frais, comptes et gain cote operateur
    Login - 4284 - authentification client par prefixe et numero
    Client - 4284 - operations et historique cote client

LAYOUT
    layout/operateur (header, nav, footer) - 4064 - habillage des pages operateur
    layout/client (header, footer) - 4284 - habillage des pages client

VIEW + ROUTE
    vues operateur (creation_operation, depot, retrait, transfert, modifier_frais, gain, prefixes, comptes, compte_detail) - 4064
    vues client (login, dashboard, depot, retrait, transfert, historique) - 4284
    routes operateur et client - 4064

JS
    apercu dynamique des frais en JS - 4284