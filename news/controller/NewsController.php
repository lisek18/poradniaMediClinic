<?php
require_once __DIR__ . '/../model/NewsModel.php';
require_once __DIR__ . '/../abstract/AbstractController.php';
require_once __DIR__ . '/../traits/SanitizerTrait.php';

class NewsController extends AbstractController {
    use SanitizerTrait;

    private NewsModel $model;

    public function __construct(NewsModel $model) {
        $this->model = $model;
    }

    public function handle(): void {
        session_start();

        if (isset($_SESSION['user_id']) && isset($_GET['seen'])) {
            $this->model->markAsSeen((int) $_SESSION['user_id']);
        }

        $news = array_map(function ($item) {
            return [
                'title' => $this->escape($item['title']),
                'content' => nl2br($this->escape($item['content'])),
                'published_at' => date("d.m.Y", strtotime($item['published_at']))
            ];
        }, $this->model->loadData());

        require __DIR__ . '/../view/news_view.php';
    }
}
