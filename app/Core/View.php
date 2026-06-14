<?php

namespace App\Core;

use RuntimeException;

final class View
{
    public function render(string $template, array $data = [], string $layout = 'layout'): string
    {
        $content = $this->renderTemplate($template, $data);

        return $this->renderTemplate('Core/Templates/' . $layout, array_merge($data, ['content' => $content]));
    }

    public function partial(string $template, array $data = []): string
    {
        return $this->renderTemplate($template, $data);
    }

    private function renderTemplate(string $template, array $data): string
    {
        $path = dirname(__DIR__) . '/' . $template . '.php';

        if (!is_file($path)) {
            throw new RuntimeException(sprintf('View template [%s] was not found.', $template));
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $path;

        return (string) ob_get_clean();
    }
}
