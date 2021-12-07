<?php

namespace OCA\Georchestra\Service;

use OCP\ILogger;

class LoggingService implements ILogger
{

    private ILogger $delegate;
    private $context = [];

    public function __construct($appName, $delegate) {
        $this->context['app'] = $appName;
        $this->delegate = $delegate;
    }

    public function emergency(string $message, array $context = []) {
        return $this->delegate->emergency($message, array_merge($context, $this->context));
    }

    public function alert(string $message, array $context = []) {
        return $this->delegate->alert($message, array_merge($context, $this->context));
    }

    public function critical(string $message, array $context = []) {
        return $this->delegate->critical($message, array_merge($context, $this->context));
    }

    public function error(string $message, array $context = []) {
        return $this->delegate->error($message, array_merge($context, $this->context));
    }

    public function warning(string $message, array $context = []) {
        return $this->delegate->warning($message, array_merge($context, $this->context));
    }

    public function notice(string $message, array $context = []) {
        return $this->delegate->notice($message, array_merge($context, $this->context));
    }

    public function info(string $message, array $context = []) {
        return $this->delegate->info($message, array_merge($context, $this->context));
    }

    public function debug(string $message, array $context = []) {
        return $this->delegate->debug($message, array_merge($context, $this->context));
    }

    public function log(int $level, string $message, array $context = []) {
        return $this->delegate->log($level, $message, array_merge($context, $this->context));
    }

    public function logException(\Throwable $exception, array $context = []) {
        return $this->delegate->logException($exception, array_merge($context, $this->context));
    }
}