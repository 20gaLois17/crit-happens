<?php

namespace Dkp;

use Dkp\Controller\Backend\BackendDkpController;
use Dkp\Controller\Frontend\FrontendDkpController;
use Dkp\Models\DkpEntry;
use Dkp\Services\DkpService;
use FrontendUserManagement\Services\AccountNavigationService;
use Oforge\Engine\Modules\AdminBackend\Core\Services\BackendNavigationService;
use Oforge\Engine\Modules\Core\Abstracts\AbstractBootstrap;
use Oforge\Engine\Modules\I18n\Helper\I18N;

class Bootstrap extends AbstractBootstrap {

    public function __construct() {
        $this->endpoints = [
            FrontendDkpController::class,
            BackendDkpController::class,
        ];

        $this->dependencies = [
            \FrontendUserManagement\Bootstrap::class,
        ];

        $this->models = [
            DkpEntry::class,
        ];

        $this->services = [
            'dkp' => DkpService::class,
        ];
    }

    public function install() {
        I18N::translate('frontend_account_dkp', [
            'de' => 'DKP'
        ]);
        I18N::translate('backend_dkp_controller', [
            'de' => 'DKP'
        ]);
        I18N::translate('frontend_account_dkp_list', [
            'de' => 'DKP-Übersicht',
        ]);
        I18N::translate('frontend_account_dkp_history', [
            'de' => 'DKP-Historie',
        ]);
    }

    public function activate() {
        /** @var AccountNavigationService $accountNavigationService */
        $accountNavigationService = Oforge()->Services()->get('frontend.user.management.account.navigation');

        $accountNavigationService->put([
            'name'     => 'frontend_account_dkp_list',
            'order'    => 2,
            'icon'     => 'star',
            'path'     => 'frontend_account_dkp_list',
            'position' => 'sidebar',
        ]);
        $accountNavigationService->put([
            'name'     => 'frontend_account_dkp_history',
            'order'    => 3,
            'icon'     => 'star',
            'path'     => 'frontend_account_dkp_history',
            'position' => 'sidebar',
        ]);

        /** @var BackendNavigationService $backendNavigationService */
        $backendNavigationService = Oforge()->Services()->get('backend.navigation');
        $backendNavigationService->add([
            'name'     => 'backend_dkp_controller',
            'order'    => 0,
            'icon'     => 'profile',
            'position' => 'sidebar',
            'path'     => 'backend_dkp_controller'
        ]);
    }
}
