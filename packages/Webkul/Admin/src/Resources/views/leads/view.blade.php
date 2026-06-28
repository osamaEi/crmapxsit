<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.leads.view.title', ['title' => strip_tags($lead->title)])
    </x-slot>

    <!-- Content -->
    <div class="relative flex gap-4 max-lg:flex-wrap">

        <!-- Left Panel -->
        {!! view_render_event('admin.leads.view.left.before', ['lead' => $lead]) !!}

        <div class="max-lg:min-w-full max-lg:max-w-full [&>div:last-child]:border-b-0 lg:sticky lg:top-[73px] flex min-w-[394px] max-w-[394px] flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Lead Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrumb's -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs
                        name="leads.view"
                        :entity="$lead"
                    />

                    @if (bouncer()->hasPermission('leads.edit'))
                        <a
                            href="{{ route('admin.leads.edit', $lead->id) }}"
                            class="secondary-button flex items-center gap-1 px-3 py-1.5 text-sm"
                        >
                            <i class="icon-edit text-base"></i>
                            @lang('admin::app.leads.view.edit-btn')
                        </a>
                    @endif
                </div>

                <div class="mb-2">
                    @if (($days = $lead->rotten_days) > 0)
                        @php
                            $lead->tags->prepend([
                                'name' => '<span class="icon-rotten text-base"></span>' . trans('admin::app.leads.view.rotten-days', ['days' => $days]),
                                'color' => '#FEE2E2'
                            ]);
                        @endphp
                    @endif

                    {!! view_render_event('admin.leads.view.tags.before', ['lead' => $lead]) !!}

                    <!-- Tags -->
                    <x-admin::tags
                        :attach-endpoint="route('admin.leads.tags.attach', $lead->id)"
                        :detach-endpoint="route('admin.leads.tags.detach', $lead->id)"
                        :added-tags="$lead->tags"
                    />

                    {!! view_render_event('admin.leads.view.tags.after', ['lead' => $lead]) !!}
                </div>


                {!! view_render_event('admin.leads.view.title.before', ['lead' => $lead]) !!}

                <!-- Title -->
                <h1 class="text-lg font-bold dark:text-white">
                    {{ $lead->title }}
                </h1>

                {!! view_render_event('admin.leads.view.title.after', ['lead' => $lead]) !!}

                <!-- Activity Actions -->
                <div class="flex flex-wrap gap-2">
                    {!! view_render_event('admin.leads.view.actions.before', ['lead' => $lead]) !!}

                    @if (bouncer()->hasPermission('mail.compose'))
                        <!-- Mail Activity Action -->
                        <x-admin::activities.actions.mail
                            :entity="$lead"
                            entity-control-name="lead_id"
                        />
                    @endif

                    @if (bouncer()->hasPermission('activities.create'))
                        <!-- File Activity Action -->
                        <x-admin::activities.actions.file
                            :entity="$lead"
                            entity-control-name="lead_id"
                        />

                        <!-- Note Activity Action -->
                        <x-admin::activities.actions.note
                            :entity="$lead"
                            entity-control-name="lead_id"
                        />

                        <!-- Activity Action -->
                        <x-admin::activities.actions.activity
                            :entity="$lead"
                            entity-control-name="lead_id"
                        />
                    @endif

                    {!! view_render_event('admin.leads.view.actions.after', ['lead' => $lead]) !!}
                </div>
            </div>

            <!-- Lead Attributes -->
            @include ('admin::leads.view.attributes')

            <!-- Contact Person -->
            @include ('admin::leads.view.person')
        </div>

        {!! view_render_event('admin.leads.view.left.after', ['lead' => $lead]) !!}

        {!! view_render_event('admin.leads.view.right.before', ['lead' => $lead]) !!}

        <!-- Right Panel -->
        <div class="flex w-full flex-col gap-4 rounded-lg">
            <!-- Stages Navigation -->
            @include ('admin::leads.view.stages')

            <!-- Stage History -->
            <v-stage-history lead-id="{{ $lead->id }}"></v-stage-history>

            <!-- Reminders -->
            <v-lead-reminders lead-id="{{ $lead->id }}"></v-lead-reminders>

            @pushOnce('scripts')
            <script type="text/x-template" id="v-stage-history-template">
                <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                        <h3 class="text-sm font-semibold dark:text-white">Stage History</h3>
                        <button
                            @click="open = !open"
                            class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        >
                            @{{ open ? 'Hide' : 'Show' }}
                        </button>
                    </div>

                    <div v-show="open">
                        <div v-if="loading" class="flex items-center justify-center py-6">
                            <span class="text-xs text-gray-400">Loading...</span>
                        </div>

                        <div v-else-if="history.length === 0" class="px-4 py-5 text-center text-xs text-gray-400">
                            No stage changes recorded yet.
                        </div>

                        <ul v-else class="divide-y divide-gray-100 dark:divide-gray-800">
                            <li
                                v-for="item in history"
                                :key="item.id"
                                class="flex items-start gap-3 px-4 py-3"
                            >
                                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-brandColor"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-baseline gap-2">
                                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">@{{ item.stage_name }}</span>
                                        <span v-if="item.user_name" class="text-[10px] text-gray-400">by @{{ item.user_name }}</span>
                                        <span class="ml-auto text-[10px] text-gray-400">@{{ formatDate(item.created_at) }}</span>
                                    </div>
                                    <p v-if="item.comment" class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">@{{ item.comment }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </script>

            <script type="module">
                app.component('v-stage-history', {
                    template: '#v-stage-history-template',

                    props: ['leadId'],

                    data() {
                        return {
                            open: true,
                            loading: false,
                            history: [],
                        };
                    },

                    mounted() {
                        this.load();
                    },

                    methods: {
                        load() {
                            this.loading = true;
                            this.$axios.get(`/admin/leads/${this.leadId}/stage-history`)
                                .then(r => { this.history = r.data; })
                                .finally(() => { this.loading = false; });
                        },

                        formatDate(dt) {
                            if (!dt) return '';
                            const d = new Date(dt);
                            return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        },
                    },
                });
            </script>
            <!-- Reminders template -->
            <script type="text/x-template" id="v-lead-reminders-template">
                <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                        <h3 class="text-sm font-semibold dark:text-white">Reminders</h3>
                        <button
                            @click="showForm = !showForm"
                            class="flex items-center gap-1 rounded bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400"
                        >
                            <span>+ Add Reminder</span>
                        </button>
                    </div>

                    <!-- Add Reminder Form -->
                    <div v-if="showForm" class="border-b border-gray-100 px-4 py-4 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Reminder Date & Time <span class="text-red-500">*</span></label>
                                <input
                                    type="datetime-local"
                                    v-model="form.remind_at"
                                    class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm text-gray-800 outline-none focus:border-blue-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                />
                                <p v-if="errors.remind_at" class="mt-1 text-xs text-red-500">@{{ errors.remind_at }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Note (optional)</label>
                                <input
                                    type="text"
                                    v-model="form.comment"
                                    placeholder="e.g. Follow up on proposal"
                                    class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm text-gray-800 outline-none focus:border-blue-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                />
                            </div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <button
                                @click="save"
                                :disabled="saving"
                                class="rounded bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                            >
                                @{{ saving ? 'Saving...' : 'Save Reminder' }}
                            </button>
                            <button @click="showForm = false" class="rounded border border-gray-200 px-4 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Reminders List -->
                    <div v-if="loading" class="flex items-center justify-center py-6">
                        <span class="text-xs text-gray-400">Loading...</span>
                    </div>

                    <div v-else-if="reminders.length === 0 && !showForm" class="px-4 py-5 text-center text-xs text-gray-400">
                        No reminders set yet.
                    </div>

                    <ul v-else class="divide-y divide-gray-100 dark:divide-gray-800">
                        <li
                            v-for="r in reminders"
                            :key="r.id"
                            class="flex items-start gap-3 px-4 py-3"
                        >
                            <span class="mt-1 text-base" :class="r.sent ? 'text-green-500' : 'text-blue-500'">
                                @{{ r.sent ? '✓' : '🔔' }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-baseline gap-2">
                                    <span class="text-xs font-semibold" :class="r.sent ? 'text-gray-400 line-through' : 'text-gray-800 dark:text-gray-200'">
                                        @{{ formatDate(r.remind_at) }}
                                    </span>
                                    <span v-if="r.sent" class="rounded bg-green-50 px-1.5 py-0.5 text-[10px] font-medium text-green-600">Sent</span>
                                    <span v-else class="rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-medium text-blue-600">Pending</span>
                                </div>
                                <p v-if="r.comment" class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">@{{ r.comment }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </script>

            <script type="module">
                app.component('v-lead-reminders', {
                    template: '#v-lead-reminders-template',

                    props: ['leadId'],

                    data() {
                        return {
                            loading: false,
                            saving: false,
                            showForm: false,
                            reminders: [],
                            form: { remind_at: '', comment: '' },
                            errors: {},
                        };
                    },

                    mounted() {
                        this.load();
                    },

                    methods: {
                        load() {
                            this.loading = true;
                            this.$axios.get(`/admin/leads/${this.leadId}/reminders`)
                                .then(r => { this.reminders = r.data; })
                                .finally(() => { this.loading = false; });
                        },

                        save() {
                            this.errors = {};
                            if (!this.form.remind_at) {
                                this.errors.remind_at = 'Reminder date is required.';
                                return;
                            }
                            this.saving = true;
                            this.$axios.post(`/admin/leads/${this.leadId}/reminders`, this.form)
                                .then(() => {
                                    this.showForm = false;
                                    this.form = { remind_at: '', comment: '' };
                                    this.$emitter.emit('add-flash', { type: 'success', message: 'Reminder set! You will receive an email.' });
                                    this.load();
                                })
                                .catch(e => {
                                    const msg = e.response?.data?.message || 'Failed to save reminder.';
                                    this.$emitter.emit('add-flash', { type: 'error', message: msg });
                                })
                                .finally(() => { this.saving = false; });
                        },

                        formatDate(dt) {
                            if (!dt) return '';
                            return new Date(dt).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' });
                        },
                    },
                });
            </script>
            @endPushOnce

            <!-- Activities -->
            {!! view_render_event('admin.leads.view.activities.before', ['lead' => $lead]) !!}

            <x-admin::activities
                :endpoint="route('admin.leads.activities.index', $lead->id)"
                :email-detach-endpoint="route('admin.leads.emails.detach', $lead->id)"
                :activeType="request()->query('from') === 'quotes' ? 'quotes' : 'all'"
                :extra-types="[
                    ['name' => 'description', 'label' => trans('admin::app.leads.view.tabs.description')],
                    ['name' => 'products', 'label' => trans('admin::app.leads.view.tabs.products')],
                    ['name' => 'quotes', 'label' => trans('admin::app.leads.view.tabs.quotes')],
                ]"
            >
                <!-- Products -->
                <x-slot:products>
                    @include ('admin::leads.view.products')
                </x-slot>

                <!-- Quotes -->
                <x-slot:quotes>
                    @include ('admin::leads.view.quotes')
                </x-slot>

                <!-- Description -->
                <x-slot:description>
                    <div class="p-4 dark:text-white">
                        {{ $lead->description }}
                    </div>
                </x-slot>
            </x-admin::activities>

            {!! view_render_event('admin.leads.view.activities.after', ['lead' => $lead]) !!}
        </div>

        {!! view_render_event('admin.leads.view.right.after', ['lead' => $lead]) !!}
    </div>
</x-admin::layouts>
