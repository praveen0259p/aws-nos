<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BreadCrumbs extends Component
{
    /**
     * Create a new component instance.
     */
    public $currentPage;
    public $menuItems;
    public $homeUrl;
    public $homeLabel;
    public function __construct($currentPage, $menuItems = [], $homeUrl = '/', $homeLabel = 'Home')
    {
        $this->currentPage = $currentPage;
        $this->menuItems = $menuItems;
        $this->homeUrl = $homeUrl;
        $this->homeLabel = $homeLabel;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.bread-crumbs');
    }
}
