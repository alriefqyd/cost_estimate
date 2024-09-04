<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DesignEngineerRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($attribute)
    {
        $this->field = $attribute;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $designEngineerCivil = request()->input('design_engineer_civil');
        $designEngineerMechanical = request()->input('design_engineer_mechanical');
        $designEngineerElectrical = request()->input('design_engineer_electrical');
        $designEngineerInstrument = request()->input('design_engineer_instrument');
        $designEngineerIt = request()->input('design_engineer_it');
        if(isset($designEngineerCivil) || isset($designEngineerMechanical)
        || isset($designEngineerElectrical) || isset($designEngineerInstrument) || isset($designEngineerIt)){
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $field = str_replace('_',' ',$this->field);
        $field = ucfirst(trans($field));
        return $field . ' is required';
    }
}
