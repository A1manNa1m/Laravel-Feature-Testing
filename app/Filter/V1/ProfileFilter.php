<?php

namespace App\Filter\V1;

use App\Filter\ApiFilter;

class ProfileFilter extends ApiFilter {

    protected $safeParams = [
        'user_id' => ['eq'],
        'bio' => ['like']
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