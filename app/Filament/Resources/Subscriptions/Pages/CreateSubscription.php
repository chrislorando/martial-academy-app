<?php

namespace App\Filament\Resources\Subscriptions\Pages;

use App\Filament\Resources\Subscriptions\SubscriptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function mount(): void
    {
        parent::mount();
        
        $this->dispatch('initialize-subscription-form');
    }
}

