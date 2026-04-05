<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class FooterSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Footer';

    protected static ?int $navigationSort = 3;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3BottomLeft;

    protected string $view = 'filament.pages.footer-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'footer_about' => SiteSetting::get('footer_about',
                'A curated collection of homeware and everyday objects inspired by Nordic minimalism.'),
            'social_twitter'   => SiteSetting::get('footer_social_twitter',   ''),
            'social_facebook'  => SiteSetting::get('footer_social_facebook',  ''),
            'social_instagram' => SiteSetting::get('footer_social_instagram', ''),
            'social_pinterest' => SiteSetting::get('footer_social_pinterest', ''),
            'social_whatsapp'  => SiteSetting::get('footer_social_whatsapp',  ''),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Footer Description')
                    ->schema([
                        Textarea::make('footer_about')
                            ->label('About Text')
                            ->helperText('Short description shown in the footer.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Social Media Links')
                    ->description('Leave blank to hide an icon.')
                    ->schema([
                        TextInput::make('social_whatsapp')
                            ->label('WhatsApp URL')
                            ->url()
                            ->placeholder('https://wa.me/628xxxxxxxxxx?text=Hi%2C+saya+ingin+order')
                            ->helperText('Used for the floating WhatsApp button on the website.'),
                        TextInput::make('social_twitter')
                            ->label('Twitter / X URL')
                            ->url()
                            ->placeholder('https://twitter.com/yourhandle'),
                        TextInput::make('social_facebook')
                            ->label('Facebook URL')
                            ->url()
                            ->placeholder('https://facebook.com/yourpage'),
                        TextInput::make('social_instagram')
                            ->label('Instagram URL')
                            ->url()
                            ->placeholder('https://instagram.com/yourhandle'),
                        TextInput::make('social_pinterest')
                            ->label('Pinterest URL')
                            ->url()
                            ->placeholder('https://pinterest.com/yourhandle'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteSetting::set('footer_about',           $state['footer_about']    ?? '');
        SiteSetting::set('footer_social_whatsapp', $state['social_whatsapp'] ?? '');
        SiteSetting::set('footer_social_twitter',  $state['social_twitter']  ?? '');
        SiteSetting::set('footer_social_facebook', $state['social_facebook'] ?? '');
        SiteSetting::set('footer_social_instagram',$state['social_instagram'] ?? '');
        SiteSetting::set('footer_social_pinterest',$state['social_pinterest'] ?? '');

        Notification::make()
            ->success()
            ->title('Footer settings saved')
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
