<?php

namespace LootTable\Controller\Frontend;

use Doctrine\ORM\ORMException;
use FrontendUserManagement\Abstracts\SecureFrontendController;
use LootTable\Services\LootManagementService;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointAction;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Oforge\Engine\Modules\Core\Exceptions\ServiceNotFoundException;
use Oforge\Engine\Modules\I18n\Helper\I18N;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * @EndpointClass(path="frontend/account/loottable", name="frontend_account_loottable", assetScope="Frontend")
 */
class FrontendLootTableController extends SecureFrontendController {

    /** restrict access */
    private $allowedUser = [
        "leobs"    => 1,
        "leon"     => 21,
        "justizia" => 3,
        "valeyna"  => 4,
        "lunascah" => 66,
        "dingo"    => 22,
    ];

    private function isAllowed($userId) {
        return in_array($userId, $this->allowedUser);
    }

    /**
     * @throws ORMException
     * @throws ServiceNotFoundException
     */
    public function indexAction(Request $request, Response $response) {
        $userId = Oforge()->View()->get('current_user.id');

        if(!$this->isAllowed($userId)) {
            Oforge()->View()->Flash()->addMessage('warning', "Dieses Feature wird bald freigeschaltet, Info folgt im Discord.");
            $router = Oforge()->App()->getContainer()->get('router');
            $uri    = $router->pathFor('frontend_account_dashboard');
            return $response->withRedirect($uri);
        }

        /** @var LootManagementService $service */
        $service         = Oforge()->Services()->get('loot.management');
        $items           = $service->listItems();
        $userPreferences = $service->listPreferencesByUser();
        $itemPreferences = $service->listPreferencesByItem();
        Oforge()->View()->assign(["loot_table" => $items]);
        Oforge()->View()->assign(['loot_preferences_users' => $userPreferences]);
        Oforge()->View()->assign(['loot_preferences_items' => $itemPreferences]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @EndpointAction()
     *
     * @return Response
     * @throws ServiceNotFoundException
     * @throws ORMException
     */
    public function preferencesAction(Request $request, Response $response) {
        $itemNumber = $request->getParam('itemId');
        $userId     = Oforge()->View()->get('current_user.id');

        if(!$this->isAllowed($userId)) {
            Oforge()->View()->Flash()->addMessage('warning', "Dieses Feature wird bald freigeschaltet, Info folgt im Discord.");
            $router = Oforge()->App()->getContainer()->get('router');
            $uri    = $router->pathFor('frontend_account_dashboard');
            return $response->withRedirect($uri);
        }

        if (!$request->isPost() || !isset($itemNumber) || !isset($userId)) {
            die('Error');
        }
        /** @var LootManagementService $service */
        $service = Oforge()->Services()->get('loot.management');
        if($service->togglePreference($userId, $itemNumber)) {
            Oforge()->View()->Flash()->addMessage('success',I18N::translate('preference_update_success', [
                'de' => 'Loot Bedarf wurde erfolgreich angepasst.'
            ]));
        } else {
            Oforge()->View()->Flash()->addMessage('error', I18N::translate('preference_update_error', [
                'de' => 'Es ist ein Fehler aufgetreten.'
            ]));
        }
        $router = Oforge()->App()->getContainer()->get('router');
        $uri    = $router->pathFor('frontend_account_loottable');

        return $response->withRedirect($uri);
    }

    public function initPermissions() {
        $this->ensurePermissions([
            'indexAction',
            'preferencesAction',
        ]);
    }
}
