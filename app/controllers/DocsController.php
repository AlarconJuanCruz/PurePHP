<?php

class DocsController extends Controller
{
    public function index(Request $request): void
    {
        $this->render('docs/index', [
            'pageTitle'   => 'Documentation',
            'dbConnected' => DB::isConnected(),
            'phpVersion'  => PHP_VERSION,
            'baseUrl'     => BASE_URL,
        ]);
    }
}
