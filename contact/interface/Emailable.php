<?php
interface Emailable {
    public function send(string $name, string $email, string $message): bool;
}
