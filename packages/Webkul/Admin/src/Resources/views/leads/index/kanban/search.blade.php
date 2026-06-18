{!! view_render_event('admin.leads.index.kanban.search.before') !!}

<v-kanban-search
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @search="search"
    @quicksearch="quickSearch"
>

</v-kanban-search>

{!! view_render_event('admin.leads.index.kanban.search.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-kanban-search-template"
    >
        <div class="relative flex max-w-[480px] w-full items-center max-md:max-w-full">
            <span class="icon-search absolute top-1/2 -translate-y-1/2 text-xl text-gray-400 ltr:left-3 rtl:right-3 pointer-events-none"></span>

            <input
                type="text"
                ref="searchInput"
                class="block w-full rounded-lg border border-gray-200 bg-white py-1.5 leading-6 text-gray-700 placeholder-gray-400 transition-all hover:border-gray-400 focus:border-brandColor focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 ltr:pl-10 ltr:pr-8 rtl:pl-8 rtl:pr-10"
                placeholder="Search by name, phone, or email..."
                autocomplete="off"
                v-model="searchTerm"
                @input="onInput"
                @keyup.enter="doSearch"
                @keyup.esc="clearSearch"
            />

            <span
                v-if="searchTerm"
                class="absolute top-1/2 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600 ltr:right-3 rtl:left-3 text-lg leading-none"
                @click="clearSearch"
            >✕</span>
        </div>
    </script>

    <script type="module">
        app.component('v-kanban-search', {
            template: '#v-kanban-search-template',

            props: ['isLoading', 'available', 'applied'],

            emits: ['search', 'quicksearch'],

            data() {
                return {
                    searchTerm: '',
                    debounceTimer: null,
                };
            },

            mounted() {
                // Restore previously applied search term
                const col = this.applied.filters.columns.find(c => c.index === '__q__');
                if (col?.value?.length) {
                    this.searchTerm = col.value[0];
                }
            },

            methods: {
                onInput() {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => {
                        this.doSearch();
                    }, 400);
                },

                doSearch() {
                    clearTimeout(this.debounceTimer);
                    this.$emit('quicksearch', this.searchTerm.trim());
                },

                clearSearch() {
                    this.searchTerm = '';
                    this.$emit('quicksearch', '');
                    this.$refs.searchInput.focus();
                },

                // Legacy: kept so parent @search binding still works
                search() {},
            },
        });
    </script>
@endPushOnce
