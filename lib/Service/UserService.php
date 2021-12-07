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

    public function tryTokenLogin($request) {
        return $this->userSession->tryTokenLogin($request);
    }

    public function trySecurityProxyLogin() {
        $user = $this->request->getHeader("sec-username");
        $roles = explode(";", $this->request->getHeader("sec-roles")) ;

        // automatically logs the user as admin if ROLE_ADMINISTRATOR
        if (in_array("ROLE_ADMINISTRATOR", $roles)) {
L            $loginResult = $this->login("admin", "");
            if (($loginResult == false) || ($loginResult == null)) {
                $this->logger->error("Failed to log the admin in.");
            } else {
                $this->logger->info("Logged in as admin.");
                //header("Location: /");
            }
        }
    }

    public function logout() {
        $this->userSession->logout();
        header("Location: /cas/logout?fromgeorchestra");
        die();
    }

    public function registerBackend(Backend $backend)
    {
        $this->userManager->registerBackend($backend);
    }
}