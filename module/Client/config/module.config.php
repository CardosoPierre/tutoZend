<?php 
namespace Client;
use Zend\Router\Http\Segment;
//use Zend\ServiceManager\Factory\InvokableFactory;

return [


    
	
	// The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'client' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/client[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ClientController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
	
	
    'view_manager' => [
        'template_path_stack' => [
            'client' => __DIR__ . '/../view',
        ],
    ],
];

?>