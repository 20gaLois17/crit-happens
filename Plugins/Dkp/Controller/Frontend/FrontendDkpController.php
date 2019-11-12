<?php

namespace Dkp\Controller\Frontend;

use FrontendUserManagement\Abstracts\SecureFrontendController;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class FrontendDkpController
 *
 * @EndpointClass(path="frontend/account/dkp", name="frontend_account_dkp", assetScope="Frontend")
 */
class FrontendDkpController extends SecureFrontendController {
    public function indexAction(Request $request, Response $response) {

    }

    public function initPermissions() {
        $this->ensurePermissions([
            'indexAction',
        ]);
    }
}
