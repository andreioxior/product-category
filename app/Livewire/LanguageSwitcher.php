<?php

namespace App\Livewire;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $currentLocale = 'en';

    public array $locales = [
        'en' => 'English',
        'sv' => 'Svenska',
        'fi' => 'Suomi',
        'da' => 'Dansk',
        'nb' => 'Norsk',
    ];

    public function mount(): void
    {
        $this->currentLocale = session('locale', app()->getLocale());
    }

    public function setLocale(string $locale): void
    {
        if (array_key_exists($locale, $this->locales)) {
            session()->put('locale', $locale);
            session()->save();
            $this->currentLocale = $locale;
            $this->redirect(request()->header('referer', url()->previous()));
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.language-switcher');
    }
}
