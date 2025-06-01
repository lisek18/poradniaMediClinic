<?php
interface Updatable {
    public function updateVisit(int $visitId, array $data): bool;
}
