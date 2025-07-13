<?php

namespace App\Filter\V1;

use App\Filter\ApiFilter;

class CommentFilter extends ApiFilter {

    protected $safeParams = [
        'user_id' => ['eq'],
        'commentable_id' => ['eq','ne'],
        'commentable_type' => ['eq','ne'],
        'body' => ['eq','ne'],
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