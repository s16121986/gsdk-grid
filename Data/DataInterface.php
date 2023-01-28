<?php

namespace Gsdk\Grid\Data;

use Gsdk\Grid\Support\Sorting;
use Gsdk\Navigation\Paginator;

interface DataInterface
{
	public function paginator(Paginator $paginator): static;

	public function sorting(Sorting $sorting): static;

	public function isEmpty(): bool;

	public function get(): iterable;
}