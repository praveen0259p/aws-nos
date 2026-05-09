<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextInput extends Component
{
    public $name;
    public $value;
    public $placeholder;
    public $label;
    public $icon;
    public $type;
    public $minlength;
    public $maxlength;

    public function __construct($name, $value = '', $placeholder = '', $label = '', $icon = '',$type='',$minlength = null, 
        $maxlength = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->label = ucfirst($label);
        $this->icon = $icon;
        $this->type = $type;
        $this->minlength = $minlength;
        $this->maxlength = $maxlength;
    }

    public function render()
    {
        return view('components.text-input');
    }
}
