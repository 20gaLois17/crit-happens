<?php

namespace FrontendUserManagement\Services;

use Doctrine\ORM\ORMException;
use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Abstracts\AbstractDatabaseAccess;
use Oforge\Engine\Modules\Core\Helper\SessionHelper;

class UserService extends AbstractDatabaseAccess {
    public function __construct() {
        parent::__construct(['default' => User::class]);
    }

    const CLASSES = [
        'warrior', 'mage', 'priest', 'druid', 'shaman', 'warlock', 'hunter', 'rogue',
    ];

    /**
     * @param string $userName
     * @param string $password
     * @param string $class
     *
     * @throws ORMException
     */
    public function createUserEntry(string $userName, string $password, string $class) {

        /** TODO: check if class is valid */

        /** @var User $user */
        $user = new User();
        $user->setEmail($userName)->setClass($class);
        $user->setGuid(SessionHelper::generateGuid());
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user->setPassword($passwordHash);
        $user->setActive(true);
        $this->entityManager()->create($user);

        $user = $user->toArray(2, ['createdAt', 'updatedAt', 'guid', 'password']);
        $user['password'] = $password;
        print_r($user);
    }

    /**
     * @param array $userData
     *
     * @throws ORMException
     */
    public function createUserEntries(array $userData) {
        foreach ($userData as $user) {
            $this->createUserEntry($user[0], $user[1], $user[2]);
        }
    }

    /**
     * @param $id
     *
     * @return User
     * @throws ORMException
     */
    public function getUserById(int $id) : ?User {
        /** @var User $user */
        $user = $this->repository()->find($id);

        return $user;
    }

    /**
     * @return array
     * @throws ORMException
     */
    public function getUsers() : array {
        $users  = $this->repository()->findAll();
        $result = [];
        /** @var User $user */
        foreach ($users as $user) {
            $result[] = $user->toArray();
        }

        return $result;
    }
}
