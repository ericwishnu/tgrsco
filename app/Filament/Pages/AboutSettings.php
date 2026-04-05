<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AboutSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'About Page';

    protected static ?int $navigationSort = 2;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected string $view = 'filament.pages.about-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hero_title'    => SiteSetting::get('about_hero_title',    'About TGRS.CO'),
            'hero_subtitle' => SiteSetting::get('about_hero_subtitle', 'Your trusted partner for quality products sourced directly from China.'),
            'hero_image'    => SiteSetting::get('about_hero_image',    'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=1600&q=80'),

            'story_image' => SiteSetting::get('about_story_image', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80'),
            'story_body'  => SiteSetting::get('about_story_body',
                "TGRS.CO is your trusted bridge to the world's largest marketplace. We partner with verified suppliers and distributors across China to bring you a wide selection of quality products at competitive prices.\n\nEvery product in our catalog is carefully reviewed before it reaches you. We handle the sourcing, quality checks, and logistics so you can shop with confidence.\n\nCan't find what you're looking for? Our Jastip (Jasa Titip) service lets you request any item from China and our team will source and purchase it on your behalf."
            ),

            'values_subtitle' => SiteSetting::get('about_values_subtitle', 'We built TGRS.CO around three principles that guide everything we do — from the suppliers we choose to the way we handle every order.'),
            'value_1_title' => SiteSetting::get('about_value_1_title', 'Verified Suppliers'),
            'value_1_body'  => SiteSetting::get('about_value_1_body',  'Every supplier in our network is personally vetted. We visit warehouses, check product quality, and verify business credentials before a single item appears in our catalog.'),
            'value_2_title' => SiteSetting::get('about_value_2_title', 'Competitive Prices'),
            'value_2_body'  => SiteSetting::get('about_value_2_body',  'By sourcing directly from Chinese manufacturers and distributors, we eliminate unnecessary layers of markup. What you see is a fair, transparent price — no hidden fees.'),
            'value_3_title' => SiteSetting::get('about_value_3_title', 'Jastip Service'),
            'value_3_body'  => SiteSetting::get('about_value_3_body',  "Our catalog can't hold everything China has to offer. With Jastip, send us a Taobao / 1688 link or a description and we'll buy it, check it, and ship it straight to you."),

            'jastip_title'        => SiteSetting::get('about_jastip_title',        'How Jastip Works'),
            'jastip_subtitle'     => SiteSetting::get('about_jastip_subtitle',     "Can't find what you need in our catalog? We'll get it from China for you — just ask."),
            'jastip_step_1_title' => SiteSetting::get('about_jastip_step_1_title', 'Tell Us What You Need'),
            'jastip_step_1_body'  => SiteSetting::get('about_jastip_step_1_body',  'Send us the product name, a link from Taobao / 1688 / Shopee, or simply describe what you are looking for via WhatsApp.'),
            'jastip_step_2_title' => SiteSetting::get('about_jastip_step_2_title', 'We Source & Purchase'),
            'jastip_step_2_body'  => SiteSetting::get('about_jastip_step_2_body',  'Our team locates the item from a trusted Chinese supplier, confirms the price and availability, and purchases it on your behalf.'),
            'jastip_step_3_title' => SiteSetting::get('about_jastip_step_3_title', 'We Deliver to You'),
            'jastip_step_3_body'  => SiteSetting::get('about_jastip_step_3_body',  'Your item is carefully inspected, packed, and shipped directly to your address. We keep you updated every step of the way.'),
            'jastip_whatsapp'     => SiteSetting::get('about_jastip_whatsapp',     ''),

            'team' => json_decode(SiteSetting::get('about_team', json_encode([])), true) ?: [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero Banner')
                    ->description('The full-width banner at the top of the About page.')
                    ->schema([
                        TextInput::make('hero_title')
                            ->label('Title')
                            ->maxLength(255),
                        TextInput::make('hero_subtitle')
                            ->label('Subtitle')
                            ->maxLength(255),
                        TextInput::make('hero_image')
                            ->label('Background Image URL')
                            ->url()
                            ->placeholder('https://…')
                            ->helperText('Recommended: 1600 × 600 px landscape image.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Our Story')
                    ->description('Left-side image and right-side text block.')
                    ->schema([
                        TextInput::make('story_image')
                            ->label('Story Image URL')
                            ->url()
                            ->placeholder('https://…')
                            ->helperText('Recommended: 800 × 380 px.')
                            ->columnSpanFull(),
                        Textarea::make('story_body')
                            ->label('Story Text')
                            ->helperText('Separate paragraphs with a blank line.')
                            ->rows(10)
                            ->columnSpanFull(),
                    ]),

                Section::make('Our Values')
                    ->description('Three value cards shown side-by-side.')
                    ->schema([
                        Textarea::make('values_subtitle')
                            ->label('Section Description')
                            ->helperText('Short paragraph shown below the "What We Stand For" heading.')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('value_1_title')->label('Value 1 — Title'),
                        Textarea::make('value_1_body')->label('Value 1 — Description')->rows(3),
                        TextInput::make('value_2_title')->label('Value 2 — Title'),
                        Textarea::make('value_2_body')->label('Value 2 — Description')->rows(3),
                        TextInput::make('value_3_title')->label('Value 3 — Title'),
                        Textarea::make('value_3_body')->label('Value 3 — Description')->rows(3),
                    ])
                    ->columns(2),

                Section::make('Jastip Service')
                    ->description('The "How Jastip Works" section — 3 steps shown as cards.')
                    ->schema([
                        TextInput::make('jastip_title')
                            ->label('Section Title')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('jastip_subtitle')
                            ->label('Section Subtitle')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('jastip_step_1_title')->label('Step 1 — Title'),
                        Textarea::make('jastip_step_1_body')->label('Step 1 — Description')->rows(3),
                        TextInput::make('jastip_step_2_title')->label('Step 2 — Title'),
                        Textarea::make('jastip_step_2_body')->label('Step 2 — Description')->rows(3),
                        TextInput::make('jastip_step_3_title')->label('Step 3 — Title'),
                        Textarea::make('jastip_step_3_body')->label('Step 3 — Description')->rows(3),
                        TextInput::make('jastip_whatsapp')
                            ->label('WhatsApp Order Link')
                            ->placeholder('https://wa.me/628xxxxxxxxxx?text=Hi%2C+saya+mau+jastip...')
                            ->url()
                            ->helperText('CTA button link. Leave blank to hide the button.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Team Members')
                    ->description('Shown as a row of portrait cards.')
                    ->schema([
                        Repeater::make('team')
                            ->label('')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('role')
                                    ->label('Role / Title')
                                    ->maxLength(255),
                                TextInput::make('image')
                                    ->label('Photo URL')
                                    ->url()
                                    ->placeholder('https://…'),
                            ])
                            ->columns(3)
                            ->addActionLabel('Add Team Member')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteSetting::set('about_hero_title',    $state['hero_title']    ?? '');
        SiteSetting::set('about_hero_subtitle', $state['hero_subtitle'] ?? '');
        SiteSetting::set('about_hero_image',    $state['hero_image']    ?? '');

        SiteSetting::set('about_story_image', $state['story_image'] ?? '');
        SiteSetting::set('about_story_body',  $state['story_body']  ?? '');

        SiteSetting::set('about_values_subtitle', $state['values_subtitle'] ?? '');
        SiteSetting::set('about_value_1_title', $state['value_1_title'] ?? '');
        SiteSetting::set('about_value_1_body',  $state['value_1_body']  ?? '');
        SiteSetting::set('about_value_2_title', $state['value_2_title'] ?? '');
        SiteSetting::set('about_value_2_body',  $state['value_2_body']  ?? '');
        SiteSetting::set('about_value_3_title', $state['value_3_title'] ?? '');
        SiteSetting::set('about_value_3_body',  $state['value_3_body']  ?? '');

        SiteSetting::set('about_jastip_title',        $state['jastip_title']        ?? '');
        SiteSetting::set('about_jastip_subtitle',     $state['jastip_subtitle']     ?? '');
        SiteSetting::set('about_jastip_step_1_title', $state['jastip_step_1_title'] ?? '');
        SiteSetting::set('about_jastip_step_1_body',  $state['jastip_step_1_body']  ?? '');
        SiteSetting::set('about_jastip_step_2_title', $state['jastip_step_2_title'] ?? '');
        SiteSetting::set('about_jastip_step_2_body',  $state['jastip_step_2_body']  ?? '');
        SiteSetting::set('about_jastip_step_3_title', $state['jastip_step_3_title'] ?? '');
        SiteSetting::set('about_jastip_step_3_body',  $state['jastip_step_3_body']  ?? '');
        SiteSetting::set('about_jastip_whatsapp',     $state['jastip_whatsapp']     ?? '');

        SiteSetting::set('about_team', json_encode(array_values($state['team'] ?? [])));

        Notification::make()
            ->success()
            ->title('About page saved successfully')
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
