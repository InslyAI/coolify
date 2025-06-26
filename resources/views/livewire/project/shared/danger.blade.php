<div>
    <h2>Danger Zone</h2>
    <div class="">Woah. I hope you know what are you doing.</div>
    @if ($resource && method_exists($resource, 'type') && in_array($resource->type(), ['application', 'service']))
        @can('delete', $resource)
            <h4 class="pt-4">Delete Resource</h4>
            <div class="pb-4">This will stop your containers, delete all related data, etc. Beware! There is no coming back!
            </div>
            <x-modal-confirmation title="Confirm Resource Deletion?" buttonTitle="Delete" isErrorButton submitAction="delete"
                buttonTitle="Delete" :checkboxes="$checkboxes" :actions="['Permanently delete all containers of this resource.']" confirmationText="{{ $resourceName }}"
                confirmationLabel="Please confirm the execution of the actions by entering the Resource Name below"
                shortConfirmationLabel="Resource Name" step3ButtonText="Permanently Delete" />
        @else
            <div class="pt-4">
                <x-alert type="error">
                    You do not have permission to delete this resource. Only administrators and owners can delete resources.
                </x-alert>
            </div>
        @endcan
    @else
        <h4 class="pt-4">Delete Resource</h4>
        <div class="pb-4">This will stop your containers, delete all related data, etc. Beware! There is no coming back!
        </div>
        <x-modal-confirmation title="Confirm Resource Deletion?" buttonTitle="Delete" isErrorButton submitAction="delete"
            buttonTitle="Delete" :checkboxes="$checkboxes" :actions="['Permanently delete all containers of this resource.']" confirmationText="{{ $resourceName }}"
            confirmationLabel="Please confirm the execution of the actions by entering the Resource Name below"
            shortConfirmationLabel="Resource Name" step3ButtonText="Permanently Delete" />
    @endif
</div>
