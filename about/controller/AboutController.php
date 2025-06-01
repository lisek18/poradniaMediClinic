<?php
require_once __DIR__ . '/../abstract/AbstractController.php';
require_once __DIR__ . '/../traits/SanitizerTrait.php';
require_once __DIR__ . '/../model/AboutSection.php';

class AboutController extends AbstractController {
    use SanitizerTrait;

    private AboutSection $model;

    public function __construct() {
        $this->model = new AboutSection();
    }

    public function handle(): void {
        session_start();

        $rawSections = $this->model->render();

        $sections = array_map(function ($item) {
            return [
                'title' => ucfirst($this->clean($item['title'])),
                'desc'  => $this->clean($item['desc'])
            ];
        }, $rawSections);

        require __DIR__ . '/../view/about_view.php';
    }
}
