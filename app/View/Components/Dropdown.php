<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dropdown extends Component
{ public $items; // Menampung item dropdown

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function render(): View|Closure|string
    {
        return view('components.dropdown');
    }
}
