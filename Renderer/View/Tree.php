<?php

namespace Gsdk\Grid\Renderer\View;

use Gsdk\Grid\Grid;
use Gsdk\Grid\Renderer\ColumnRenderer;

class Tree extends Table
{

	private string $parentIndex;

	private string $treeIndent;

	private string $indentColumn;

	public function __construct($options)
	{
		$params = $options['treeConfig'] ?? $options['viewConfig'] ?? $options;

		$this->parentIndex = $params['parentIndex'] ?? 'parent_id';
		$this->treeIndent = $params['treeIndent'] ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
		$this->indentColumn = $params['indentColumn'] ?? 'name';
	}

	protected function renderTBody(Grid $grid): string
	{
		$html = '<tbody>';
		$html .= $this->tree($grid, null);
		$html .= '</tbody>';
		return $html;
	}

	private function tree(Grid $grid, $parentId, $level = 0): string
	{
		$html = '';

		foreach ($grid->getData()->get() as $row) {
			if ($row->{$this->parentIndex} != $parentId)
				continue;

			$html .= '<tr>';
			foreach ($grid->getColumns() as $column) {
				$renderer = new ColumnRenderer($grid, $column);
				$html .= '<td class="' . $renderer->class() . '">';
				if ($column->name == $this->indentColumn)
					$html .= self::indentPad($this->treeIndent, $level);

				$html .= $renderer->text($column, $row);
				$html .= '</td>';
			}
			$html .= '</tr>';

			$html .= $this->tree($grid, $row->id, $level + 1);
		}

		return $html;
	}

	private static function indentPad($indent, $count): string
	{
		return str_repeat($indent, $count);
	}

}