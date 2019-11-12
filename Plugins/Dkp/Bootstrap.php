<?php

namespace Dkp;

use Dkp\Controller\Frontend\FrontendDkpController;
use FrontendUserManagement\Services\AccountNavigationService;
use Oforge\Engine\Modules\Core\Abstracts\AbstractBootstrap;
use Oforge\Engine\Modules\I18n\Helper\I18N;

class Bootstrap extends AbstractBootstrap {

    public function __construct() {
        $this->endpoints = [
            FrontendDkpController::class,
        ];

        $this->dependencies = [
            \FrontendUserManagement\Bootstrap::class,
        ];
    }

    public function install() {
        I18N::translate('frontend_account_dkp', [
            'en' => 'DKP',
            'de' => 'DKP'
        ]);
    }

    public function activate() {
        /** @var AccountNavigationService $accountNavigationService */
        $accountNavigationService = Oforge()->Services()->get('frontend.user.management.account.navigation');

        $accountNavigationService->put([
            'name'     => 'frontend_account_dkp',
            'order'    => 3,
            'icon'     => 'star',
            'path'     => 'frontend_account_dkp',
            'position' => 'sidebar',
        ]);
    }
}
