<?php

namespace Oforge\Engine\Modules\Auth\Services;

/**
 * Class PasswordService
 *
 * @package Oforge\Engine\Modules\Auth\Services
 */
class PasswordService {

    /**
     * @param string $password
     *
     * @return bool|string
     */
    public function hash(string $password) {
        if (strlen($password) > 5) {
            return password_hash($password, PASSWORD_BCRYPT);
        }

        return null;
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function validate(string $password, string $hash) {
        return password_verify($password, $hash);
    }

}
