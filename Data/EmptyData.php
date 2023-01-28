<?php

namespace Gsdk\Grid\Data;

use Gsdk\Grid\Support\Sorting;
use Gsdk\Navigation\Paginator;

class EmptyData implements DataInterface
{
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
		return true;
	}

	public function get(): iterable
	{
		return [];
	}
}