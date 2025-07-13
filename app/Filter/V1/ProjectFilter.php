<?php

namespace App\Filter\V1;

use App\Filter\ApiFilter;

class ProjectFilter extends ApiFilter {

    protected $safeParams = [
        'user_id' => ['eq'],
        'title' => ['eq','ne'],
        'description' => ['eq','ne'],
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
        'ne' => '!='
    ];
}