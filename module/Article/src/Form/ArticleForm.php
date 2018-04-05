<?php
namespace Article\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class ArticleForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('article');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'designation',
            'type' => 'text',
            'options' => [
                'label' => 'Désignation',
            ],
        ]);

        $this->add([
            'name' => 'prix',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Prix',
            ],
            'attributes' => [
                'min' => '0',
                'step' => '0.01',
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