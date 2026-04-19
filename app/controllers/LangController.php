<?php

class LangController extends Controller
{
    public function switch(Request $request, string $locale): void
    {
        $available = array_keys(Lang::available());
        if (in_array($locale, $available, true)) {
            switchLang($locale);
        }
        // Redirect back to referer, or home
        $ref = $_SERVER['HTTP_REFERER'] ?? url('/');
        // Safety: only redirect to same host
        $host = parse_url($ref, PHP_URL_HOST) ?? '';
        $myHost = $_SERVER['HTTP_HOST'] ?? '';
        if ($host !== $myHost) { $ref = url('/'); }
        $this->redirect($ref);
    }
}
