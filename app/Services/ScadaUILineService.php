<?php

namespace App\Services;

use App\Contracts\IScadaUILine;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaLineAppearance;
use App\Models\MnemoSchemaLineArrowType;
use App\Models\MnemoSchemaLineOptions;
use App\Models\MnemoSchemaLineType;

class ScadaUILineService implements IScadaUILine
{
    /**
     * @param MnemoSchema $schema
     * @return array
     */
    public function getLinesBySchema(MnemoSchema $schema): array
    {
        $lines = MnemoSchemaLine::whereSchemaId($schema->id)->get();
        $linesData = [];
        foreach ($lines as $line) {
            $linesData[] = $this->getDataByLine($line);
        }
        return $linesData;
    }

    /**
     * @param MnemoSchemaLine $line
     * @return array
     */
    private function getDataByLine(MnemoSchemaLine $line): array
    {
        $appearance = MnemoSchemaLineAppearance::whereLineId($line->id)->firstOrFail();
        $options = MnemoSchemaLineOptions::whereLineId($line->id)->firstOrFail();

        $lineType = $this->getLineTypeByTypeId($options->type_id);
        return [
            'id' => $line->id,
            'first_node' => $line->first_node,
            'second_node' => $line->second_node,
            'first_position' => $line->source_position,
            'second_position' => $line->target_position,
            'options' => [
                'label' => $options->text,
                'first_arrow' => $this->getLineFirstArrowType($options->first_arrow),
                'second_arrow' => $this->getLineFirstArrowType($options->second_arrow),
                'type' => $lineType,
                'appearance' => [
                    'color' => $appearance->color,
                    'opacity' => $appearance->opacity,
                    'width' => $appearance->width
                ]
            ]
        ];
    }

    /**
     * @param int $lineTypeId
     * @return string
     */
    private static function getLineTypeByTypeId(int $lineTypeId): string
    {
        return MnemoSchemaLineType::whereId($lineTypeId)->first()->type;
    }

    /**
     * @param int|null $typeId
     * @return mixed
     */
    private function getLineFirstArrowType(int|null $typeId)
    {
        return MnemoSchemaLineArrowType::where('id', '=', $typeId)->value('arrow_type_title');
    }
}
