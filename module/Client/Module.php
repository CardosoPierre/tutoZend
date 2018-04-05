<?php 
namespace Client;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
	
        return include __DIR__ . '/../config/module.config.php';
    }
	 public function getServiceConfig()
    {
	
        return [
            'factories' => [
                Model\ClientTable::class => function($container) {
				
                    $tableGateway = $container->get(Model\ClientTableGateway::class);
                    return new Model\ClientTable($tableGateway);
                },
                Model\ClientTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Client());
					
                    return new TableGateway('client', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
	 public function getControllerConfig()
    {
	
        return [
            'factories' => [
                Controller\ClientController::class => function($container) {
                    return new Controller\ClientController(
                        $container->get(Model\ClientTable::class)
                    );
                },
            ],
        ];
    }
	
}
?>