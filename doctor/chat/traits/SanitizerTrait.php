<?php
trait SanitizerTrait {
    public function clean(string $data): string {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}
