<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectInput extends Component
{
    public $name;
    public $icon;
    public $label;
    public $options;
    public $selected;
    public $placeholder;
    

    public function __construct($name,$icon, $label = '', $options = [], $selected = '', $placeholder = 'Select an option')
    {
        $this->name = $name;
        $this->label = $label ?: ucfirst($name);
        $this->options = $options;
        $this->selected = $selected;
        $this->placeholder = $placeholder;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.select-input');
    }
}
