<?php

namespace olvlvl\ElasticsearchDSL\Query\Term;

use olvlvl\ElasticsearchDSL\Query\QueryAbstract;

/**
 * @property string|null $type
 */
class IdsQuery extends QueryAbstract
{
	const NAME = 'ids';

	/**
	 * @var array
	 */
	private $values;

	/**
	 * @param array $values
	 */
	public function __construct(array $values)
	{
		$this->values = $values;
	}

	/**
	 * @param string|null $type
	 *
	 * @return $this
	 */
	public function type(?string $type)
	{
		$this->options[__FUNCTION__] = $type;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return [ self::NAME => [ 'values' => $this->values ] + parent::jsonSerialize() ];
	}
}
