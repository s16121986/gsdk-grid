<?php

namespace Gsdk\Grid\Column;

class Image extends AbstractColumn {

	public function formatValue($value, $row = null) {
		if ($value)
			return '<img src="/file/' . $value . '/" alt="">';

		return '';
	}
}
