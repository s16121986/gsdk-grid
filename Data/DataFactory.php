<?php

namespace Gsdk\Grid\Data;

class DataFactory
{
	public static function factory($dataEntity)
	{
		if (EloquentQuery::isEloquentQuery($dataEntity))
			return new EloquentQuery($dataEntity);
		else if (is_iterable($dataEntity))
			return new IterableData($dataEntity);
		else
			return new EmptyData();
	}
}