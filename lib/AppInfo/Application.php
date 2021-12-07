<?php

namespace OCA\Georchestra\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IBootContext;

use OCA\Georchestra\User\Backend;
use OCA\Georchestra\Service\UserService;
use OCA\Georchestra\Service\LoggingService;
use ReflectionObject;

class Application extends App implements IBootstrap {


	public function __construct(array $urlParams = []) {
        parent::__construct('georchestra', $urlParams);

        $container = $this->getContainer();

        $container->registerService('Logger', function ($c) {
            return new LoggingService(
                'georchestra',
                $c->query('ServerContainer')->getLogger()
            );
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

	public function register(IRegistrationContext $context): void {
	}

	public function boot(IBootContext $context) : void {
        $c = $context->getAppContainer();

        $logger = $c->query("Logger");
        $request = $c->query("Request");
        $userService = $c->query('UserService');
        $backend = $c->query('Backend');
        $userService->registerBackend($backend);


        // tries to log the user in using the session
        // (see session->tryTokenLogin(IRequest))
        if (! $userService->isLoggedIn()) {
            $userService->tryTokenLogin($request);
        }

        if ($userService->isLoggedIn()) {
            $logger->debug("User already logged in (session already opened), skipping");
            return;
        }

        // If there are still no user logged in, then try a SP Login
        $userService->trySecurityProxyLogin();

        // Still not in ? redirect to CAS
        if (! $userService->isLoggedIn()) {
            header("Location: https://georchestra-127-0-1-1.traefik.me/files/?login");
            die();
        }

	}
}
