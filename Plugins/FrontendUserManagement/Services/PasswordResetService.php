<?php

namespace FrontendUserManagement\Services;

use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Abstracts\AbstractDatabaseAccess;
use Oforge\Engine\Modules\Core\Helper\SessionHelper;

class PasswordResetService extends AbstractDatabaseAccess {
    public function __construct() {
        parent::__construct(['default' => User::class]);
    }

    public function emailExists(string $email) {
        return $this->repository()->findOneBy(['email' => $email]) !== null;
    }

    /**
     * @param string $guid
     * @param string $password
     *
     * @return array|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePassword(string $guid, string $password) {

        /** @var User $user */
        $user = $this->repository()->findOneBy(['guid' => $guid]);

        if ($user) {
            $user->setGuid(SessionHelper::generateGuid());
            $user->setPassword($password);
            $this->entityManager()->update($user);

            $user = $user->toArray(1);
            unset($user["password"]);
            $user["type"] = User::class;
        }

        return $user;
    }
}
