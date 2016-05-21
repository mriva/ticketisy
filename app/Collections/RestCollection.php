<?php

namespace App\Collections;

class RestCollection {

    protected $resource;

    protected $filters;

    protected $actions = [
        'take' => 'setTake',
        'skip' => 'setSkip',
    ];

    protected $take = 30;

    protected $skip = 0;

    public static function get($filters = []) {
        $instance = new static;

        $instance->filters = $filters;
        $instance->filter();
        
        $count = $instance->resource->count();

        $data = $instance->resource->get();

        return [
            'total'    => $count,
            'returned' => count($data),
            'data'     => $data,
        ];
    }

    public function filter() {
        foreach ($this->filters as $action => $value) {
            if (!isset($this->actions[$action])) {
                continue;
            }
            
            $method = $this->actions[$action];
            $this->$method($value);
        }

        $this->take();
        $this->skip();
    }

    public function take() {
        $this->resource = $this->resource->take($this->take);
    }

    public function skip() {
        $this->resource = $this->resource->skip($this->skip);
    }

    public function setTake($value) {
        $this->take = $value;
    }

    public function setSkip($value) {
        $this->skip = $value;
    }

}
