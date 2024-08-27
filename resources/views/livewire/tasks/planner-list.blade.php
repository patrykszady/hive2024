<div>
    {{-- PROJECTS FOREACH HERE --}}
    <div class="sticky top-0 z-20 flex-none shadow bg-white overflow-x-scroll" x-bind="scrollSync">
        {{-- PROJECTS FOREACH HERE --}}
        <div class="divide-x divide-gray-100 text-sm leading-6 text-gray-500 grid grid-flow-col auto-cols-max">
            {{-- First. leftmost table column on the first row.  --}}
            <div class="col-end-1 w-14 bg-white"></div>

            @foreach($projects as $project_index => $project)
                {{-- items-center justify-center  --}}
                <div class="w-48 p-3 border-b-4">
                    <span class="font-semibold text-gray-800">
                        {{ Str::limit($project->address, 22) }}
                    </span>
                    <br>
                    <span class="font-normal italic text-gray-600">
                        {{ Str::limit($project->project_name, 22) }}
                    </span>
                    <br>
                    <span class="font-normal italic text-gray-600">
                        {{ $project->id }}
                    </span>
                </div>
                {{-- Selected Project (why ... makes sense for the tailwind calendar template we're using .. might want to implenent this on the hirizontal Days /dates div) --}}
                {{-- <div class="flex items-center justify-center py-3">
                    <span class="flex items-baseline">Wed <span
                            class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></span>
                </div> --}}
            @endforeach
        </div>
    </div>

    {{-- HORIZONTAL LINES HERE --}}
    <div class="flex flex-auto overflow-x-auto" x-bind="scrollSync">
        <div class="sticky left-0 z-10 w-14 flex-none bg-white ring-1 ring-gray-100"></div>
        <div>
            <div style="width: 2300px;">
                @foreach($days as $day_index => $day)
                    <div class="sticky left-0 z-20 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-800">
                        <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                        <br>
                        <span class="italics">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                    </div>
                    <div class="grid-stack bg-indigo-100 mb-2 -mt-10"
                        x-data="{
                            init() {
                                let grids = GridStack.initAll({
                                    column: 12,
                                    cellHeight: '60px',
                                    cellWidth: '300px',
                                    float: true,
                                    {{-- resizable: {
                                        handles: 'n, s'
                                    }, --}}
                                    minRow: 3,
                                    {{-- margin: 10, --}}
                                    acceptWidgets: true,
                                    {{-- removable: '.trash', // drag-out delete class --}}
                                });

                                grids[{{$day_index}}].on('added change', function(event, items) {
                                    {{-- let newItems = [];
                                    items.forEach ((el) => {
                                        newItems.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id});
                                    });

                                    $wire.taskMoved(newItems); --}}
                                });
                                {{-- GridStack.setupDragIn('.noDateTasks .grid-stack-item', { appendTo: 'body' }); --}}
                            }
                        }"
                        >

                        <div class="grid-stack-item cursor-pointer" gs-no-resize="true">
                            <div class="m-2">
                                <div class="pl-1 grid-stack-item-content border border-solid border-indigo-400 bg-indigo-50 h-14 hover:bg-white font-bold rounded-md text-clip overflow-hidden">
                                    <span
                                        class="text-indigo-800"
                                        >
                                        Item {{$day_index}}
                                    </span>
                                    <br>
                                    <span class="text-sm font-medium text-gray-600">{{$day['formatted_date']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.store('scrollSync', {
                scrollLeft: 0,
            })
            Alpine.bind('scrollSync', {
                '@scroll'(){
                    this.$store.scrollSync.scrollLeft = this.$el.scrollLeft
                },
                'x-effect'() {
                    this.$el.scrollLeft = this.$store.scrollSync.scrollLeft
                }
            })
        })
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">
</div>

