<?php

namespace App\Filter\V1;

use App\Filter\ApiFilter;

class CountryFilter extends ApiFilter {

    protected $safeParams = [
        'name' => ['eq','ne','like'],
    ];

    protected $columnMap = [
        '' => '',
        '' => '',
        '' => ''
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
        'like' => 'LIKE'
    ];
}