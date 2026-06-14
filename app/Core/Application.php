<?php

namespace App\Core;

use Throwable;

final class Application
{
    public function run(): void
    {
        date_default_timezone_set(Config::app()['timezone']);

        $request = Request::capture();
        $response = new Response();
        $view = new View();

        try {
            (new Router($request, $response, $view))->dispatch();
        } catch (Throwable $throwable) {
            $isAjax = $request->isAjax();
            $message = 'An unexpected error occurred.';

            if ($isAjax) {
                $response->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => [],
                ], 500);

                return;
            }

            $response->html($message, 500);
        }
    }
}
