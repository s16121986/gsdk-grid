<?php

namespace Gsdk\Grid\Renderer;

use Gsdk\Grid\Column\ColumnInterface;
use Gsdk\Grid\Grid;

class ColumnRenderer
{
	public function __construct(
		private readonly Grid $grid,
		private readonly ColumnInterface $column
	) {
	}

	public function th(): string
	{
		$sorting = $this->grid->getSorting();
		$column = $this->column;

		$html = '<th class="' . $this->class() . '">';
		if ($column->order) {
			$html .= '<div class="column-inner">';
			$html .= '<a href="' . $sorting->columnUrl($column) . '">';
			$html .= $column->text;
			$html .= '</a>';
			if ($sorting->orderby == $column->name)
				$html .= '<div class="grid-sorted-arrow"></div>';

			$html .= '</div>';
		}
		else
			$html .= $column->text;

		$html .= '</th>';

		return $html;
	}

	public function td($row): string
	{
		$html = '<td class="' . $this->class() . '">';
		//$dataValue = isset($row->{$column->name}) ? $row->{$column->name} : null;
		$html .= $this->text($row);
		$html .= '</td>';
		return $html;
	}

	public function text($row)
	{
		$column = $this->column;
		$dataValue = $row->{$column->name} ?? null;

		if ($column->renderer)
			$columnValue = call_user_func_array($column->renderer, [$row, $column->formatValue($dataValue, $row), $column->params]);
		else if (method_exists($column, 'renderer'))
			$columnValue = call_user_func_array([$column, 'renderer'], [$row, $dataValue, $column->params]);
		else
			$columnValue = $column->formatValue($dataValue, $row);

		if (null === $columnValue || '' === $columnValue)
			return $column->emptyText;

		if ($column->href) {
			$href = preg_replace_callback('/{(.+)}/', function ($m) use ($row) {
				return $row->{$m[1]} ?? '';
			}, $column->href);
			return '<a href="' . $href . '"' . ($column->hrefTarget ? ' target="' . $column->hrefTarget . '"' : '') . '>' . $columnValue . '</a>';
		}

		return $columnValue;
	}

	public function class(): string
	{
		$sorting = $this->grid->getSorting();
		$column = $this->column;

		$cls = 'column-' . $column->type;

		if ($column->type !== $column->name)
			$cls = ' column-' . $column->name;

		if ($column->class)
			$cls .= ' ' . $column->class;

		if ($column->order && $sorting->orderby == $column->name)
			$cls .= ' column-sorted column-sorted-' . $sorting->sortorder;

		return $cls;
	}
}