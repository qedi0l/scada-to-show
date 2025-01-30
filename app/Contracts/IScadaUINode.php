<?php

namespace App\Contracts;

use App\Models\MnemoSchema;

interface IScadaUINode
{

    /**
     * Get node hierarchy of parent node
     * @param string $schemaName
     * @param int $parentNodeId
     * @return mixed
     */
    public function showHierarchyByMnemoSchema(string $schemaName, int $parentNodeId): mixed;

    /**
     * Get nodes with property service_type = true
     * @param MnemoSchema $schema
     * @return array
     */
    public function getServiceNodesBySchema(MnemoSchema $schema): array;

    /**
     * Get array of child nodes
     * @param int $nodeId
     * @return mixed
     */
    public function getChildNodes(int $nodeId): mixed;

}
