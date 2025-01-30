<?php

namespace App\RESTModels;

class NodeParam
{
    public string $title;
    public object $nodes;

    public function __construct(string $title, object $nodes)
    {
        $this->nodes = $nodes;
        $this->title = $title;
    }
}
