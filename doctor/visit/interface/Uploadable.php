<?php
interface Uploadable {
    public function upload(int $patientId, array $file): ?string;
}
