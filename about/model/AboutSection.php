<?php
require_once __DIR__ . '/../interface/Renderable.php';

class AboutSection implements Renderable {
    public function render(): array {
        return [
            ['title' => 'Profesjonalizm', 'desc' => 'Dbamy o najwyższe standardy leczenia i komunikacji z pacjentem.'],
            ['title' => 'Empatia', 'desc' => 'Stawiamy pacjenta w centrum uwagi – jego potrzeby są naszym priorytetem.'],
            ['title' => 'Nowoczesność', 'desc' => 'Inwestujemy w innowacyjne technologie, by świadczyć usługi na najwyższym poziomie.'],
        ];
    }
}
