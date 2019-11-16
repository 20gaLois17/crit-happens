<?php

namespace RaidManagement\Controller\Frontend;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Abstracts\SecureFrontendController;
use FrontendUserManagement\Services\FrontendUserService;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointAction;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Oforge\Engine\Modules\Core\Exceptions\ServiceNotFoundException;
use Oforge\Engine\Modules\Core\Helper\RouteHelper;
use RaidManagement\Models\RaidEvent;
use RaidManagement\Services\RaidManagementService;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class FrontendRaidEventController
 * @EndpointClass(path="frontend/account/raids", name="frontend_account_raids", assetScope="Frontend")
 */
class FrontendRaidEventController extends SecureFrontendController {

    /**
     * list all raid events and add functions to subscribe/unsubscribe to an event
     */
    /**
     * @throws ORMException
     * @throws ServiceNotFoundException
     */
    public function indexAction() {

        /** @var FrontendUserService $frontendUserService */
        $frontendUserService = Oforge()->Services()->get('frontend.user');
        $user = $frontendUserService->getUser();

        /** @var RaidManagementService $raidManagementService */
        $raidManagementService = Oforge()->Services()->get('raid.management');
        Oforge()->View()->assign([
            'raids' => $raidManagementService->listActiveRaids(),
            'user_raids' => $raidManagementService->listUserParticipation($user),
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ServiceNotFoundException
     * @EndpointAction(path="/toggleRaid/{raidId:\d+}")
     */
    public function toggleRaidAction(Request $request, Response $response, $args) {

        if (!$request->isPost()) {
            print('invalid method'); die();
        }

        $raidId = $args['raidId'];

        if(is_null($raidId)) {
            print('no id :/'); die();
        }

        /** @var RaidManagementService $raidManagementService */
        $raidManagementService = Oforge()->Services()->get('raid.management');
        /** @var RaidEvent $raid */
        $raid = $raidManagementService->repository('raid')->find($raidId);

        if(is_null($raid)) {
            print('this raid does not exist'); die();
        }

        /** @var FrontendUserService $frontendUserService */
        $frontendUserService = Oforge()->Services()->get('frontend.user');

        $user = $frontendUserService->getUser();

        if(isset($_POST['role'])) {
            if (!$this->roleCheck($user->getClass(), $_POST['role'])) {
                return RouteHelper::redirect($response, 'frontend_account_raids');
            }
        }

        if ($raidManagementService->toggleRaidSubscription($user, $raid, $_POST)) {
            Oforge()->View()->Flash()->addMessage('success', 'Du bist beim Raid dabei!');
        } else {
            Oforge()->View()->Flash()->addMessage('warning', 'Du hast dich vom Raid abgemeldet!');
        }

        return RouteHelper::redirect($response, 'frontend_account_raids');

    }

    private function roleCheck(string $class, string $role) {
        $tanks = ['warrior', 'druid'];
        $healer = ['priest', 'shaman', 'druid'];

        if ($role == 'dps') return true;

        if ($role == 'tank' && !in_array($class, $tanks)) {
            Oforge()->View()->Flash()->addMessage('error', 'Deine Klasse sollte nicht tanken, bitte wähle eine andere Rolle!');
            return false;
        }
        if ($role == 'healer' && !in_array($class, $healer)) {
            Oforge()->View()->Flash()->addMessage('error', 'Deine Klasse sollte nicht heilen, bitte wähle eine andere Rolle!');
            return false;
        }
        return true;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @throws ORMException
     * @throws ServiceNotFoundException
     * @EndpointAction(path="detail/{raidId:\d+}")
     */
    public function detailAction(Request $request, Response $response, $args) {
        $raidId = $args['raidId'];

        if (is_null($raidId)) {
            print('no id :/'); die();
        }

        /** @var RaidManagementService $raidManagementService */
        $raidManagementService = Oforge()->Services()->get('raid.management');

        /** @var RaidEvent $raid */
        $raid = $raidManagementService->repository('raid')->find($raidId);

        if(is_null($raid)) {
            print('this raid does not exist'); die();
        }

        $raidMembers = $raidManagementService->listRaidMembers($raid);
        $raidRoles   = $raidManagementService->countRoles($raid);
        Oforge()->View()->assign([
            'raid' => $raid->toArray(),
            'raid_members' => $raidMembers,
            'roles' => $raidRoles
        ]);

    }

    public function initPermissions() {
        $this->ensurePermissions([
            'indexAction',
            'toggleRaidAction',
            'detailAction'
        ]);
    }
}
