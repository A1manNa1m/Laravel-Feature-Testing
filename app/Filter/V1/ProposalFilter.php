<?php

namespace App\Filter\V1;

use App\Filter\ApiFilter;

class ProposalFilter extends ApiFilter {

    protected $safeParams = [
        'user_id' => ['eq'],
        'project_id' => ['eq'],
        'cover_letter' => ['like'],
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