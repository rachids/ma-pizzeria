<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Config de base
 */
$config['jeu_MoneySymbol'] = 'assets/img/money.png'; //Cela peut être une image.
$config['jeu_passwordClients'] = 'c1l8h6e3u7r0e5d3e2b4o6u8f9f1e5r2d3l4a8p6i8z9z4m5o1n25o8s6t7i0'; //Cela peut être une image.
$config['jeu_apportNotoriete'] = 2; //Pour le calcul du nombre de clients attirés.
$config['jeu_margeNotoriete'] = 0.3; //30% de marge.
$config['jeu_lvlUpNotoriete'] = 10; //Coefficient.
$config['pagination_limite'] = 10; //10 par page.

/**
 * Valeurs de base lors de la création du restaurant
 */
$config['restaurant_capacite'] = 20; //20 clients par service
$config['restaurant_stock'] = 10;
$config['restaurant_notoriete'] = 1;
$config['restaurant_etat'] = 50;
$config['restaurant_argent'] = 500;
$config['restaurant_openMax'] = 2;
$config['restaurant_coutPrivatisation'] = 50;
$config['restaurant_seuilEtatSecurite'] = 2;

/**
 * Valeurs pour batiment
 */
$config['restaurant_upgradePDB'] = 15; //1 point coûte 15$ de plus que les points précédents.
$config['restaurant_upgradeStock'] = 1; //1 point coûte 1$ de plus que les points précédents.
$config['restaurant_upgradeCapacite'] = 30; //1 point coûte 1$ de plus que les points précédents.
$config['restaurant_reparer'] = 3; //1 point de réparation coûte 3$


/**
 * Valeurs des ingrédients
 */
$config['ingredient_image'] = 'assets/img/ingredients/';
$config['ingredient_margeErreurStock'] = 0.10; //10% de marge d'erreur sur l'affichage des stocks dispo.
$config['ingredient_valeurPlus2'] = 0.30; //Prct de produits en stock pour obtenir +2 sur le prix.
$config['ingredient_valeurPlus1'] = 0.5; //Entre 0.3 et 0.5 on obtient +1 sur le prix.
$config['ingredient_valeurMoins1'] = 0.65; //Si +0.65% en stock alors -1 sur prix.
$config['ingredient_valeurMoins2'] = 0.85; //Entre 0.85 et 1 = -2 sur prix.

/**
 * Valeurs pour les recettes de Pizzas
 */
$config['pizza_recetteMax'] = 3; //Nombre de recettes maximale que peut avoir un restaurant
$config['pizza_ingredientsMax'] = 5; //Nombre d'ingrédients maximum que peut avoir une recette

/**
 * Valeurs pour le vandalisme
 */
$config['pizza_cambriolage_cout'] = 200;
$config['pizza_cambriolage_chance'] = 70;
$config['pizza_graffiti_cout'] = 400;
$config['pizza_graffiti_chance'] = 80;
$config['pizza_casse_cout'] = 800;
$config['pizza_casse_chance'] = 65;
$config['pizza_echapper_chance'] = 20; #Chance d'arrêter les vandales
$config['pizza_denonciation_chance'] = 95; #Chance de se faire dénoncer par les vandales
$config['pizza_ratioAmende'] = 0.6; #L'Amende coûte 60% du coût du vandalisme.

/**
 * Valeurs pour l'Agent de Sécurité
 */
$config['pizza_securite_lvl2'] = 10; #Enleve 10% de réussite

//Various
$config['avatarsioKey'] = '278e3380bd5d6300e4f75c0dbe76e0d20c3ac4127e5a6d157dde364e3584903b';
$config['avatarTemp'] = './assets/upload';
$config['soccerEvent'] = 3; #Pourcentage de chance qui s'ajoute chaque jour pour provoquer l'événement
$config['soccerTeams'] = array(
                            'OM'        =>  'de l\'Olympique Margarita',
                            'PSG'       =>  'de la Pizza Sans Garniture',
                            'ASNL'      =>  'de l\'Asso. Sportive des Nachos Lorrains',
                            'FCB'       =>  'du FC Bolognaise',
                            'LIV'       =>  'du Liverpizza',
                            'LOSC'      =>  'de Légumes Oignons Salami Calzone'
                        );
/**
 * Page profil
 */
$config['sexe'] = array(0 => '--', 1 => 'Homme', 2 => 'Femme');