<?php

namespace App\Core;

/**
 * Basic Logging system that writes messages to a rotating file in storage/logs.
 */
class Logger
{
    /**
     * @var string
     */
    protected static $logDir = __DIR__ . '/../../storage/logs';

    /**
     * Ensure the log directory exists.
     */
    protected static function ensureDirectoryExists()
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }

    /**
     * Get the log file path for today.
     *
     * @return string
     */
    protected static function getLogFile(): string
    {
        self::ensureDirectoryExists();
        $date = date('Y-m-d');
        return self::$logDir . "/app-{$date}.log";
    }

    /**
     * Log a message with an arbitrary level.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function log(string $level, string $message, array $context = []): void
    {
        $logFile = self::getLogFile();

        $timestamp = date('Y-m-d H:i:s');
        $level = strtoupper($level);

        $contextString = '';
        if (!empty($context)) {
            $contextString = ' ' . json_encode($context);
        }

        $formattedMessage = sprintf(
            "[%s] %s: %s%s" . PHP_EOL,
            $timestamp,
            $level,
            $message,
            $contextString
        );

        file_put_contents($logFile, $formattedMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * Log an info message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        self::log('info', $message, $context);
    }

    /**
     * Log a warning message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        self::log('warning', $message, $context);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        self::log('error', $message, $context);
    }

    /**
     * Log a debug message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function debug(string $message, array $context = []): void
    {
        self::log('debug', $message, $context);
    }
}
