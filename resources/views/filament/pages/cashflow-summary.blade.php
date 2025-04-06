<x-filament::page>
    <div class="mb-4 flex gap-4">
        <x-filament::input.wrapper>
            <x-filament::input 
                type="date" 
                wire:model.live="startDate"
                :label="__('Start Date')"
            />
        </x-filament::input.wrapper>

        <x-filament::input.wrapper>
            <x-filament::input 
                type="date" 
                wire:model.live="endDate"
                :label="__('End Date')"
            />
        </x-filament::input.wrapper>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <x-filament::card>
            <h2 class="text-lg font-bold tracking-tight">Opening Balance</h2>
            <p class="text-3xl font-semibold">UGX {{ $openingBalance }}</p>
        </x-filament::card>

        <x-filament::card>
            <h2 class="text-lg font-bold tracking-tight text-green-600">Total Cash In</h2>
            <p class="text-3xl font-semibold text-green-600">UGX {{ $totalCashIn }}</p>
        </x-filament::card>

        <x-filament::card>
            <h2 class="text-lg font-bold tracking-tight text-red-600">Total Cash Out</h2>
            <p class="text-3xl font-semibold text-red-600">UGX {{ $totalCashOut }}</p>
        </x-filament::card>

        <x-filament::card>
            <h2 class="text-lg font-bold tracking-tight {{ $rawCurrentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">Current Balance</h2>
            <p class="text-3xl font-semibold {{ $rawCurrentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">UGX {{ $currentBalance }}</p>
        </x-filament::card>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-2">
        <x-filament::section>
            <x-slot name="heading">Cash In Breakdown</x-slot>
            <div class="space-y-4">
                @foreach ($cashInBreakdown as $label => $amount)
                    <div class="flex justify-between">
                        <span>{{ $label }}</span>
                        <span class="font-medium">UGX {{ number_format($amount, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Cash Out Breakdown</x-slot>
            
            <div class="space-y-6">
                @foreach ($cashOutBreakdown as $category => $types)
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium">
                            {{ $category === 'admin_cost' ? 'Administrative Costs' : 'Project Costs' }}
                        </h3>
                        
                        @foreach ($types as $type => $data)
                            <div class="rounded-lg border p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-medium">{{ $type }}</h4>
                                    <span class="text-lg font-semibold">
                                        UGX {{ number_format($data['total'], 2) }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach ($data['items'] as $item)
                                        <div class="flex justify-between text-sm border-t pt-2">
                                            <div>
                                                <div class="text-gray-600">{{ $item['reference'] }}</div>
                                                <div>{{ $item['description'] }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div>{{ number_format($item['amount'], 2) }} {{ $item['currency'] }}</div>
                                                <div class="text-gray-500">{{ $item['date'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>

    <x-filament::modal wire:model="showBreakdownModal" width="4xl">
        <x-slot name="heading">{{ $selectedCategory }} Breakdown</x-slot>
        
        <div class="space-y-4">
            @foreach($breakdownData as $item)
                <div class="rounded-lg border p-4">
                    <div class="flex justify-between">
                        <div>
                            <div class="font-medium">{{ $item['type'] }}</div>
                            <div class="text-sm text-gray-500">{{ $item['reference'] }}</div>
                            <div class="mt-1">{{ $item['description'] }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-medium">{{ $item['amount'] }} {{ $item['currency'] }}</div>
                            <div class="text-sm text-gray-500">{{ $item['date'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::modal>
</x-filament-panels::page>
