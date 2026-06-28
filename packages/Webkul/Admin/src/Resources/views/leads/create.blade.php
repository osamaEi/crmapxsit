<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.leads.create.title')
    </x-slot>

    {!! view_render_event('admin.leads.create.form.before') !!}

    {{-- Validation error summary --}}
    @if ($errors->any())
        <div class="mx-auto mb-2 w-full max-w-screen-xl rounded-lg border border-red-200 bg-red-50 px-5 py-4 dark:border-red-800 dark:bg-red-900/20">
            <p class="mb-2 text-sm font-semibold text-red-700 dark:text-red-400">Please fix the following errors:</p>
            <ul class="list-disc pl-5 text-sm text-red-600 dark:text-red-400">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-admin::form :action="route('admin.leads.store')">
        <div class="flex flex-col gap-4">

            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="leads.create" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.leads.create.title')
                    </div>
                </div>
                <div class="flex items-center gap-x-2.5">
                    <button type="submit" class="primary-button">
                        @lang('admin::app.leads.create.save-btn')
                    </button>
                </div>
            </div>

            @if (request('stage_id'))
                <input type="hidden" name="lead_pipeline_stage_id" value="{{ request('stage_id') }}" />
            @endif
            @if (request('pipeline_id'))
                <input type="hidden" name="lead_pipeline_id" value="{{ request('pipeline_id') }}" />
            @endif

            <!-- Form Body -->
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">

                <!-- Section: Lead Info -->
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <h3 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white">Lead Information</h3>

                    <div class="grid grid-cols-2 gap-x-6 gap-y-2 max-md:grid-cols-1">

                        <!-- Title (Lead Name) -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Full Name
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="title"
                                rules="required"
                                label="Full Name"
                                placeholder="Enter full name"
                                value="{{ old('title') }}"
                            />
                            <x-admin::form.control-group.error control-name="title" />
                        </x-admin::form.control-group>

                        <!-- Phone Number (custom attribute) -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Phone Number
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="phone_number"
                                placeholder="+966 5X XXX XXXX"
                                value="{{ old('phone_number') }}"
                            />
                            <x-admin::form.control-group.error control-name="phone_number" />
                        </x-admin::form.control-group>

                        <!-- Email (stored on person) -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Email
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="email"
                                name="person[emails][0][value]"
                                placeholder="email@example.com"
                                value="{{ old('person.emails.0.value') }}"
                            />
                            <input type="hidden" name="person[emails][0][label]" value="work" />
                            <x-admin::form.control-group.error control-name="person[emails][0][value]" />
                        </x-admin::form.control-group>

                        <!-- Country -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Country
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="country"
                                placeholder="e.g. Saudi Arabia"
                                value="{{ old('country') }}"
                            />
                            <x-admin::form.control-group.error control-name="country" />
                        </x-admin::form.control-group>

                        <!-- Nationality -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Nationality
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="nationality"
                                placeholder="e.g. Saudi"
                                value="{{ old('nationality') }}"
                            />
                            <x-admin::form.control-group.error control-name="nationality" />
                        </x-admin::form.control-group>

                        <!-- Source -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Source
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="lead_source_id"
                                rules="required"
                                label="Source"
                            >
                                <option value="">-- Select Source --</option>
                                @foreach (app('Webkul\Lead\Repositories\SourceRepository')->all() as $source)
                                    <option value="{{ $source->id }}" {{ old('lead_source_id') == $source->id ? 'selected' : '' }}>
                                        {{ $source->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="lead_source_id" />
                        </x-admin::form.control-group>

                        <!-- Interested Program -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Interested Program
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="interested_program"
                                placeholder="e.g. MBA, Computer Science"
                                value="{{ old('interested_program') }}"
                            />
                            <x-admin::form.control-group.error control-name="interested_program" />
                        </x-admin::form.control-group>

                        <!-- Degree -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Degree
                            </x-admin::form.control-group.label>
                            @php
                                $degreeAttr = app('Webkul\Attribute\Repositories\AttributeRepository')
                                    ->findOneWhere(['code' => 'degree', 'entity_type' => 'leads']);
                            @endphp
                            <x-admin::form.control-group.control
                                type="select"
                                name="degree"
                            >
                                <option value="">-- Select Degree --</option>
                                @if ($degreeAttr)
                                    @foreach ($degreeAttr->options()->orderBy('sort_order')->get() as $opt)
                                        <option value="{{ $opt->id }}" {{ old('degree') == $opt->id ? 'selected' : '' }}>
                                            {{ $opt->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="degree" />
                        </x-admin::form.control-group>

                        <!-- Sales Owner -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Sales Owner
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="user_id"
                            >
                                <option value="">-- Select Sales Owner --</option>
                                @foreach (app('Webkul\User\Repositories\UserRepository')->all() as $user)
                                    @php $selectedOwner = old('user_id', auth()->guard('user')->id()); @endphp
                                    <option value="{{ $user->id }}" {{ $selectedOwner == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="user_id" />
                        </x-admin::form.control-group>

                        <!-- Lead Status -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Lead Status
                            </x-admin::form.control-group.label>
                            @php
                                $statusAttr = app('Webkul\Attribute\Repositories\AttributeRepository')
                                    ->findOneWhere(['code' => 'lead_status', 'entity_type' => 'leads']);
                            @endphp
                            <x-admin::form.control-group.control
                                type="select"
                                name="lead_status"
                            >
                                <option value="">-- Select Status --</option>
                                @if ($statusAttr)
                                    @foreach ($statusAttr->options()->orderBy('sort_order')->get() as $opt)
                                        <option value="{{ $opt->id }}" {{ old('lead_status') == $opt->id ? 'selected' : '' }}>
                                            {{ $opt->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="lead_status" />
                        </x-admin::form.control-group>

                    </div>
                </div>

                <!-- Hidden required fields with defaults -->
                <div class="hidden">
                    <!-- Lead value default 0 -->
                    <input type="hidden" name="lead_value" value="0" />
                    <!-- Lead type default first -->
                    @php $firstType = app('Webkul\Lead\Repositories\TypeRepository')->first(); @endphp
                    @if ($firstType)
                        <input type="hidden" name="lead_type_id" value="{{ $firstType->id }}" />
                    @endif
                    <!-- Person name: pre-filled with old title, kept in sync by JS -->
                    <input type="hidden" name="person[name]" id="person_name_hidden" value="{{ old('title', '') }}" />
                    <!-- entity_type required by AttributeValueRepository -->
                    <input type="hidden" name="entity_type" value="leads" />
                </div>

            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.leads.create.form.after') !!}

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var titleInput = document.querySelector('[name="title"]');
            var personName = document.getElementById('person_name_hidden');

            function syncName() {
                if (titleInput && personName) {
                    personName.value = titleInput.value.trim() || 'Lead';
                }
            }

            if (titleInput) {
                // Sync on every keystroke
                titleInput.addEventListener('input', syncName);
                // Sync immediately so the hidden field is never empty on first render
                syncName();
            }

            // Sync on any click of the submit button (fires before VeeValidate)
            document.querySelectorAll('[type="submit"]').forEach(function (btn) {
                btn.addEventListener('click', syncName, true);
            });
        });
    </script>
    @endpush
</x-admin::layouts>
