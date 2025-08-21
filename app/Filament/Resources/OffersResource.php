<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OffersResource\Pages;
use App\Filament\Resources\OffersResource\RelationManagers;
use App\Models\Offers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MultiSelect;

class OffersResource extends Resource
{
    protected static ?string $model = Offers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),

                Select::make('type')
                    ->options([
                        'free' => 'Buy X Get Y Free',
                        'discount' => 'Discount',
                    ])
                    ->live()
                    ->required(),

                // Free offer fields
                Grid::make(2)->schema([
                    TextInput::make('buy_quantity')
                        ->numeric()
                        ->minValue(1)
                        ->label('Buy')
                        ->default(1)
                        ->required(),
                    TextInput::make('free_quantity')
                        ->numeric()
                        ->minValue(1)
                        ->label('Get Free')
                        ->default(1)
                        ->required(),
                ])->visible(fn (callable $get) => $get('type') === 'free'),

                // Discount offer fields
                Grid::make(2)->schema([
                    TextInput::make('buy_quantity')
                        ->numeric()
                        ->minValue(1)
                        ->label('Buy')
                        ->default(1)
                        ->required(),
                    TextInput::make('discount_percent')
                        ->numeric()
                        ->suffix('%')
                        ->minValue(1)
                        ->maxValue(100)
                        ->label('Discount')
                        ->required(),
                ])->visible(fn (callable $get) => $get('type') === 'discount'),

                Select::make('products')
                    ->label('Products')
                    ->multiple() // allow selecting multiple
                    ->relationship('products', 'title') // products() relation in Offer model
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['parameters'] = [];

        if ($data['type'] === 'free') {
            $data['parameters'] = [
                'buy'  => $data['buy'] ?? 1,
                'free' => $data['free'] ?? 1,
            ];
        }

        if ($data['type'] === 'discount') {
            $data['parameters'] = [
                'discount'  => $data['discount'] ?? 0,
                'min_items' => $data['min_items'] ?? 1,
            ];
        }

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return self::mutateFormDataBeforeCreate($data); // reuse same logic
    }

    private static function generateParameters(array $data): array
    {
        if($data['type'] === 'free') {
            return [
                'buy' => (int) ($data['buy'] ?? 1),
                'free' => (int) ($data['free'] ?? 1),
            ];
        }

        if($data['type'] === 'discount') {
            return [
                'discount' => (int) ($data['discount'] ?? 0),
                'min_items' => (int) ($data['min_items'] ?? 1),
            ];
        }

        return [];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Offer Name'),

                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('details')
                    ->label('Offer Details')
                    ->getStateUsing(function ($record) {
                        if ($record->type === 'free') {
                            return "Buy {$record->buy_quantity} Get {$record->free_quantity} Free";
                        }
                        if ($record->type === 'discount') {
                            return "Buy {$record->buy_quantity} Get {$record->discount_percent}% Off";
                        }
                        return '-';
                    }),
                TextColumn::make('products.title')->label('Applied Products')->limitList(3),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffers::route('/create'),
            'edit' => Pages\EditOffers::route('/{record}/edit'),
        ];
    }
}
