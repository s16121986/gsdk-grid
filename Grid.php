<?php

namespace Gsdk\Grid;

use Gsdk\Navigation\Paginator;

class Grid {

	use Concerns\HasExtensions,
		Concerns\HasColumns;

	protected Support\Options $options;

	protected Support\Sorting $sorting;

	protected Renderer\Renderer $renderer;

	protected Data\DataInterface $data;

	protected $paginator;

	public function __construct($options = []) {
		$this->options = new Support\Options($options);
		$this->sorting = new Support\Sorting($options);
		$this->renderer = new Renderer\Renderer($options);
		$this->data = new Data\EmptyData();
	}

	public function __call(string $name, array $arguments) {
		if (!isset($arguments[0]))
			throw new \ArgumentCountError('Argument required');

		if ($this->options->hasOption($name))
			$this->options->set($name, $arguments[0] ?? null);
		else {
			$id = $arguments[0];
			unset($arguments[0]);
			$this->addColumn($id, $name, $arguments);
		}

		return $this;
	}

	public function getOption(string $name) {
		return $this->options->get($name);
	}

	public function getSorting(): Support\Sorting {
		return $this->sorting;
	}

	public function paginator(int|Paginator $paginator = null): static {
		if (null === $paginator) {
			$this->paginator = new Paginator();
		} else if (is_numeric($paginator))
			$this->paginator = new Paginator($paginator);
		else
			$this->paginator = $paginator;

		return $this;
	}

	public function getPaginator(): ?Paginator {
		return $this->paginator;
	}

	public function data($data): static {
		$this->data = Data\DataFactory::factory($data);

		return $this;
	}

	public function getData(): Data\DataInterface {
		return $this->data;
	}

	public function orderBy($name, $order = 'asc'): static {
		$this->sorting->orderBy($name, $order);

		return $this;
	}

	public function render(): string {
		$this->prepareData();

		return $this->renderer->render($this);
	}

	public function __toString(): string {
		return $this->render();
	}

	protected function prepareData(): void {
		$this->data
			->paginator($this->paginator)
			->sorting($this->sorting);
	}

}
