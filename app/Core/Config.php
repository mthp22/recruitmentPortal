<?php

namespace App\Core;

final class Config
{
    public static function app(): array
    {
        return [
            'name' => 'Job Application Portal',
            'base_url' => self::env('APP_BASE_URL', '/public'),
            'timezone' => self::env('APP_TIMEZONE', 'Africa/Johannesburg'),
        ];
    }

    public static function database(): array
    {
        return [
            'host' => self::env('DB_HOST', '127.0.0.1'),
            'port' => self::env('DB_PORT', '3306'),
            'name' => self::env('DB_NAME', 'job_app'),
            'username' => self::env('DB_USERNAME', 'root'),
            'password' => self::env('DB_PASSWORD', ''),
            'charset' => self::env('DB_CHARSET', 'utf8mb4'),
        ];
    }

    public static function uploads(): array
    {
        return [
            'cv_directory' => dirname(__DIR__, 2) . '/uploads/cvs',
            'cv_web_path' => 'uploads/cvs',
            'max_size' => 4 * 1024 * 1024,
            'allowed_extensions' => ['pdf', 'doc', 'docx', 'txt', 'bmp', 'png', 'jpeg', 'jpg'],
            'allowed_mime_types' => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'text/plain',
                'image/bmp',
                'image/png',
                'image/jpeg',
            ],
        ];
    }

    private static function env(string $key, string $default): string
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        return is_string($value) && $value !== '' ? $value : $default;
    }
}
