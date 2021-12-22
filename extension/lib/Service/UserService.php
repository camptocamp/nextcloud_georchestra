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

    public function register() {
        $this->userSession->listen('\OC\User', 'postLogout', array($this, 'postLogout'));
    }

    public function login($userId, $password) {
        $this->userSession->getSession()->regenerateId();
        $user = $this->userManager->get($userId);
        $this->logger->info("UserId". $userId);
        $this->userSession->createSessionToken($this->request, $userId, $userId);
        $ret = $this->userSession->login($userId, "");
        $this->userSession->createSessionToken($this->request, $userId, $userId);
        return $ret;
    }

    public function isLoggedIn(){
        return $this->userSession->isLoggedIn();
    }

    public function logout() {
        $this->userSession->logout();
    }

    public function postLogout() {
        header("Location: " . $this->request->getServerProtocol() . '://' . $this->request->getServerHost() . '/cas/logout');
        exit();
    }

    public function registerBackend(Backend $backend) {
        $this->userManager->registerBackend($backend);
    }
}