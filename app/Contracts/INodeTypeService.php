<?php

namespace App\Contracts;

use App\Models\MnemoSchemaNodeType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface INodeTypeService
{
    /**
     * Get available node types
     * @return array
     */
    public function getNodeTypes(): array;

    /**
     * Create new node type
     * @param Request $request
     * @return Model|MnemoSchemaNodeType
     */
    public function createNodeType(Request $request): Model|MnemoSchemaNodeType;

    /**
     * Update selected node type
     * @param Request $request
     * @return Model|Collection|array|MnemoSchemaNodeType|null
     */
    public function updateNodeType(Request $request): Model|Collection|array|MnemoSchemaNodeType|null;

    /**
     * Delete selected node type
     * @param int $nodeTypeId
     * @return void
     */
    public function deleteNodeType(int $nodeTypeId): void;
}
