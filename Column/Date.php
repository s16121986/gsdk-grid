<?php

namespace Gsdk\Grid\Column;

class Date extends AbstractColumn {

	protected array $options = [
		'format' => 'd.m.Y'
	];

	public function formatValue($value, $row = null) {
		return parent::formatValue(app('format')->date($value, $this->format), $row);
	}

}
