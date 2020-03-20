<?php

namespace Dkp\Controller\Frontend;

use Dkp\Models\DkpEntry;
use Dkp\Services\DkpService;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Abstracts\SecureFrontendController;
use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointAction;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Oforge\Engine\Modules\Core\Exceptions\ServiceNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class FrontendDkpController
 *
 * @EndpointClass(path="frontend/account/dkp", name="frontend_account_dkp", assetScope="Frontend")
 */
class FrontendDkpController extends SecureFrontendController {

    public function indexAction() {

    }

    /**
     * @param Request $request
     * @param Response $response
     * @EndpointAction(path="/list")
     *
     * @throws ServiceNotFoundException
     */
    public function listAction(Request $request, Response $response) {
        /** @var DkpService $dkpService */
        $dkpService = Oforge()->Services()->get('dkp');

        /** @var User[] $userEntities */
        $userEntities = Oforge()->DB()->getForgeEntityManager()->getRepository(User::class)->findBy(['active' => 1],["class" => "DESC"]);
        $userDkp      = [];
        foreach($userEntities as $entity) {
            $arrayEntity = $entity->toArray(1,['id','password', 'guid', 'createdAt', 'updatedAt', 'active', 'coreRaider', 'dkp']);
            $arrayEntity['dkp'] = $dkpService->getUserDkp($entity->getId())['total'];
            $userDkp[] = $arrayEntity;
        }
        Oforge()->View()->assign(['user_dkp' => $userDkp]);
        Oforge()->View()->assign(['last_update' => Oforge()->DB()->getForgeEntityManager()->getRepository(DkpEntry::class)->findBy([],['createdAt' => 'DESC'], 1)[0]->getCreatedAt()]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @EndpointAction(path="/history")
     *
     * @throws ServiceNotFoundException
     * @throws ORMException
     */
    public function historyAction(Request $request, Response $response) {
        $userId = Oforge()->View()->get('current_user')['id'];
        /** @var DkpService $dkpService */
        $dkpService = Oforge()->Services()->get('dkp');
        $dkpHistory = $dkpService->getDkpHistory($userId);

        $userDkp    = $dkpService->getUserDkp($userId);


        Oforge()->View()->assign(['dkp_history' => $dkpHistory]);
        Oforge()->View()->assign(['user_dkp' => $userDkp]);
    }

    public function initPermissions() {
        $this->ensurePermissions([
            'indexAction',
            'historyAction',
            'listAction',
        ]);
    }
}
