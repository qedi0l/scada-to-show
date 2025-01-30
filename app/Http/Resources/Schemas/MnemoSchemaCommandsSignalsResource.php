<?php

namespace App\Http\Resources\Schemas;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @mixin MnemoSchema
 */
class MnemoSchemaCommandsSignalsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'schema_title' => $this->title,
            'schema_name' => $this->name,
            'signals' => $this->getSignals($this->nodes),
            'commands' => $this->getCommands(),
        ];
    }

    /**
     * Get Signals
     *
     * @param Collection $nodes
     * @return Collection
     */
    private function getSignals(Collection $nodes): Collection
    {
        return $nodes
            ->map(function (MnemoSchemaNode $node) {
                return $node->options->only(['hardware_code', 'parameter_code', 'node_id']);
            })
            ->filter(function (array $options) {
                return $options['parameter_code'] !== null;
            })
            ->values();
    }

    /**
     * Get Commands
     *
     * @return Collection|null
     */
    private function getCommands(): ?Collection
    {
        $commands = $this->nodes->map(function (MnemoSchemaNode $node) {
            return $node->commands->map(function (MnemoSchemaNodeCommand $command) {
                return [
                    'parameter_code' => $command->parameter_code,
                    'node_id' => $command->node_id,
                    'hardware_code' => $command->node->options->hardware_code,
                ];
            });
        })
            ->filter()
            ->flatten(1);

        return $commands->isNotEmpty()
            ? $commands
            : null;
    }
}
