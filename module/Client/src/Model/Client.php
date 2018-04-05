<?php
namespace Application\Util;

use Zend\I18n\Translator\Translator as ZendI18nTranslator;
use Zend\Validator\Translator\TranslatorInterface;

class Translator extends ZendI18nTranslator implements TranslatorInterface {

}

namespace Client\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;
use Application\Util\Translator ;
use Zend\Validator\AbstractValidator;
use Zend\I18n\Translator\Resources ;

class Client implements InputFilterAwareInterface
{
    public $id;
    public $nom;
    public $prenom;

    private $inputFilter;

    public function exchangeArray($data)
    {
	
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->nom = (isset($data['nom'])) ? $data['nom'] : null;
        $this->prenom  = (isset($data['prenom'])) ? $data['prenom'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'nom' => $this->nom,
            'prenom'  => $this->prenom,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf("%s n'autorise pas l'injection d'une classe input filter alternative",  __CLASS__ ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $translator = Translator::factory([
            'locale' => 'fr'
        ]);

        $translator->addTranslationFilePattern(
            'phparray',
            Resources::getBasePath(),
            Resources::getPatternForValidator()
        );
        AbstractValidator::setDefaultTranslator($translator);
        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'nom',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'prenom',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}