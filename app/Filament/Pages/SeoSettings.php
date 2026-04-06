<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SeoSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'SEO & Analytics';

    protected static ?int $navigationSort = 4;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected string $view = 'filament.pages.seo-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'google_analytics_id'    => SiteSetting::get('google_analytics_id', ''),
            'google_search_console'  => SiteSetting::get('google_search_console', ''),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Google Analytics (GA4)')
                    ->description('Add your Measurement ID to enable Google Analytics tracking on all pages.')
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('Measurement ID')
                            ->placeholder('G-XXXXXXXXXX')
                            ->helperText('Find this in your GA4 property under Admin → Data Streams. Leave blank to disable.')
                            ->maxLength(30),
                    ]),

                Section::make('Google Search Console')
                    ->description('Verify site ownership for Google Search Console.')
                    ->schema([
                        TextInput::make('google_search_console')
                            ->label('Verification Meta Content')
                            ->placeholder('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx')
                            ->helperText('Paste only the content="…" value from the HTML tag verification method. Leave blank to skip.')
                            ->maxLength(100),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteSetting::set('google_analytics_id',   trim($state['google_analytics_id']   ?? ''));
        SiteSetting::set('google_search_console',  trim($state['google_search_console']  ?? ''));

        Notification::make()
            ->success()
            ->title('SEO & Analytics settings saved')
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('primary')
                ->action('save'),
        ];
    }
}
