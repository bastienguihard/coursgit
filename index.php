<?php

include __DIR__."/vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Session\Session;

//Connexion & co
$routeIndex = new Route('/',
    ['_controller' => 'ProjetP1\App\Controller\ConnexionController::index']
);
$routeCo = new Route('/connexion',
    ['_controller' => 'ProjetP1\App\Controller\ConnexionController::login']
);
$routeDeco = new Route('/deconnexion',
    ['_controller' => 'ProjetP1\App\Controller\ConnexionController::logout']
);
//Messagerie
$routeMessagerie = new Route('/messagerie/{libelle}',
    ['_controller' => 'ProjetP1\App\Controller\MessagerieController::index']
);
$routeMessagerieVierge = new Route('/messagerie',
    ['_controller' => 'ProjetP1\App\Controller\MessagerieController::index']
);
//Message
$routeVoirMessage = new Route('/message',
    ['_controller' => 'ProjetP1\App\Controller\MessagerieController::voirMessage']
);

//trier
$routeTrier = new Route('/trier',
    ['_controller' => 'ProjetP1\App\Controller\MessagerieController::trier']
);


$routeAddMail = new Route('/ajouterMessage',
    ['_controller' => 'ProjetP1\App\Controller\MessagerieController::ajouterMessage']
);
//Libellé
$routeLibelle = new Route('/libelle',
    ['_controller' => 'ProjetP1\App\Controller\LibelleController::index']
);

//Libellé_POST
$routeAjouterLibelle = new Route('/ajouterLibelle',
    ['_controller' => 'ProjetP1\App\Controller\LibelleController::ajouterLibelle']
);

//supprimer libelle
$routeSupprimerLibelle = new Route('/supprimerLibelle/{libelle}',
    ['_controller' => 'ProjetP1\App\Controller\LibelleController::supprimerLibelle']
);




//Contacts
$routeContacts = new Route('/contacts',
    ['_controller' => 'ProjetP1\App\Controller\ContactsController::index']
);
$routeContactsAjouter = new Route('/contacts/ajouterContact',
    ['_controller' => 'ProjetP1\App\Controller\ContactsController::ajouterContact']
);

    //test
$routeTestInmportCSV = new Route('/testImportCSV',
    ['_controller' => 'ProjetP1\App\Controller\TestImportCSVController::index']
);

$routes = new RouteCollection();
//
$routes->add('index', $routeIndex);
$routes->add('connexion', $routeCo);
$routes->add('deconnexion', $routeDeco);
//
$routes->add('messagerie', $routeMessagerie);
$routes->add('messagerieVierge', $routeMessagerieVierge);
$routes->add('voirMessage', $routeVoirMessage);
$routes->add('addMail', $routeAddMail);
//
$routes->add('testlibelle', $routeLibelle);
$routes->add('ajouterLibelle', $routeAjouterLibelle);
$routes->add('supprimerLibelle', $routeSupprimerLibelle);
//
$routes->add('testImportCSV', $routeTestInmportCSV);
//
$routes->add('contacts', $routeContacts);
$routes->add('contactsAjouter', $routeContactsAjouter);

$routes->add('routeTrier', $routeTrier);



$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

$session = new Session();
$session->start();

try{
    $parameters = $matcher->matchRequest($request);
    $controllerPath = $parameters['_controller'];
    list($controllerClass, $controllerFunction) = explode("::", $controllerPath); //cut le controller en deux en le répartissant dans la list
    unset($parameters['_controller']);
    $controller = new $controllerClass(); //créée une instance de controller
    /** @var Response $response */
    $response = $controller->$controllerFunction($request, $parameters); //appelle la fonction user
}catch (ResourceNotFoundException $e){
    $response = new Response ('Erreur 404 : page non trouvée', Response::HTTP_NOT_FOUND);
}

$response->prepare($request);
$response->send();