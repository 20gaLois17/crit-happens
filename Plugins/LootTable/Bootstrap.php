<?php

namespace Plugings\LootTable;

use Oforge\Engine\Modules\Core\Abstracts\AbstractBootstrap;

class LootTable extends AbstractBootstrap {
  public function __construct() {
    $this->models = [

    ];
    $this->endpoints = [

    ];
    $this->services = [

    ];
    $this->dependencies = [

    ];

  }

  public function activate() {
      /** @var AccountNavigationService $accountNavigationService */
      $accountNavigationService = Oforge()->Services()->get('frontend.user.management.account.navigation');
      $accountNavigationService->put([
          'name'     => 'frontend_account_loottable',
          'order'    => 3,
          'icon'     => 'profile',
          'path'     => 'frontend_account_loottable',
          'position' => 'sidebar',
      ]);
    }
}
