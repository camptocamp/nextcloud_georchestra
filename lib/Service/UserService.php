<?php
namespace OCA\Georchestra\Service;
use OCA\Georchestra\User\Backend;

class UserService {

    private $userManager;
    private $tokenManager;
    private $userSession;
    private $logger;
    private $request;

    public function __construct($userSession, $userManager, $request, $logger) {
        $this->userManager = $userManager;
        $this->userSession = $userSession;
        $this->request = $request;
        $this->logger = $logger;
    }

    public function login($userId, $password) {
        $this->userSession->getSession()->regenerateId();
        $user = $this->userManager->get($userId);
        $this->userSession->createSessionToken($this->request, $user->getUID(), $user->getUID());
        $ret = $this->userSession->login("admin", "");
        $this->userSession->createSessionToken($this->request, $user->getUID(), $user->getUID());

        return $ret;
    }

    public function isLoggedIn(){
        return $this->userSession->isLoggedIn();
    }

    public function logout() {
        $this->userSession->logout();
    }

    public function registerBackend(Backend $backend)
    {
        $this->userManager->registerBackend($backend);
    }
}