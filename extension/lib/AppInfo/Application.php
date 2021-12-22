<?php

namespace OCA\Georchestra\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IBootContext;

use OCA\Georchestra\User\Backend;
use OCA\Georchestra\Service\UserService;
use ReflectionObject;

class Application extends App implements IBootstrap {


	public function __construct(array $urlParams = []) {
        parent::__construct('georchestra', $urlParams);

        // $container = $this->getContainer();

        // $container->registerService('Logger', function ($c) {
        //     return $c->query('ServerContainer')->getLogger();
        // });

        // $container->registerService('Backend', function ($c) {
        //     return new Backend($c->query('Logger'));
        // });

        // $container->registerService('UserService', function($c) {
        //     return new UserService(
        //         $c->query('ServerContainer')->getUserSession(),
        //         $c->query('ServerContainer')->getUserManager(),
        //         $c->query('Request'),
        //         $c->query('ServerContainer')->getLogger()
        //     );
        // });

        // $container->registerService('User', function($c) {
        //     return $c->query('UserSession')->getUser();
        // });
	}

	public function register(IRegistrationContext $container): void {
        $container->registerService('Logger', function ($c) {
            return $c->query('ServerContainer')->getLogger();
        });
        $container->registerService('Backend', function ($c) {
            return new Backend($c->query('Logger'));
        });
        $container->registerService('UserService', function($c) {
            return new UserService(
                $c->query('ServerContainer')->getUserSession(),
                $c->query('ServerContainer')->getUserManager(),
                $c->query('Request'),
                $c->query('ServerContainer')->getLogger()
            );
        });
        $container->registerService('User', function($c) {
            return $c->query('UserSession')->getUser();
        });

	}

	public function boot(IBootContext $context) : void {
        $c = $context->getAppContainer();

        $logger = $c->query("Logger");
        $request = $c->query("Request");
        $userSession = $c->query("UserSession");
        $userService = $c->query('UserService');
        $backend = $c->query('Backend');
        $userService->register();
        $userService->registerBackend($backend);

        $usr = $userSession->getUser();

        // tries to log the user in using the session
        // (see session->tryTokenLogin(IRequest))
        if (! $userSession->isLoggedIn()) {
            $userSession->tryTokenLogin($request);
        }

        if ($userSession->isLoggedIn()) {
            $logger->info("User already logged in (session already opened), skipping");
            if (! $request->passesCSRFCheck()) {
                $logger->info("request does not pass CSRF checks !");
            }
            return;
        }

        $user = $request->getHeader("sec-username");
        $roles = explode(";", $request->getHeader("sec-roles")) ;
        if(!$user){
            header("Location: " . $request->getServerProtocol() . '://' . $request->getServerHost() . '/cas/login');
            exit();
        }
        // automatically logs the user as admin if ROLE_ADMINISTRATOR
        if (in_array("ROLE_ADMINISTRATOR", $roles)) {
            $user = "admin";
        }
        $loginResult = $userService->login($user, "");

        if (($loginResult == false) || ($loginResult == null)) {
            $logger->info("Failed to log the user " . $user . " in.");
            header("Location: " . $request->getServerProtocol() . '://' . $request->getServerHost() . '/cas/login');
            exit();
        } else {
            $logger->info("Logged in as" . $user);
        }

	}
}
