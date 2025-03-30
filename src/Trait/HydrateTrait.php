<?php
namespace App\Trait;

trait HydrateTrait
{
    public function hydrate(array $init):void{
        foreach($init as $property=>$value){
            $setProperty = "set". ucfirst($property);
            $this->$setProperty($value);
        }
    }


}