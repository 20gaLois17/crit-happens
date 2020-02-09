<?php

namespace LootTable\Controller\Frontend;

use FrontendUserManagement\Abstracts\SecureFrontendController;
use LootTable\Services\LootManagementService;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Slim\Http\Request;
use Slim\Http\Response;

/**
* @EndpointClass(path="frontend/account/loottable", name="frontend_account_loottable", assetScope="Frontend")
*/
class FrontendLootTableController extends SecureFrontendController {

  public function indexAction() {
      /** @var LootManagementService $service */
      $service = Oforge()->Services()->get('loot.management');
      $items = $service->listItems();
      Oforge()->View()->assign(["loot_table" => ["items" => $items]]);

  }

  public function initPermissions() {
    $this->ensurePermissions([
      'indexAction',
    ]);
  }

}
