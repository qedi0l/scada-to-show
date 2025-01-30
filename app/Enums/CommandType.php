<?php

namespace App\Enums;

use App\Commands\CommandInterface;
use App\Commands\LineOperation\LIneOperationAddLine;
use App\Commands\LineOperation\LineOperationChangeLineAppearances;
use App\Commands\LineOperation\LineOperationChangeLineDirection;
use App\Commands\LineOperation\LineOperationChangeLineOptions;
use App\Commands\LineOperation\LineOperationDeleteLine;
use App\Commands\NodeOperation\NodeOperationAddLabelToNode;
use App\Commands\NodeOperation\NodeOperationAddMultipleChildNodes;
use App\Commands\NodeOperation\NodeOperationAddNode;
use App\Commands\NodeOperation\NodeOperationAddNodeFromNodeTypeGroup;
use App\Commands\NodeOperation\NodeOperationChangeNodeGeometry;
use App\Commands\NodeOperation\NodeOperationChangeNodeLabel;
use App\Commands\NodeOperation\NodeOperationChangeNodeLink;
use App\Commands\NodeOperation\NodeOperationChangeNodeParameterAndHardwareCodes;
use App\Commands\NodeOperation\NodeOperationChangeNodeSize;
use App\Commands\NodeOperation\NodeOperationChangeNodeTitle;
use App\Commands\NodeOperation\NodeOperationChangeNodeType;
use App\Commands\NodeOperation\NodeOperationDeleteCommandFromNode;
use App\Commands\NodeOperation\NodeOperationDeleteNode;
use App\Commands\NodeOperation\NodeOperationDeleteNodeLabel;
use App\Commands\NodeOperation\NodeOperationManipulateNodeCommands;
use App\Commands\SchemaOperation\SchemaOperationAddSchema;
use App\Commands\SchemaOperation\SchemaOperationChangeTitle;
use App\Commands\SchemaOperation\SchemaOperationDeleteSchema;
use App\Commands\SchemaOperation\SchemaOperationMakeSchemaDefault;
use App\Commands\ZAxis\ZAxisChangeLayerCommand;

/**
 * Command Types
 */
enum CommandType: string
{
    case ChangeNodeGeometry = 'change_node_geometry';
    case DeleteNodeFromSchema = 'delete_node_from_schema';
    case AddNodeToSchema = 'add_node_to_schema';
    case DeleteLine = 'delete_line';
    case AddLine = 'add_line';
    case AddSchema = 'add_schema';
    case ChangeSchemaTitle = 'change_schema_title';
    case DeleteSchema = 'delete_schema';
    case DeleteCommandFromNode = 'delete_command_from_node';
    case ChangeNodeTitle = 'change_node_title';
    case ChangeNodeHardwareAndParameterCodes = 'change_node_hardware_and_parameter_codes';
    case ChangeNodeType = 'change_node_type';
    case ChangeNodeSize = 'change_node_size';
    case ChangeNodeLink = 'change_node_link';
    case AddLabelToNode = 'add_label_to_node';
    case ChangeNodeLabel = 'change_node_label';
    case DeleteNodeLabel = 'delete_node_label';
    case ChangeNodeLayer = 'change_node_layer';
    case ChangeLineAppearances = 'change_line_appearances';
    case ChangeLineOptions = 'change_line_options';
    case MakeSchemaDefault = 'make_schema_default';
    case ChangeLineDirection = 'change_line_direction';
    case AddNodeFromNodeTypeGroup = 'add_node_from_node_type_group';
    case AddMultipleChildNodes = 'add_multiple_child_nodes';
    case ManipulateNodeCommands = 'manipulate_node_commands';


    public static function getCommandTypeMap(): array
    {
        return [
            NodeOperationChangeNodeGeometry::class => self::ChangeNodeGeometry,
            NodeOperationDeleteNode::class => self::DeleteNodeFromSchema,
            NodeOperationAddNode::class => self::AddNodeToSchema,
            LineOperationDeleteLine::class => self::DeleteLine,
            LIneOperationAddLine::class => self::AddLine,
            SchemaOperationAddSchema::class => self::AddSchema,
            SchemaOperationChangeTitle::class => self::ChangeSchemaTitle,
            SchemaOperationDeleteSchema::class => self::DeleteSchema,
            NodeOperationDeleteCommandFromNode::class => self::DeleteCommandFromNode,
            NodeOperationChangeNodeTitle::class => self::ChangeNodeTitle,
            NodeOperationChangeNodeParameterAndHardwareCodes::class => self::ChangeNodeHardwareAndParameterCodes,
            NodeOperationChangeNodeType::class => self::ChangeNodeType,
            NodeOperationChangeNodeSize::class => self::ChangeNodeSize,
            NodeOperationChangeNodeLink::class => self::ChangeNodeLink,
            NodeOperationAddLabelToNode::class => self::AddLabelToNode,
            NodeOperationChangeNodeLabel::class => self::ChangeNodeLabel,
            NodeOperationDeleteNodeLabel::class => self::DeleteNodeLabel,
            ZAxisChangeLayerCommand::class => self::ChangeNodeLayer,
            LineOperationChangeLineAppearances::class => self::ChangeLineAppearances,
            LineOperationChangeLineOptions::class => self::ChangeLineOptions,
            SchemaOperationMakeSchemaDefault::class => self::MakeSchemaDefault,
            LineOperationChangeLineDirection::class => self::ChangeLineDirection,
            NodeOperationAddNodeFromNodeTypeGroup::class => self::AddNodeFromNodeTypeGroup,
            NodeOperationAddMultipleChildNodes::class => self::AddMultipleChildNodes,
            NodeOperationManipulateNodeCommands::class => self::ManipulateNodeCommands,
        ];
    }

    /**
     * Get Command Class by Type Value
     *
     * @param string $title
     * @return string|null
     */
    public static function getCommandByTitle(string $title): ?string
    {
        // Define Type
        $type = self::tryFrom($title);
        if (is_null($type)) {
            return null;
        }

        // Command class search
        $commandClass = array_search($type, self::getCommandTypeMap(), true);
        if (!$commandClass) {
            return null;
        }

        return $commandClass;
    }

    /**
     * Get Type by Command
     *
     * @param CommandInterface $command
     * @return self|null
     */
    public static function getTypeByCommand(CommandInterface $command): ?self
    {
        $commandClass = get_class($command);

        return self::getCommandTypeMap()[$commandClass] ?? null;
    }
}
