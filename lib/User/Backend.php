<?php

namespace OCA\Georchestra\User;
use OC\User\Database;
use OCP\User\Backend\ABackend;
use OCP\IUserBackend;
use OCP\User\Backend\ICheckPasswordBackend;

use OCA\Georchestra\Service\LoggingService;

class Backend extends Database implements IUserBackend, ICheckPasswordBackend
{
	private $logger;

    public function __construct(LoggingService $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    public function getBackendName()
    {
        return "GEORCHESTRA";
    }

    public function checkPassword(string $loginName, string $password) {
        return $loginName;
    }
    
    public function getRealUID(string $uid): string
    {

        return $uid;
    }
}