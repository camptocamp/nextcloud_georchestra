<?php
namespace OCA\Georchestra\Controller;

use OCP\AppFramework\Http\TemplateResponse;
use \OCP\IRequest;
use \OCP\AppFramework\Http\RedirectResponse;
use \OCP\AppFramework\Controller;
use \OCP\IConfig;
use \OCP\IUserSession;

class AuthenticationController extends Controller {
    /**
     * @var string $appName
     */
    protected $appName;

    /**
     * @var \OCP\IConfig $config
     */
    private $config;

    /**
     * @var \OCA\UserCAS\Service\UserService $userService
     */
    private $userService;

    /**
     * @var \OCA\UserCAS\Service\AppService $appService
     */
    private $appService;

    /**
     * @var IUserSession $userSession
     */
    private $userSession;

    /**
     * @var \OCA\UserCAS\Service\LoggingService $loggingService
     */
    private $loggingService;

    /**
     * AuthenticationController constructor.
     * @param $appName
     * @param IRequest $request
     * @param IConfig $config
     * @param UserService $userService
     * @param AppService $appService
     * @param IUserSession $userSession
     * @param LoggingService $loggingService
     */
    public function __construct($appName, IRequest $request, IConfig $config, UserService $userService, AppService $appService, IUserSession $userSession, LoggingService $loggingService)
    {
        $this->appName = $appName;
        $this->config = $config;
        $this->userService = $userService;
        $this->appService = $appService;
        $this->userSession = $userSession;
        $this->loggingService = $loggingService;
        parent::__construct($appName, $request);
    }
   

    public function georchestraLogin() {
    }

    public function georchestraLogout() {
        $url = $this->request->getServerProtocol()."://".$this->request->getServerHost()."/cas/logout";
        return $this->redirect($url);
    }
}