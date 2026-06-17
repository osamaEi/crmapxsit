<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.leads.edit.title')
    </x-slot>

    {!! view_render_event('admin.leads.edit.form_controls.before', ['lead' => $lead]) !!}

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

    <x-admin::form
        :action="route('admin.leads.update', $lead->id)"
        method="PUT"
    >
        <div class="flex flex-col gap-4">

            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="leads.edit" :entity="$lead" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.leads.edit.title')
                    </div>
                </div>
                <div class="flex items-center gap-x-2.5">
                    <a href="{{ route('admin.leads.view', $lead->id) }}" class="secondary-button">
                        Cancel
                    </a>
                    <button type="submit" class="primary-button">
                        @lang('admin::app.leads.edit.save-btn')
                    </button>
                </div>
            </div>

            <!-- Keep pipeline stage -->
            <input type="hidden" name="lead_pipeline_stage_id" value="{{ $lead->lead_pipeline_stage_id }}" />

            <!-- Form Body -->
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">

                <!-- Section: Lead Info -->
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <h3 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white">Lead Information</h3>

                    <div class="grid grid-cols-2 gap-x-6 gap-y-2 max-md:grid-cols-1">

                        <!-- Title / Full Name -->
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
                                :value="old('title', $lead->title)"
                            />
                            <x-admin::form.control-group.error control-name="title" />
                        </x-admin::form.control-group>

                        <!-- Phone Number -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Phone Number
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="phone_number"
                                placeholder="+966 5X XXX XXXX"
                                :value="old('phone_number', $lead->phone_number ?? '')"
                            />
                            <x-admin::form.control-group.error control-name="phone_number" />
                        </x-admin::form.control-group>

                        <!-- Email -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Email
                            </x-admin::form.control-group.label>
                            @php
                                $personEmail = $lead->person?->emails[0]['value'] ?? '';
                            @endphp
                            <x-admin::form.control-group.control
                                type="email"
                                name="person[emails][0][value]"
                                placeholder="email@example.com"
                                :value="old('person.emails.0.value', $personEmail)"
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
                                :value="old('country', $lead->country ?? '')"
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
                                :value="old('nationality', $lead->nationality ?? '')"
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
                                    <option value="{{ $source->id }}" {{ old('lead_source_id', $lead->lead_source_id) == $source->id ? 'selected' : '' }}>
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
                                :value="old('interested_program', $lead->interested_program ?? '')"
                            />
                            <x-admin::form.control-group.error control-name="interested_program" />
                        </x-admin::form.control-group>

                        <!-- Degree -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Degree
                            </x-admin::form.control-group.label>
                            @php
                                $degreeAttr    = app('Webkul\Attribute\Repositories\AttributeRepository')
                                    ->findOneWhere(['code' => 'degree', 'entity_type' => 'leads']);
                                $currentDegree = old('degree', $lead->degree);
                            @endphp
                            <x-admin::form.control-group.control type="select" name="degree">
                                <option value="">-- Select Degree --</option>
                                @if ($degreeAttr)
                                    @foreach ($degreeAttr->options()->orderBy('sort_order')->get() as $opt)
                                        <option value="{{ $opt->id }}" {{ $currentDegree == $opt->id ? 'selected' : '' }}>
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
                            <x-admin::form.control-group.control type="select" name="user_id">
                                <option value="">-- Select Sales Owner --</option>
                                @foreach (app('Webkul\User\Repositories\UserRepository')->all() as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $lead->user_id) == $user->id ? 'selected' : '' }}>
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
                                $statusAttr    = app('Webkul\Attribute\Repositories\AttributeRepository')
                                    ->findOneWhere(['code' => 'lead_status', 'entity_type' => 'leads']);
                                $currentStatus = old('lead_status', $lead->lead_status);
                            @endphp
                            <x-admin::form.control-group.control type="select" name="lead_status">
                                <option value="">-- Select Status --</option>
                                @if ($statusAttr)
                                    @foreach ($statusAttr->options()->orderBy('sort_order')->get() as $opt)
                                        <option value="{{ $opt->id }}" {{ $currentStatus == $opt->id ? 'selected' : '' }}>
                                            {{ $opt->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="lead_status" />
                        </x-admin::form.control-group>

                    </div>
                </div>

                <!-- Hidden required fields -->
                <div class="hidden">
                    <input type="hidden" name="entity_type" value="leads" />
                    <input type="hidden" name="lead_value" value="{{ $lead->lead_value ?? 0 }}" />
                    @php $firstType = app('Webkul\Lead\Repositories\TypeRepository')->first(); @endphp
                    @if ($firstType)
                        <input type="hidden" name="lead_type_id" value="{{ $lead->lead_type_id ?? $firstType->id }}" />
                    @endif
                    <!-- person id to update existing contact -->
                    @if ($lead->person)
                        <input type="hidden" name="person[id]" value="{{ $lead->person->id }}" />
                        <input type="hidden" name="person[name]" value="{{ $lead->person->name }}" />
                    @else
                        <input type="hidden" name="person[name]" value="{{ $lead->title }}" />
                    @endif
                </div>

            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.leads.edit.form_controls.after', ['lead' => $lead]) !!}
</x-admin::layouts>
