<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['jeu/restaurant'] = 'restaurant/gestion';
$route['jeu/restaurant/(:any)'] = 'restaurant/$1';

$route['jeu/marche'] = 'marche/index';
$route['jeu/marche/(:any)'] = 'marche/$1';

$route['jeu/recettes/(:any)'] = 'recettes/$1';
$route['jeu/recettes'] = 'recettes/index';

$route['jeu/journal/public/(:num)'] = 'journal/pub/$1';
$route['jeu/journal/public'] = 'journal/pub';
$route['jeu/journal/prive/(:num)'] = 'journal/pri/$1';
$route['jeu/journal/prive'] = 'journal/pri';
$route['jeu/journal/vider'] = 'journal/vider';
$route['jeu/journal'] = 'journal/index';

$route['jeu/emploi/licencier/(:num)'] = 'emploi/licencier/$1';
$route['jeu/emploi/(:any)'] = 'emploi/$1';
$route['jeu/emploi'] = 'emploi/index';

$route['jeu/attaquer/(:any)'] = 'attaquer/$1';
$route['jeu/attaquer'] = 'attaquer/index';



$route['jeu'] = 'restaurant/gestion';

$route['default_controller'] = "pages/index";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */