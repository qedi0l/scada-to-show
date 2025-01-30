<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\DTO\LineAppearanceDto;
use App\DTO\LineDto;
use App\DTO\LineOptionsDto;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeType;
use App\Receivers\NodeOperation\AddNodeToSchemaReceiver;
use App\Receivers\NodeOperation\DeleteNodeFromSchemaReceiver;
use App\Repositories\LineRepository;
use App\Repositories\NodeRepository;
use App\Repositories\SchemaRepository;
use Exception;
use Throwable;

class NodeOperationDeleteNode extends AbstractCommand
{
    /**
     * Delete node
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer'
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $node->load([
            'schema',
            'options',
            'appearance',
            'geometry',
            'node_type',
            'from_lines' => ['options', 'appearance'],
            'to_lines' => ['options', 'appearance']
        ]);
        $nodeData = [
            'node' => [
                'title' => $node->title,
                'group_id' => $node->group_id,
                'type' => $node->node_type->type,
                'schema_name' => $node->schema->name,
                'options' => [
                    'hardware_code' => $node->options->hardware_code,
                    'parameter_code' => $node->options->parameter_code,
                    'appearance' => [
                        'width' => $node->appearance->width,
                        'height' => $node->appearance->height
                    ],
                    'geometry' => [
                        'x' => $node->geometry->x,
                        'y' => $node->geometry->y
                    ]
                ]
            ]
        ];
        $linesData = $node->from_lines->merge($node->to_lines)
            ->map(function (MnemoSchemaLine $line) use ($node) {
                return [
                    'line_id' => $line->id,
                    'schema_name' => $node->schema->name,
                    'first_node' => $line->first_node,
                    'second_node' => $line->second_node,
                    'source_position' => $line->source_position,
                    'target_position' => $line->target_position,
                    'options' => [
                        'text' => $line->options->text,
                        'type_id' => $line->options->type_id,
                        'first_arrow' => $line->options->first_arrow,
                        'second_arrow' => $line->options->second_arrow,
                        'appearance' => [
                            'color' => $line->appearance->color,
                            'opacity' => $line->appearance->opacity,
                            'width' => $line->appearance->width
                        ]
                    ]
                ];
            });

        $receiver = new DeleteNodeFromSchemaReceiver();
        $receiver->deleteNodeFromSchema($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $node->getKey(),
                'node_data' => $nodeData,
                'lines_data' => $linesData
            ])
            ->setResponseData(null);
    }


    /**
     * Undo deleting of node
     * @return void
     * @throws Throwable
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'node_data' => 'required|array',
            'lines_data' => 'required|array',
        ]);
        $nodeData = $this->request->input('node_data');
        $linesData = $this->request->input('lines_data');
        $nodeId = $this->request->input('node_id');


        // Define Schema
        $schemaRepository = new SchemaRepository();
        $schema = $schemaRepository->getByName($nodeData['node']['schema_name']);
        $this->setSchemaId($schema->getKey());

        // Execute
        $receiver = new AddNodeToSchemaReceiver();
        $request = [
            'data' => $nodeData
        ];
        $newNode = $receiver->addNodeToSchema($request);

        $lineRepository = new lineRepository();
        foreach ($linesData as $lineData) {
            $options = $lineData['options'];
            $appearance = $options['appearance'];

            $lineDto = new LineDto(
                schemaId: $schema->getKey(),
                firstNodeId: $lineData['first_node'] == $nodeId ? $newNode->getKey() : $lineData['first_node'],
                secondNodeId: $lineData['second_node'] == $nodeId ? $newNode->getKey() : $lineData['second_node'],
                sourcePosition: $lineData['source_position'],
                targetPosition: $lineData['target_position'],
                options: new LineOptionsDto(
                    lineId: 0,
                    text: $options['text'],
                    typeId: $options['type_id'],
                    firstArrow: $options['first_arrow'],
                    secondArrow: $options['second_arrow'],
                ),
                appearance: new LineAppearanceDto(
                    lineId: 0,
                    color: $appearance['color'],
                    opacity: $appearance['opacity'],
                    width: $appearance['width'],
                ),
            );
            $lineRepository->store($lineDto);
        }
    }
}
