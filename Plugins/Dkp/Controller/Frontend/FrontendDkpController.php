<?php

namespace Dkp\Controller\Frontend;

use FrontendUserManagement\Abstracts\SecureFrontendController;
use FrontendUserManagement\Models\User;
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
        /** @var User[] $userEntities */
        $userEntities = Oforge()->DB()->getForgeEntityManager()->getRepository(User::class)->findBy(['active' => 1],["class" => "DESC", "dkp" => "DESC"]);
        $userDkp      = [];
        foreach($userEntities as $entity) {
            $userDkp[] = $entity->toArray(1,['id','password', 'guid', 'createdAt', 'updatedAt', 'active', 'coreRaider']);
        }
        Oforge()->View()->assign(['user_dkp' => $userDkp]);
        Oforge()->View()->assign(['last_update' => Oforge()->DB()->getForgeEntityManager()->getRepository(User:class)->findOneBy(['email' => 'Leobs'])->getUpdatedAt()]);
    }

    public function initPermissions() {
        $this->ensurePermissions([
            'indexAction',
        ]);
    }
}
