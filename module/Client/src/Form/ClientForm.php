<?php
namespace Client\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class ClientForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('client');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'nom',
            'type' => 'text',
            'options' => [
                'label' => 'Nom',
            ],
        ]);

        $this->add([
            'name' => 'prenom',
            'type' => 'text',
            'options' => [
                'label' => 'Prénom',
            ],
        ]);

        $this->add([
            'name' => 'adresse',
            'type' => 'text',
            'options' => [
                'label' => 'Adresse',
            ],
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'Email',
            'options' => [
                'label' => 'Email',
            ],
        ]);

        $this->add([
            'name' => 'dateNaissance',
            'type' => Element\Date::class,
            'options' => [
                'label'     =>  'Date de Naissance',
                'format'    =>  'Y-m-d',
            ],
        ]);

        $this->add([
            'name' => 'sexe',
            'type' => Element\Radio::class,
            'options' => [
                'label' => 'Sexe',
                'value_options' => [
                    [
                        'value' => 'F',
                        'label' => 'F',
                        'selected' => true,
                    ],
                    [
                        'value' => 'M',
                        'label' => 'M',
                    ],
                ]
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}