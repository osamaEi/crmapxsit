<!-- Stages Navigation -->
{!! view_render_event('admin.leads.view.stages.before', ['lead' => $lead]) !!}

<!-- Stages Vue Component -->
<v-lead-stages>
    <x-admin::shimmer.leads.view.stages :count="$lead->pipeline->stages->count() - 1" />
</v-lead-stages>

{!! view_render_event('admin.leads.view.stages.after', ['lead' => $lead]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-lead-stages-template">
        <!-- Stages Container -->
        <div
            class="flex w-full max-w-full"
            :class="{'opacity-50 pointer-events-none': isUpdating}"
        >
            <!-- Stages Item -->
            <template v-for="stage in stages">
                {!! view_render_event('admin.leads.view.stages.items.before', ['lead' => $lead]) !!}

                <div
                    class="stage relative flex h-7 cursor-pointer items-center justify-center bg-white pl-7 pr-4 dark:bg-gray-900 ltr:first:rounded-l-lg rtl:first:rounded-r-lg"
                    :class="{
                        '!bg-green-500 text-white dark:text-gray-900 ltr:after:bg-green-500 rtl:before:bg-green-500': currentStage.sort_order >= stage.sort_order,
                        '!bg-red-500 text-white dark:text-gray-900 ltr:after:bg-red-500 rtl:before:bg-red-500': currentStage.code == 'lost',
                    }"
                    v-if="! ['won', 'lost'].includes(stage.code)"
                    @click="openCommentModal(stage)"
                >
                    <span class="z-20 whitespace-nowrap text-sm font-medium dark:text-white">
                        @{{ stage.name }}
                    </span>
                </div>

                {!! view_render_event('admin.leads.view.stages.items.after', ['lead' => $lead]) !!}
            </template>

            {!! view_render_event('admin.leads.view.stages.items.dropdown.before', ['lead' => $lead]) !!}

            <!-- Won/Lost Stage Item -->
            <x-admin::dropdown position="bottom-right">
                <x-slot:toggle>
                    {!! view_render_event('admin.leads.view.stages.items.dropdown.toggle.before', ['lead' => $lead]) !!}

                    <div
                        class="relative flex h-7 min-w-24 cursor-pointer items-center justify-center rounded-r-lg bg-white pl-7 pr-4 dark:bg-gray-900"
                        :class="{
                            '!bg-green-500 text-white dark:text-gray-900 after:bg-green-500': ['won', 'lost'].includes(currentStage.code) && currentStage.code == 'won',
                            '!bg-red-500 text-white dark:text-gray-900 after:bg-red-500': ['won', 'lost'].includes(currentStage.code) && currentStage.code == 'lost',
                        }"
                        @click="stageToggler = ! stageToggler"
                    >
                        <span class="z-20 whitespace-nowrap text-sm font-medium dark:text-white">
                             @{{ stages.filter(stage => ['won', 'lost'].includes(stage.code)).map(stage => stage.name).join('/') }}
                        </span>

                        <span
                            class="text-2xl dark:text-gray-900"
                            :class="{'icon-up-arrow': stageToggler, 'icon-down-arrow': ! stageToggler}"
                        ></span>
                    </div>

                    {!! view_render_event('admin.leads.view.stages.items.dropdown.toggle.after', ['lead' => $lead]) !!}
                </x-slot>

                <x-slot:menu>
                    {!! view_render_event('admin.leads.view.stages.items.dropdown.menu_item.before', ['lead' => $lead]) !!}

                    <x-admin::dropdown.menu.item
                        v-for="stage in stages.filter(stage => ['won', 'lost'].includes(stage.code))"
                        @click="openModal(stage)"
                    >
                        @{{ stage.name }}
                    </x-admin::dropdown.menu.item>

                    {!! view_render_event('admin.leads.view.stages.items.dropdown.menu_item.after', ['lead' => $lead]) !!}
                </x-slot>
            </x-admin::dropdown>

            {!! view_render_event('admin.leads.view.stages.items.dropdown.after', ['lead' => $lead]) !!}

            {!! view_render_event('admin.leads.view.stages.form_controls.before', ['lead' => $lead]) !!}

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="stageUpdateForm"
            >
                <form @submit="handleSubmit($event, handleFormSubmit)">
                    {!! view_render_event('admin.leads.view.stages.form_controls.modal.before', ['lead' => $lead]) !!}

                    <x-admin::modal ref="stageUpdateModal">
                        <x-slot:header>
                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.header.before', ['lead' => $lead]) !!}

                            <h3 class="text-base font-semibold dark:text-white">
                                @lang('admin::app.leads.view.stages.need-more-info')
                            </h3>

                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.header.after', ['lead' => $lead]) !!}
                        </x-slot>

                        <x-slot:content>
                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.content.before', ['lead' => $lead]) !!}

                            <!-- Won Value -->
                            <template v-if="nextStage.code == 'won'">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.leads.view.stages.won-value')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="price"
                                        name="lead_value"
                                        :value="$lead->lead_value"
                                        v-model="nextStage.lead_value"
                                    />
                                </x-admin::form.control-group>
                            </template>

                            <!-- Lost Reason -->
                            <template v-else>
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.leads.view.stages.lost-reason')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="lost_reason"
                                        v-model="nextStage.lost_reason"
                                    />
                                </x-admin::form.control-group>
                            </template>

                            <!-- Closed At -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.leads.view.stages.closed-at')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="datetime"
                                    name="closed_at"
                                    v-model="nextStage.closed_at"
                                    :label="trans('admin::app.leads.view.stages.closed-at')"
                                />

                                <x-admin::form.control-group.error control-name="closed_at"/>
                            </x-admin::form.control-group>

                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.content.after', ['lead' => $lead]) !!}
                        </x-slot>

                        <x-slot:footer>
                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.footer.before', ['lead' => $lead]) !!}

                            <button
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.leads.view.stages.save-btn')
                            </button>

                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.footer.after', ['lead' => $lead]) !!}
                        </x-slot>
                    </x-admin::modal>

                    {!! view_render_event('admin.leads.view.stages.form_controls.modal.after', ['lead' => $lead]) !!}
                </form>
            </x-admin::form>

            {!! view_render_event('admin.leads.view.stages.form_controls.after', ['lead' => $lead]) !!}

            <!-- Stage Comment + Reminder Modal -->
            <x-admin::modal ref="stageCommentModal">
                <x-slot:header>
                    <h3 class="text-base font-semibold dark:text-white">
                        Add a Comment
                    </h3>
                </x-slot>

                <x-slot:content>
                    <!-- Moving to badge -->
                    <div class="mb-4 rounded-lg bg-blue-50 px-4 py-2 text-sm text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                        Moving stage to:
                        <strong v-text="pendingStage ? pendingStage.name : ''"></strong>
                    </div>

                    <!-- Comment -->
                    <div class="mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Comment
                        <span class="text-gray-400">(optional)</span>
                    </div>
                    <textarea
                        v-model="stageComment"
                        rows="3"
                        class="w-full rounded-lg border border-gray-200 p-3 text-sm text-gray-700 outline-none focus:border-blue-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                        placeholder="Write a comment about this stage change..."
                    ></textarea>

                    <!-- Divider -->
                    <div class="my-4 flex items-center gap-3">
                        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                        <span class="text-xs text-gray-400">REMINDER</span>
                        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                    </div>

                    <!-- Reminder toggle -->
                    <label class="mb-3 flex cursor-pointer items-center gap-2">
                        <div
                            class="relative h-5 w-9 rounded-full transition-colors"
                            :class="stageReminder.enabled ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600'"
                            @click="stageReminder.enabled = !stageReminder.enabled"
                        >
                            <div
                                class="absolute top-0.5 h-4 w-4 rounded-full bg-white shadow transition-transform"
                                :class="stageReminder.enabled ? 'translate-x-4' : 'translate-x-0.5'"
                            ></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Set a reminder for this lead</span>
                    </label>

                    <!-- Reminder fields -->
                    <div v-if="stageReminder.enabled" class="grid grid-cols-1 gap-3 rounded-lg border border-blue-100 bg-blue-50 p-3 dark:border-blue-900 dark:bg-blue-900/10 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Date & Time <span class="text-red-500">*</span></label>
                            <input
                                type="datetime-local"
                                v-model="stageReminder.remind_at"
                                class="w-full rounded border border-gray-200 bg-white px-2.5 py-2 text-sm text-gray-800 outline-none focus:border-blue-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Reminder Note (optional)</label>
                            <input
                                type="text"
                                v-model="stageReminder.note"
                                placeholder="e.g. Follow up call"
                                class="w-full rounded border border-gray-200 bg-white px-2.5 py-2 text-sm text-gray-800 outline-none focus:border-blue-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                            />
                        </div>
                        <p v-if="stageReminder.error" class="col-span-2 text-xs text-red-500">@{{ stageReminder.error }}</p>
                    </div>
                </x-slot>

                <x-slot:footer>
                    <button
                        type="button"
                        class="secondary-button mr-2"
                        @click="proceedWithComment(false)"
                    >
                        Skip
                    </button>

                    <button
                        type="button"
                        class="primary-button"
                        @click="proceedWithComment(true)"
                    >
                        Save & Continue
                    </button>
                </x-slot>
            </x-admin::modal>
        </div>
    </script>

    <script type="module">
        app.component('v-lead-stages', {
            template: '#v-lead-stages-template',

            data() {
                return {
                    isUpdating: false,

                    currentStage: @json($lead->stage),

                    nextStage: null,

                    stages: @json($lead->pipeline->stages),

                    stageToggler: '',

                    pendingStage: null,

                    stageComment: '',

                    stageReminder: {
                        enabled: false,
                        remind_at: '',
                        note: '',
                        error: '',
                    },
                }
            },

            methods: {
                openCommentModal(stage) {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.pendingStage        = stage;
                    this.stageComment        = '';
                    this.stageReminder       = { enabled: false, remind_at: '', note: '', error: '' };

                    this.$refs.stageCommentModal.open();
                },

                proceedWithComment(saveComment) {
                    // Validate reminder if enabled
                    if (saveComment && this.stageReminder.enabled) {
                        if (!this.stageReminder.remind_at) {
                            this.stageReminder.error = 'Please select a reminder date and time.';
                            return;
                        }
                        if (new Date(this.stageReminder.remind_at) <= new Date()) {
                            this.stageReminder.error = 'Reminder date must be in the future.';
                            return;
                        }
                        this.stageReminder.error = '';
                    }

                    this.$refs.stageCommentModal.close();

                    const comment = saveComment ? this.stageComment.trim() : '';

                    // Save reminder if set
                    if (saveComment && this.stageReminder.enabled && this.stageReminder.remind_at) {
                        this.$axios.post(`/admin/leads/{{ $lead->id }}/reminders`, {
                            remind_at: this.stageReminder.remind_at,
                            comment: this.stageReminder.note || null,
                        }).then(() => {
                            this.$emitter.emit('add-flash', { type: 'success', message: 'Reminder set! You will receive an email.' });
                        });
                    }

                    this.update(this.pendingStage, null, comment);
                },

                openModal(stage) {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.nextStage = stage;

                    this.$refs.stageUpdateModal.open();
                },

                handleFormSubmit(event) {
                    let params = {
                        'lead_pipeline_stage_id': this.nextStage.id
                    };

                    if (this.nextStage.code == 'won') {
                        params.lead_value = this.nextStage.lead_value;

                        params.closed_at = this.nextStage.closed_at;
                    } else if (this.nextStage.code == 'lost') {
                        params.lost_reason = this.nextStage.lost_reason;

                        params.closed_at = this.nextStage.closed_at;
                    }

                    this.update(this.nextStage, params);
                },

                update(stage, params = null, comment = '') {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.$refs.stageUpdateModal.close();

                    this.isUpdating = true;

                    this.$axios
                        .put("{{ route('admin.leads.stage.update', $lead->id) }}", params ?? {
                            'lead_pipeline_stage_id': stage.id
                        })
                        .then ((response) => {
                            this.isUpdating = false;

                            this.currentStage = stage;

                            if (comment) {
                                this.$axios.post("{{ route('admin.activities.store') }}", {
                                    type: 'note',
                                    lead_id: {{ $lead->id }},
                                    title: 'Stage → ' + stage.name,
                                    comment: comment,
                                }).then((noteResponse) => {
                                    this.$emitter.emit('on-activity-added', noteResponse.data.data);
                                    this.$parent.$refs.activities.get();
                                });
                            } else {
                                this.$parent.$refs.activities.get();
                            }

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch ((error) => {
                            this.isUpdating = false;

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },
            },
        });
    </script>
@endPushOnce
