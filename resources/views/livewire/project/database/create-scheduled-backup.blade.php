<form class="flex flex-col w-full gap-2 rounded-sm" wire:submit='submit'>
    <x-forms.select id="frequency" label="Frequency" helper="Select a frequency or choose custom to enter a cron expression.">
        <option value="every_minute">Every Minute</option>
        <option value="hourly">Hourly</option>
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
        <option value="yearly">Yearly</option>
        <option value="custom">Custom</option>
    </x-forms.select>
    @if ($frequency === 'custom')
        <x-forms.input placeholder="0 0 * * *" id="custom_frequency" label="Cron Expression" required />
    @endif
    <h2>S3</h2>
    @if ($definedS3s->count() === 0)
        <div class="text-red-500">No validated S3 Storages found.</div>
    @else
        <x-forms.checkbox wire:model.live="saveToS3" label="Save to S3" />
        @if ($saveToS3)
            <x-forms.select id="s3StorageId" label="Select a S3 Storage">
                @foreach ($definedS3s as $s3)
                    <option value="{{ $s3->id }}">{{ $s3->name }}</option>
                @endforeach
            </x-forms.select>
        @endif
    @endif
    <x-forms.button type="submit" @click="modalOpen=false">
        Save
    </x-forms.button>
</form>
