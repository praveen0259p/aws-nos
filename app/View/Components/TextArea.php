<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Textarea extends Component
{
    public $label, $name, $placeholder, $rows, $required,$value;

    public function __construct($label, $name, $placeholder = '', $rows = 3, $required = true,$value='')
    {
        $this->label = $label;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->rows = $rows;
        $this->required = $required;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.textarea');
    }
}
