<?php

namespace App\Providers;

use App\Contracts\IAvailableCommands;
use App\Contracts\ICatalogService;
use App\Contracts\IMnemoSchema;
use App\Contracts\INodeTypeService;
use App\Contracts\IScadaUILineArrowType;
use App\Contracts\IScadaUILineType;
use App\Contracts\IScadaSignals;
use App\Contracts\IScadaUI;
use App\Contracts\IScadaUILibrary;
use App\Contracts\IScadaUILine;
use App\Contracts\IScadaUINode;
use App\Contracts\IScadaUINodeAppearance;
use App\Contracts\IScadaUINodeGeometry;
use App\Contracts\IScadaUIProject;
use App\Contracts\ISchemaImportAndExport;
use App\Contracts\ISelected;
use App\Contracts\ISettingsToolbar;
use App\Services\AvailableCommandsService;
use App\Services\CatalogServices\CatalogSignalService;
use App\Services\MnemoSchemaImportAndExportService;
use App\Services\MnemoSchemaService;
use App\Services\NodeTypeService;
use App\Services\ScadaSignalService;
use App\Services\ScadaUIDBProjectService;
use App\Services\ScadaUILibraryService;
use App\Services\ScadaUILineArrowTypeService;
use App\Services\ScadaUILineService;
use App\Services\ScadaUILineTypeService;
use App\Services\ScadaUINodeAppearanceService;
use App\Services\ScadaUINodeGeometryService;
use App\Services\ScadaUINodeService;
use App\Services\ScadaUIService;
use App\Services\SelectedService;
use App\Services\SettingsToolbarService;
use Illuminate\Support\ServiceProvider;

class ScadaUIProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(IScadaUI::class, ScadaUIService::class);
        $this->app->singleton(IScadaUINode::class, ScadaUINodeService::class);
        $this->app->singleton(IScadaUILine::class, ScadaUILineService::class);
        $this->app->singleton(IScadaUINodeAppearance::class, ScadaUINodeAppearanceService::class);
        $this->app->singleton(IScadaUINodeGeometry::class, ScadaUINodeGeometryService::class);
        $this->app->singleton(IScadaUIProject::class, ScadaUIDBProjectService::class);
        $this->app->singleton(IScadaUILibrary::class, ScadaUILibraryService::class);
        $this->app->singleton(IAvailableCommands::class, AvailableCommandsService::class);
        $this->app->singleton(IScadaSignals::class, ScadaSignalService::class);
        $this->app->singleton(ICatalogService::class, CatalogSignalService::class);
        $this->app->singleton(INodeTypeService::class, NodeTypeService::class);
        $this->app->singleton(ISchemaImportAndExport::class, MnemoSchemaImportAndExportService::class);
        $this->app->singleton(IScadaUILineType::class, ScadaUILineTypeService::class);
        $this->app->singleton(IScadaUILineArrowType::class, ScadaUILineArrowTypeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
