<?php

namespace App\Filter\V1;

use App\Filter\ApiFilter;

class SkillFilter extends ApiFilter {

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