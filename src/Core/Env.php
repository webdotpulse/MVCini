<?php

namespace App\Core;

class Env
{
    /**
     * Parse a .env file and load the variables into the environment.
     *
     * @param string $path Path to the .env file.
     * @return void
     */
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            // Trim whitespace
            $line = trim($line);

            // Ignore comments and empty lines
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            // Split on the first equals sign
            if (str_contains($line, '=')) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // Strip quotes if they exist around the value
                if (preg_match('/^([\'"])(.*)\1$/', $value, $matches)) {
                    $value = $matches[2];
                }

                // Set the environment variables
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
