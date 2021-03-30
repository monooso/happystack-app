<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BaseLayout extends Component
{
    public function render()
    {
        return view('layouts.base');
    }
}
