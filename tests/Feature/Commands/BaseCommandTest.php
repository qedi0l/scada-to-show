<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;

class BaseCommandTest extends TestCase
{
    public string $commandExecuteUrl = 'api/scada-ui/api/v1/scada/ui/execute/command';

    public string $commandUndoUrl = 'api/scada-ui/api/v1/scada/ui/undo/last/command/';
}
