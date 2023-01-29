<?php

namespace Gsdk\Grid\Support;

use Illuminate\Support\Facades\Request;

class Sorting
{
	const PARAM_ORDERBY = 'orderby';
	const PARAM_SORTORDER = 'sortorder';

	protected ?string $url = null;

	protected ?array $params = null;

	protected ?string $orderby = null;

	protected ?string $sortorder = null;

	public function __construct(array $options = [])
	{
		$set = function ($param, array $gridOptions) use ($options) {
			foreach ($gridOptions as $optionName) {
				if (!array_key_exists($optionName, $options))
					continue;

				$this->$param = $options[$optionName];
				return true;
			}
			return false;
		};

		if (!$set('url', ['orderUrl', 'sortingUrl']))
			$this->url = '/' . Request::path();

		$set('params', ['orderParams', 'sortingParams']);

		$set('orderby', ['orderby']);

		$set('sortorder', ['sortorder']);
	}

	public function __get($name)
	{
		return $this->$name ?? null;
	}

	public function fromRequest(): void
	{
		$params = Request::query();

		$this->params = $params;

		if (isset($params[self::PARAM_ORDERBY]))
			$this->orderby = $params[self::PARAM_ORDERBY];

		if (isset($params[self::PARAM_SORTORDER]))
			$this->sortorder = $params[self::PARAM_SORTORDER] === 'desc' ? 'desc' : 'asc';
	}

	public function orderBy($name, $order = 'asc'): static
	{
		$this->orderby = $name;
		$this->sortorder = $order;
		return $this;
	}

	public function columnUrl($column): string
	{
		$dir = 'asc';
		if ($this->orderby === $column->name)
			$dir = $this->sortorder == 'asc' ? 'desc' : 'asc';

		$q = $this->params;

		$q[self::PARAM_ORDERBY] = $column->name;
		$q[self::PARAM_SORTORDER] = $dir;

		return $this->url . '?' . http_build_query($q);
	}

	public function setUrl(string $url)
	{
		$this->url = $url;
	}

	public function get()
	{
		return $this->orderby ? [
			'orderby' => $this->orderby,
			'sortorder' => $this->sortorder
		] : [];
	}

	public function query($query): void
	{
		if (!$this->orderby)
			return;

		$query->orderBy($this->orderby, $this->sortorder ?? 'asc');
	}
}
