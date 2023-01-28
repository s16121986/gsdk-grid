<?php

namespace Gsdk\Grid\Renderer;

use Gsdk\Grid\Grid;

class Renderer
{
	protected View\AbstractTable $view;

	public function __construct(array $options = [])
	{
		if (isset($options['renderer']))
			$this->view = $this->viewFactory($options['renderer'], $options);
		else if (isset($options['view']))
			$this->view = $this->viewFactory('view', $options);
		else
			$this->view = $this->viewFactory('table', $options);
	}

	public function render(Grid $grid): string
	{
		return $this->view->render($grid);
	}

	private function viewFactory($type, $options)
	{
		$class = __NAMESPACE__ . '\View\\';
		$class .= match (strtolower($type)) {
			'tree' => 'Tree',
			'table' => 'Table',
			'view' => 'View',
			default => throw new \Exception('Unavailable renderer type')
		};

		return new $class($options);
	}
}