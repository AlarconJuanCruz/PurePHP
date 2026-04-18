<?php

class ComponentsController extends Controller
{
    public function index(Request $request): void
    {
        $this->render('components/index', [
            'pageTitle' => 'UI Components',
        ]);
    }
}
