<?php

namespace App\Http\Resources\Nodes;

use App\Contracts\IScadaSignals;
use App\Models\MnemoSchemaNode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin MnemoSchemaNode
 */
class ChildNodesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'hardware_code' => $this->options->hardware_code,
            'parameter_code' => $this->options->parameter_code,
            'parent_id' => $this->options->parent_id,
            'node_id' => $this->options->node_id,
            'node_type' => $this->node_type->type,
            'node_type_title' => $this->node_type->title,
            'node_type_group_id' => $this->node_type->node_type_group_id,
            'node_type_group_title' => $this->node_type->group->title,
            'commands' => $this->getCommands($this->getKey()),
        ];
    }

    private function getCommands(int $nodeId)
    {
        $signals = App::make(IScadaSignals::class);

        return $signals->getSignalsMetaData($nodeId);
    }
}
