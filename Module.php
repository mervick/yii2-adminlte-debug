<?php

namespace mervick\adminlte\debug;

use Yii;
use yii\debug\Module as DebugModule;

/**
 * Class Module
 * @package mervick\adminlte\debug
 * @author Andrey Izman <izmanw@gmail.com>
 */
class Module extends DebugModule
{

    /**
     * @var array the list of roles that are allowed to access this module.
     * Each array element is a hostname that will be resolved to an IP address that is compared
     * with the IP address of the user. A use case is to use a dynamic DNS (DDNS) to allow access.
     * The default value is `[]`.
     * @var array list of roles that this rule applies to. Two special roles are recognized, and
     * they are checked via [[User::isGuest]]:
     *
     * - `?`: matches a guest user (not authenticated yet)
     * - `@`: matches an authenticated user
     *
     * If you are using RBAC (Role-Based Access Control), you may also specify role or permission names.
     * In this case, [[User::can()]] will be called to check access.
     *
     * If this property is not set or empty, it means this rule applies to all roles.
     */
    public $allowedRoles;


    /**
     * Checks if current user is allowed to access the module
     * @return boolean if access is granted
     */
    protected function checkAccess()
    {
        $ip = Yii::$app->getRequest()->getUserIP();
        if ($this->matchRole()) {
            foreach ($this->allowedIPs as $filter) {
                if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                    return true;
                }
            }
            foreach ($this->allowedHosts as $hostname) {
                $filter = gethostbyname($hostname);
                if ($filter === $ip) {
                    return true;
                }
            }
            Yii::warning('Access to debugger is denied due to IP address restriction. The requesting IP address is ' . $ip, __METHOD__);
        }
        return false;
    }

    /**
     * @return boolean whether the rule applies to the role
     */
    protected function matchRole()
    {
        if (empty($this->allowedRoles)) {
            return true;
        }
        $user = Yii::$app->user;
        foreach ($this->allowedRoles as $role) {
            if ($role === '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif ($role === '@') {
                if (!$user->getIsGuest()) {
                    return true;
                }
            } elseif ($user->can($role)) {
                return true;
            }
        }

        return false;
    }
}