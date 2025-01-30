<?php

namespace App\Contracts;

use App\Models\MnemoSchema;
use App\RESTModels\NodeParam;

interface IScadaUI
{
    /**
     * Get data for rendering schema
     * @param MnemoSchema $schema
     * @return array
     */
    public function getDataBySchemaID(MnemoSchema $schema): array;

    /**
     * Get array of nodes which has parameter and hardware codes
     * @param string $schemaName
     * @return NodeParam
     */
    public function getNodeParamsBySchemaName(string $schemaName): NodeParam;

    /**
     * Get data for rendering all schemas
     * @return array
     */
    public function getAllMnemoSchemas(): array;

    /**
     * Get array of signals of all schemas
     * @return array
     */
    public function getSignalsOfAllSchemas(): array;

    /**
     * Get array of signals of chosen schema
     * @param string $schemaName
     * @return array
     */
    public function getSignalsOfSingleSchema(string $schemaName): array;

    /**
     * Get array of schema titles and names
     * @return array
     */
    public function getSchemaTitles(): array;

}
