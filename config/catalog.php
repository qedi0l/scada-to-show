<?php

return [
    'base_url' => env('METRIC_CATALOG_HOST'),
    'readable_signals_endpoint' => env('METRIC_CATALOG_HOST') . '/api/v1/scada/signals/reading',
    'writable_signals_endpoint' => env('METRIC_CATALOG_HOST') . '/api/v1/scada/signals/writing',
    'signals_metadata_route' => env('METRIC_CATALOG_HOST') . '/api/v1/metrics/signals/metadata',
    'hardware_hierarchy_endpoint' => env('METRIC_CATALOG_HOST') . '/api/v1/scada/hardware/hierarchy',
];
