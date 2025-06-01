<?php
interface Validatable {
    public function validate(array $data): bool;
}
