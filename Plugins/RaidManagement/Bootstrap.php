<?php

namespace RaidManagement;

use FrontendUserManagement\Services\AccountNavigationService;
use Oforge\Engine\Modules\AdminBackend\Core\Services\BackendNavigationService;
use Oforge\Engine\Modules\Core\Abstracts\AbstractBootstrap;
use RaidManagement\Controller\Backend\BackendRaidEventController;
use RaidManagement\Controller\Backend\BackendRaidMemberController;
use RaidManagement\Controller\Frontend\FrontendRaidEventController;
use RaidManagement\Models\RaidEvent;
use RaidManagement\Models\RaidMember;
use RaidManagement\Services\RaidManagementService;

class Bootstrap extends AbstractBootstrap {

    public function __construct() {
        $this->models = [
            RaidEvent::class,
            RaidMember::class,
        ];

        $this->services = [
            'raid.management' => RaidManagementService::class,
        ];

        $this->endpoints = [
            BackendRaidEventController::class,
            BackendRaidMemberController::class,
            FrontendRaidEventController::class,
        ];

        $this->dependencies = [
            \FrontendUserManagement\Bootstrap::class,
        ];
    }

    public function install() {

    }

    public function activate() {
        /** @var AccountNavigationService $accountNavigationService */
        $accountNavigationService = Oforge()->Services()->get('frontend.user.management.account.navigation');
        $accountNavigationService->put([
            'name'     => 'frontend_account_raids',
            'order'    => 0,
            'icon'     => 'profile',
            'path'     => 'frontend_account_raids',
            'position' => 'sidebar',
        ]);

        /** @var BackendNavigationService $backendNavigationService */
        $backendNavigationService = Oforge()->Services()->get('backend.navigation');
        $backendNavigationService->add([
            'name'     => 'backend_raid_management',
            'order'    => 0,
            'icon'     => 'profile',
            'position' => 'sidebar',
        ]);
        $backendNavigationService->add([
            'name'     => 'backend_raid_member',
            'order'    => 0,
            'icon'     => 'fa fa-gears',
            'path'     => 'backend_raid_member',
            'position' => 'sidebar',
            'parent'   => 'backend_raid_management'
        ]);
        $backendNavigationService->add([
            'name'     => 'backend_raids',
            'order'    => 0,
            'icon'     => 'fa fa-gears',
            'path'     => 'backend_raids',
            'position' => 'sidebar',
            'parent'   => 'backend_raid_management'
        ]);
    }
}
