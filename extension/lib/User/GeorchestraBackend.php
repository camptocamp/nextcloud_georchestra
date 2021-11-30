<?php

namespace OCA\Georchestra\User;

use OC\User\Backend;
//use OCA\User_LDAP\IUser;
//use OCP\User\Backend\ICheckPasswordBackend;

//class Backend implements IUser, ICheckPasswordBackend
class GeorchestraBackend extends Backend {
	private $logger;

    public function __construct() {

    }

    public function login() {
        return true;
    }
    public function createUserGroupFolder($user) {
        $this->logger->debug("unimplemented"); //TODO
        return;
    }

    public function groupFolderExistForUser($user) {
        $this->logger->debug("unimplemented"); //TODO
        return;
    }
}