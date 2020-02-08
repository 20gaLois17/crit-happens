<?php

namespace Plugins\LootTable\Controller\Frontend;

use Slim\Http\Request;
use Slim\Http\Response;

/**
* @EndpointClass(path="frontend/account/loottable", name="frontend_account_loottable", assetScope="Frontend")
*/
class FrontendLootTableController extends secureFrontendController {
  public function indexAction() {

  }

  public function initPermissions() {
    $this->ensurePermission([
      'indexAction',
    ]);
  }

}
