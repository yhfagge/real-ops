<div>
    @if(session()->has('booking-requested'))
        <div class="p-2 bg-green-200 text-green-800 border-2 border-green-700">
            {{ session()->get('booking-requested') }}
        </div>
    @endif
    @if(session()->has('booking-confirmed'))
        <div class="p-2 bg-green-200 text-green-800 border-2 border-green-700">
            {{ session()->get('booking-confirmed') }} &#128747;
        </div>
    @endif
    @if(session()->has('booking-confirmation-failed'))
        <div class="p-2 bg-red-200 text-red-800 border-2 border-red-700">
            {{ session()->get('booking-confirmation-failed') }}
        </div>
    @endif
    @forelse($bookables as $bookable)
        @if($bookable->type == 'flight')
            <div x-data="{showConfirm: false}">
                <div class="-mx-1 flex font-light rounded bg-gray-100 mt-4 px-2 py-3">
                    <div class="px-1 w-auto lg:w-3/4">
                        <span class="font-semibold">{{ $bookable->data['callsign'] }}</span> departing
                        <span class="font-semibold">
                            {{ $bookable->begin_date->format('Y-m-d') }}
                            {{ $bookable->begin_time->format('H:i') }}z
                        </span>
                        @isset($bookable->data['origin_airport_icao'])
                            from
                            <span class="font-semibold">
                                {{ $bookable->data['origin_airport_icao'] }}
                            </span>
                        @endisset
                        @isset($bookable->data['destination_airport_icao'])
                            arriving at
                            <span class="font-semibold">
                                {{ $bookable->data['destination_airport_icao'] }}
                            </span>
                            (approx.
                            {{ $bookable->end_date->format('Y-m-d') }}
                            {{ $bookable->end_time->format('H:i') }}z)
                        @endisset
                    </div>
                    @if (!$bookable->isBooked())
                        <div x-show="!showConfirm" class="px-1 w-1/4 flex justify-center items-center">
                            <button @click="showConfirm = true" class="btn-sm btn-blue-secondary">Book this!</button>
                        </div>
                    @else
                        <div class="px-1 w-1/4 flex justify-center items-center">
                            <span class="py-2 px-1 text-xs rounded-tl-lg rounded-br-lg bg-blue-200">BOOKED!</span>
                        </div>
                    @endif
                </div>
                @if (!$bookable->isBooked())
                    <div x-show="showConfirm" @click.away="showConfirm = false" class="mt-4">
                        @error('email')
                            <div class="mb-2 p-2 bg-red-200 text-red-800 border-2 border-red-700">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="-mx-1 flex font-light rounded bg-blue-100 mb-12 py-2">
                            <div class="px-1 w-3/4">
                                <input wire:model.lazy="email" class="input w-full" type="email" placeholder="Enter your e-mail" required>
                            </div>
                            <div class="px-1 w-1/4 flex justify-center items-center">
                                <button wire:click="bookBookable({{ $bookable->id }})" class="btn-sm btn-blue w-full" type="submit">Confirm!</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @empty
        No bookables added yet...
    @endforelse
</div>
