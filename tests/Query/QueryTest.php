<?php

namespace olvlvl\ElasticsearchDSL\Query;

use olvlvl\ElasticsearchDSL\Query;

class QueryTest extends QueryTestCase
{
	public function provideSerialization(): array
	{
		return [

			[ function (Query $query) {
				$query->match_all();

				return <<<JSON
{
    "query": {
        "match_all": {}
    }
}
JSON;

			} ],

			[ function (Query $query) {
				$query->bool->must
					->match("preference_1", "Apples");

				return <<<EOT
{
    "query": {
        "bool": {
            "must": {
                "match": {
                    "preference_1": "Apples"
                }
            }
        }
    }
}
EOT;
			} ],

			[ function (Query $query) {
				$query->bool->must
					->match("preference_1", "Apples")
					->match("preference_2", "Bananas");

				return <<<EOT
{
    "query": {
        "bool": {
            "must": [
                {
                    "match": {
                        "preference_1": "Apples"
                    }
                },
                {
                    "match": {
                        "preference_2": "Bananas"
                    }
                }
            ]
        }
    }
}
EOT;
			} ],

			[ function (Query $query) {
				$query->bool->must_not
					->match("preference_1", "Apples");

				return <<<EOT
{
    "query": {
        "bool": {
            "must_not": {
                "match": {
                    "preference_1": "Apples"
                }
            }
        }
    }
}
EOT;

			} ],

			[ function (Query $query) {
				$query->bool->should
					->match("preference_1", "Apples")
					->match("preference_2", "Bananas");

				return <<<EOT
{
    "query": {
        "bool": {
            "should": [
                {
                    "match": {
                        "preference_1": "Apples"
                    }
                },
                {
                    "match": {
                        "preference_2": "Bananas"
                    }
                }
            ]
        }
    }
}
EOT;
			} ],

			[ function (Query $query) {
				$query->bool->should->bool()->must
					->match("preference_1", "Apples")
					->match("preference_2", "Bananas");

				$query->bool->should->bool()->must
					->match("preference_1", "Apples")
					->match("preference_2", "Bananas");

				$query->bool->should->match("preference_1", "Grapefruit");

				return <<<EOT
{
    "query": {
        "bool": {
            "should": [
                {
                    "bool": {
                        "must": [
                            {
                                "match": {
                                    "preference_1": "Apples"
                                }
                            },
                            {
                                "match": {
                                    "preference_2": "Bananas"
                                }
                            }
                        ]
                    }
                },
                {
                    "bool": {
                        "must": [
                            {
                                "match": {
                                    "preference_1": "Apples"
                                }
                            },
                            {
                                "match": {
                                    "preference_2": "Bananas"
                                }
                            }
                        ]
                    }
                },
                {
                    "match": {
                        "preference_1": "Grapefruit"
                    }
                }
            ]
        }
    }
}
EOT;

			} ],

			[ function (Query $query) {
				$query->bool->should->bool()->must
					->match("preference_1", "Apples")
					->match("preference_2", "Bananas");
				$query->bool->should->bool()->must
					->match("preference_1", "Apples")
					->match("preference_2", "Cherries");
				$query->bool->should->match("preference_1", "Grapefruit");
				$query->bool->filter->term("grade", "2");

				return <<<JSON
{
    "query": {
        "bool": {
            "filter": {
                "term": {
                    "grade": "2"
                }
            },
            "should": [
                {
                    "bool": {
                        "must": [
                            {
                                "match": {
                                    "preference_1": "Apples"
                                }
                            },
                            {
                                "match": {
                                    "preference_2": "Bananas"
                                }
                            }
                        ]
                    }
                },
                {
                    "bool": {
                        "must": [
                            {
                                "match": {
                                    "preference_1": "Apples"
                                }
                            },
                            {
                                "match": {
                                    "preference_2": "Cherries"
                                }
                            }
                        ]
                    }
                },
                {
                    "match": {
                        "preference_1": "Grapefruit"
                    }
                }
            ]
        }
    }
}
JSON;

			} ],

			[ function (Query $query) {
				$query->bool->must
					->term("user", "kimchy");
				$query->bool->must_not
					->range("age", function (Query\Term\RangeQuery $range) {
						$range->from(10)->to(10);
					});
				$query->bool->should
					->term("tag", "wow")
					->term("tag", "elasticsearch");
				$query->bool
					->minimum_should_match(1)
					->boost(1.5);

				return <<<JSON
{
    "query": {
        "bool": {
            "must": {
                "term": {
                    "user": "kimchy"
                }
            },
            "should": [
                {
                    "term": {
                        "tag": "wow"
                    }
                },
                {
                    "term": {
                        "tag": "elasticsearch"
                    }
                }
            ],
            "must_not": {
                "range": {
                    "age": {
                        "from": 10,
                        "to": 10
                    }
                }
            },
            "minimum_should_match": 1,
            "boost": 1.5
        }
    }
}
JSON;

			} ],

			[ function (Query $query) {
				$query->nested("menus")
					->score_mode('avg')
					->ignore_unmapped(true)
					->query->bool->filter
						->term("menus.week", "2018-W03")
						->term("menus.product", "express-box");

				return <<<JSON
{
    "query": {
        "nested": {
            "path": "menus",
            "score_mode": "avg",
            "ignore_unmapped": true,
            "query": {
                "bool": {
                    "filter": [
                        {
                            "term": {
                                "menus.week": "2018-W03"
                            }
                        },
                        {
                            "term": {
                                "menus.product": "express-box"
                            }
                        }
                    ]
                }
            }
        }
    }
}
JSON;
			} ],

		];
	}

	protected function makeInstance()
	{
		return new Query();
	}
}
