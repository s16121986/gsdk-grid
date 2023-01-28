<?php

namespace Gsdk\Grid\Concerns;

use Gsdk\Grid\Column\ColumnInterface;

trait HasColumns
{

	protected array $columns = [];

	public function __call(string $name, array $arguments)
	{
		if (!isset($arguments[0]))
			throw new \ArgumentCountError('Column name required');

		$this->addColumn($arguments[0], $name, $arguments[1] ?? []);
	}

	public function addColumn(
		ColumnInterface|string $column,
		string|array $type = 'text',
		array $options = []
	): static {
		if (is_array($type)) {
			$options = $type;
			$type = 'text';
		}

		if (is_string($column))
			$column = static::columnFactory($column, $type, $options);

		$this->columns[] = $column;

		return $this;
	}

	public function getColumns(): array
	{
		return $this->columns;
	}

	public function getColumn(string $name)
	{
		foreach ($this->columns as $column) {
			if ($column->name === $name)
				return $column;
		}

		return null;
	}

	public function hasColumn(string $name): bool
	{
		foreach ($this->columns as $column) {
			if ($column->name === $name)
				return true;
		}

		return false;
	}

}