<?php

namespace SPF\View;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class View
{
    /**
     * @var
     */
    protected $engine;

    /**
     * @param string $view
     * @param array $options
     */
    public function __construct(
      protected string $view,
      protected array $options = []
    )
    {
        $this->init();
        $this->render($this->view);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @return void
     */
    protected function init()
    {
        $loader = new FilesystemLoader($this->getTemplatesDirectory());

        $this->engine = new Environment($loader, [
          'cache'       => $this->getCacheDirectory(),
          'auto_reload' =>  true,
        ]);
    }

    /**
     * Returns templates directory's path.
     *
     * @return string
     */
    protected function getTemplatesDirectory(): string
    {
        return basePath() . '/app/Views';
    }

    /**
     * Returns template cache's directory.
     *
     * @return string
     */
    protected function getCacheDirectory(): string
    {
        return basePath() . '/src/cache/templates';
    }

    /**
     * Renders specific view.
     *
     * @return mixed
     */
    protected function render()
    {
        $view = str_ends_with($this->view, '.html') == '.html' ? $this->view : $this->view . '.html';

        return $this->engine->render($view, $this->options);
    }
}
