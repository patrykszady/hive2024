<div
    x-data="{
        query: '',
        selected_option: null,
        {{-- frameworks: @entangle('employees'), --}}
        options: [
            @foreach ($employees as $employee)
                {
                    id: {{$employee->id}},
                    name: '{{$employee->first_name}}',
                    disabled: false,
                },
            @endforeach
        ],
        get filteredOptions() {
            return this.query === ''
                ? this.options
                : this.options.filter((framework) => {
                    return framework.name.toLowerCase().includes(this.query.toLowerCase())
                })
        },
    }"
    class="max-w-xs w-full"
    >
    <div x-combobox x-model="selected_option">
        <div class="mt-1 relative rounded-md focus-within:ring-2 focus-within:ring-blue-500">
            <div class="flex items-center justify-between gap-2 w-full bg-white pl-5 pr-3 py-2.5 rounded-md shadow">
                <input
                    x-combobox:input
                    :display-value="framework => framework.name"
                    @change="query = $event.target.value;"
                    class="border-none p-0 focus:outline-none focus:ring-0"
                    placeholder="Select Team Member..."
                />
                <button x-combobox:button class="absolute inset-y-0 right-0 flex items-center pr-2">
                    <!-- Heroicons up/down -->
                    <svg class="shrink-0 w-5 h-5 text-gray-500" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </button>
            </div>

            <div x-combobox:options x-cloak class="absolute left-0 max-w-xs w-full max-h-60 mt-2 z-10 origin-top-right overflow-auto bg-white border border-gray-200 rounded-md shadow-md outline-none" x-transition.out.opacity>
                <ul class="divide-y divide-gray-100">
                    <template
                        x-for="framework in filteredOptions"
                        :key="framework.id"
                        hidden
                        >
                        <li
                            x-combobox:option
                            :value="framework"
                            :disabled="framework.disabled"
                            :class="{
                                'bg-indigo-500/10 text-gray-900': $comboboxOption.isActive,
                                'text-gray-600': ! $comboboxOption.isActive,
                                'opacity-50 cursor-not-allowed': $comboboxOption.isDisabled,
                            }"
                            class="flex items-center cursor-default justify-between gap-2 w-full px-4 py-2 text-sm"
                        >
                            <span x-text="framework.name"></span>

                            <span x-show="$comboboxOption.isSelected" class="text-indigo-600 font-bold">&check;</span>
                        </li>
                    </template>
                </ul>

                <p x-show="filteredOptions.length == 0" class="px-4 py-2 text-sm text-gray-600">No user found.</p>
            </div>
        </div>
    </div>
</div>
