<?php

namespace App\Services;

class EmailTemplateRenderer
{
    public function render(?string $template, array $variables): string
    {
        $template = (string) $template;

        foreach ($variables as $key => $value) {
            $template = str_replace('{' . $key . '}', (string) $value, $template);
        }

        return nl2br(e($template), false);
    }
}
