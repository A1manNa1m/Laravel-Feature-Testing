<?php

namespace App\Filter\V1;

use App\Filter\ApiFilter;

class SkillFilter extends ApiFilter {

    protected $safeParams = [
        '' => ['eq'],
        '' => ['eq','lt','lte','gt','gte'],
        '' => ['eq','ne'],
        '' => ['eq','lt','lte','gt','gte'],
        '' => ['eq','lt','lte','gt','gte']
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