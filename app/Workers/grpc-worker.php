<?php

//use App\Services\GRPC\ClassifierInfoService;
use Spiral\RoadRunner\GRPC\Server;
use Illuminate\Foundation\Console\Kernel;

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$server = new Server();
//$server->registerService(GreeterInterface::class, new GreeterService());
//$server->registerService(ClassifierCrudServiceInterface::class, new ClassifierService());
//$server->registerService(ClassifierInfoCrudServiceInterface::class, new ClassifierInfoService());
//$server->registerService(ClassifierQuestionCrudServiceInterface::class, new ClassifierQuestionService());
//$server->registerService(ClientCrudServiceInterface::class, new ClientService());
//$server->registerService(ClientEmailCrudServiceInterface::class, new ClientEmailService());
//$server->registerService(ClientPhoneCrudServiceInterface::class, new ClientPhoneService());
//$server->registerService(ClientRequestCrudServiceInterface::class, new ClientRequestService());
//$server->registerService(ClientRequestTimeCrudServiceInterface::class, new ClientRequestTimeService());
//$server->registerService(ProblemAreaTypeCrudServiceInterface::class, new ProblemAreaTypeService());
//$server->registerService(StatusCrudServiceInterface::class, new StatusService());
// $server->registerService(NAMEInterface::class, new NAMEService());
// Register your services here...
$server->serve();
