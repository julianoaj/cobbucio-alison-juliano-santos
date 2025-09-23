<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface Action
{
    /**
     * Handle the action with generic parameters.
     *
     * @param Request $request ...$parameters
     * @return void
     */
    public function handle(Request $request): void;
}
