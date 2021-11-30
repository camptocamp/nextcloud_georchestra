<?php
namespace OCA\Georchestra\Session;


use OCP\IUserSession;

use OC\AppFramework\Http\Request;
use OCP\User;


class GeorchestraSession implements IUserSession {
    private string $user;
    private string $org;
    private array $roles;

    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;

        $this->user = $request->getHeader("sec-username");
        $this->orgs = $request->getHeader("sec-org");
        $this->roles = explode(";", $request->getHeader("sec-roles"));
    }


    public function getUser(): IUser|null {
        if ($this->isLoggedIn()) {
            return $this->user;
        }
        return null;
    }

    public function isLoggedIn() {
        return $this->user !== null or $this->user !== '';
    }

    public function login($uid, $password) : bool {
        if($this->isLoggedIn()) {
            return true;
        }
        return false;
    }

    public function logout() {
        $url = $this->request->getServerProtocol()."://".$request->getServerHost();
        header('Location: ' . $url."/cas/login?service=".$url."/files");
        exit();
    }

    public function setImpersonatingUserID(bool $useCurrentUser = true): void {

    }
    public function getImpersonatingUserID(): ?string {
        return "aaaa";
    }

    public function setUser($user) {
        return true;
    }
}