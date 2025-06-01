<?php
trait SanitizerTrait {
    public function clean(string $value): string {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
}
