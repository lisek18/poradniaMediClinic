<?php
trait SanitizerTrait {
    public function escape(string $input): string {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
