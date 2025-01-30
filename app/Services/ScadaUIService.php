<?php

namespace App\Services;

use App;
use App\Contracts\IScadaUI;
use App\Http\Resources\Schemas\MnemoSchemaResource;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeOptions;
use App\RESTModels\NodeParam;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class ScadaUIService implements IScadaUI
{
    /**
     * @param MnemoSchema $schema
     * @return array[]
     */
    public function getDataBySchemaID(MnemoSchema $schema): array
    {
        return [
            'Schema' => $this->getSchemaData($schema)
        ];
    }

    /**
     * Get data of schema for SCADA UI
     * @param MnemoSchema $schema
     * @return array
     */
    private function getSchemaData(MnemoSchema $schema): array
    {
        $schema
            ->load([
                'lines.options',
                'lines.appearance',
                'nodes' => function (HasMany $builder) {
                    $builder->with(['node_type', 'options.node' => ['appearance', 'geometry'], 'appearance']);
                },
                'service_nodes' => function (HasMany $builder) {
                    $builder->with(['node_type', 'options.node' => ['options', 'appearance'], 'appearance']);
                },
                'parent_nodes' => function (HasMany $builder) {
                    $builder
                        ->with('children_options');
                }
            ])
            ->firstOrFail();

        return MnemoSchemaResource::make($schema)->toArray(new Request());
    }

    /**
     * @param string $schemaName
     * @return NodeParam
     */
    public function getNodeParamsBySchemaName(string $schemaName): NodeParam
    {
        $mnemoSchema = MnemoSchema::where('name', $schemaName)->firstOrFail();
        $nodes = MnemoSchemaNode::whereSchemaId($mnemoSchema->id)->select(['id', 'title'])->get();

        foreach ($nodes as $node) {
            $nodeOptions = MnemoSchemaNodeOptions::whereNodeId($node->id)->select(['parameter_code', 'hardware_code']
            )->get()->toArray();
            $node->options = $nodeOptions;
        }

        return new NodeParam($mnemoSchema->title, $nodes);
    }

    /**
     * @return array
     */
    public function getAllMnemoSchemas(): array
    {
        $schemaModel = new MnemoSchema();
        $schemas = $schemaModel->all();
        $schemaArray = [];
        foreach ($schemas as $schema) {
            $schemaArray[] = $this->getSchemaData($schema);
        }

        return $schemaArray;
    }

    /**
     * @return array
     */
    public function getSignalsOfAllSchemas(): array
    {
        $mnemoSchema = new MnemoSchema();
        $schemas = $mnemoSchema->all();
        $result = [];

        foreach ($schemas as $schema) {
            $result[] = $this->getSignalsOfSingleSchema($schema->name);
        }

        return $result;
    }

    /**
     * @param string $schemaName
     * @return array
     */
    public function getSignalsOfSingleSchema(string $schemaName): array
    {
        $schema = MnemoSchema::whereName($schemaName)->firstOrFail();

        $nodes = MnemoSchemaNode::whereSchemaId($schema->id)->get();

        $signals = [];

        foreach ($nodes as $node) {
            $options = MnemoSchemaNodeOptions::whereNodeId($node->id)->select(['hardware_code', 'parameter_code'])->get(
            );

            foreach ($options as $option) {
                $hardwareCode = $option->hardware_code;
                $parameterCode = $option->parameter_code;

                if (!isset($signals[$hardwareCode])) {
                    $signals[$hardwareCode] = [
                        'hardware_code' => $hardwareCode,
                        'parameter_code' => []
                    ];
                }

                if (is_array($parameterCode)) {
                    $signals[$hardwareCode]['parameter_code'] = array_merge(
                        $signals[$hardwareCode]['parameter_code'],
                        $parameterCode
                    );
                } else {
                    $signals[$hardwareCode]['parameter_code'][] = $parameterCode;
                }
            }
        }

        return [
            'schema_title' => $schema->title,
            'schema_name' => $schema->name,
            'signals' => $signals
        ];
    }

    /**
     * @return array
     */
    public function getSchemaTitles(): array
    {
        return MnemoSchema::query()
            ->select(['title', 'name'])
            ->get()
            ->toArray();
    }
}
