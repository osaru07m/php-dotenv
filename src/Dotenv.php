<?php

namespace Osaru07m\Dotenv;

class Dotenv {
    /**
     * Load environment variables from a file.
     *
     * @param string $filePath The path to the environment file.
     * @param bool $changeable Whether the environment variables can be changed after being set (default is false).
     * @return void
     * @throws \Exception If the file does not exist.
     */
    public static function load(string $filePath, bool $changeable = false): void {
        if (!is_file($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $row) {
            if (str_contains($row, '=')) {
                [$key, $value] = explode('=', $row, 2);

                $key = strtoupper($key);
                $value = trim($value);

                $value = self::castValue($value);

                if (!$changeable && !is_null(self::get($key))) {
                    continue;
                }

                self::add($key, $value);
            }
        }
    }

    /**
     * Cast the value to the appropriate type.
     *
     * @param string $value The value to be cast.
     * @return mixed The casted value.
     */
    private static function castValue(string $value): mixed
    {
        if ($value === '') {
            return null;
        }

        if ($value[0] === '"' && $value[strlen($value) - 1] === '"') {
            return $value = substr($value, 1, -1);
        }

        if (strcasecmp($value, 'TRUE') === 0 || strcasecmp($value, 'FALSE') === 0) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float) $value : (int) $value;
        }

        return $value;
    }

    /**
     * Get the value of an environment variable.
     *
     * @param string $key The key of the environment variable.
     * @param mixed $default The default value to return if the key is not set (default is null).
     * @return mixed The value of the environment variable or the default value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $key = strtoupper($key);

        $env = array_merge($_ENV, $_SERVER);

        return $env[$key] ?? $default;
    }

    /**
     * Get all environment variables.
     *
     * @return array An associative array of all environment variables.
     */
    public static function getAll(): array
    {
        return array_merge($_ENV, $_SERVER);
    }

    /**
     * Add an environment variable.
     *
     * @param string $key The key of the environment variable.
     * @param mixed $value The value of the environment variable.
     * @param bool $changeable Whether the environment variable can be changed after being set (default is false).
     * @return void
     */
    public static function add(string $key, mixed $value, bool $changeable = false): void
    {
        $key = strtoupper($key);

        if (!$changeable && !is_null(self::get($key))) {
            return;
        }

        $_ENV[$key] = $value;
        ksort($_ENV);

        $_SERVER[$key] = $value;
        ksort($_SERVER);

        $value = (string) $value;
        putenv("{$key}={$value}");
    }
}

?>