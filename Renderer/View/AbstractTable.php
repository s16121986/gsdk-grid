<?php

namespace Gsdk\Grid\Renderer\View;

use Gsdk\Grid\Grid;

abstract class AbstractTable
{

	public function render(Grid $grid): string
	{
		if ($grid->getData()->isEmpty())
			return '<div class="grid-empty-text">' . $grid->getOption('emptyText') . '</div>';

		$html = $this->renderTable($grid);

		if (($paginator = $grid->getPaginator()))
			$html .= $paginator->render();

		return $html;
	}

	public function initFeatures(): array
	{
		if (!$this->grid->features)
			return [];

		$features = [];
		$featuresTemp = $this->grid->features;
		if (!is_array($featuresTemp))
			$featuresTemp = [$featuresTemp];

		foreach ($featuresTemp as $name) {
			if (!preg_match('/^[a-z_]+$/i', $name))
				throw new \Exception('Invalid feature');

			$cls = 'Gsdk\Grid\Feature\\' . ucfirst($name);
			if (!class_exists($cls, true))
				throw new \Exception('Feature not exists');

			$features[$name] = new $cls($this->grid);
		}

		return $features;
	}

	abstract protected function renderTable(Grid $grid): string;

}
