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
