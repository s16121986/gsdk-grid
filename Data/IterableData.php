<?php

namespace Gsdk\Grid\Data;

use Gsdk\Grid\Support\Sorting;
use Gsdk\Navigation\Paginator;

class IterableData implements DataInterface
{
	public function __construct(private readonly iterable $data) { }

	public function paginator(Paginator $paginator): static
	{
		return $this;
	}

	public function sorting(Sorting $sorting): static
	{
		return $this;
	}

	public function isEmpty(): bool
	{
		return empty($this->data);
	}

	public function get(): iterable
	{
		return $this->data;
	}
}