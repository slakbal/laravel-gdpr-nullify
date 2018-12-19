<?php

namespace Subdesign\LaravelGdprNullify;

use Doctrine\DBAL\Schema\Schema as DoctrineSchema;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Subdesign\LaravelGdprNullify\Exception\GdprNullifyException;

trait GdprNullifyTrait
{
    /**
     * Allowed database field types
     * Docs: https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/types.html#character-string-types
     * @var array
     */
    private $allowedFieldTypes = ['string', 'text']; 

    /**
     * The field(s) aren't exist
     * 
     * @var array
     */
    private $notExists = [];

    /**
     * The field(s) aren't a string or text type
     * 
     * @var array
     */
    private $notTextType = [];
        
    /**
     * Fields with sizes to work with
     * 
     * @var array
     */
    private $fieldsAndLengths = [];

    /**
     * Run check first of all
     */
    public function __construct()
    {
        $this->checkFields($this->gdprFields);
    }

    /**
     * Nullify fields
     * 
     * @return [type] [description]
     */
    public function nullify()
    {
        foreach ($this->fieldsAndLengths as $field => $length) {
            $this->randomizeField($field, $length);
        }

        $this->save();
    }

    /**
     * Check fields before nullify
     * 
     * @return void
     */
    private function checkFields()
    {
        if (! property_exists(get_class($this), 'gdprFields')) {
            throw new GdprNullifyException("Please add the required field to $gdprFields property.");            
        }   

        if (!is_array($this->gdprFields)) {
            throw new GdprNullifyException("The $gdprFields property is not an array.");            
        }
        
        if (empty($this->gdprFields)) {
            throw new GdprNullifyException("The $gdprFields property is empty.");            
        }

        foreach ($this->gdprFields as $field) {

            if (!in_array(DB::getSchemaBuilder()->getColumnType($this->getTable(), $field), $this->allowedFieldTypes)) {
                array_push($this->notTextType, $field);
            }

            $this->fieldsAndLengths[$field] = DB::connection()->getDoctrineColumn($this->getTable(), $field)->getLength();
        }

        if (!empty($this->notTextType)) {
            throw new GdprNullifyException("The following field(s) is/are not TEXT type: ".implode(",", $this->notTextType));            
        }
    }

    /**
     * Set field to random string
     * 
     * @param  string  $field 
     * @param  integer $length
     * @return void         
     */
    private function randomizeField($field, $length)
    {
        $this->$field = str_random($length);
    }
}