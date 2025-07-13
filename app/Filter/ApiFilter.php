<?php

namespace App\Filter;

use Illuminate\Http\Request;

class ApiFilter {
    protected $safeParams = [];

    protected $columnMap = [];

    protected $operatorMap = [];

    public function transform(Request $request) {
        $eloQuery = [];

        foreach ($this->safeParams as $parm => $operators) {
            $query = $request->query($parm);

            if(!isset($query)){
                continue;
            }

            $column = $this->columnMap[$parm] ?? $parm;

            foreach ($operators as $operator){
                if(isset($query[$operator])){
                    $value = $query[$operator];
                    if ($this->operatorMap[$operator] === 'LIKE') {
                        $value = "%$value%"; // Add wildcard for partial match
                    }
                    $eloQuery[]= [$column, $this->operatorMap[$operator], $value];
                }
            }
        }
        return $eloQuery;
    }
}