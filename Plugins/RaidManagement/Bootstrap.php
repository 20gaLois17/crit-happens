<?php

namespace RaidManagement;

use FrontendUserManagement\Services\AccountNavigationService;
use Oforge\Engine\Modules\Core\Abstracts\AbstractBootstrap;
use RaidManagement\Controller\Backend\BackendRaidEventController;
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
    }
}
