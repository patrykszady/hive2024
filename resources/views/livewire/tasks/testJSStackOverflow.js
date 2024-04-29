<div id='widget-pane'>
    <div class="flex flex-col justify-center">
        <div id="gs" wire:key="{{rand()}}" class="grid-stack w-full sm:max-w-[660px] md:max-w-[788px] lg:max-w-[1044px] xl:max-w-[1300px] 2xl:max-w-[1556px] mx-auto grid-stack-start grid-stack-instance-1 grid-stack-animate" wire:ignore.self>
            @foreach($structure as $widget)
                <livewire:dashboard.dashboard-widget :id="'widget_id_' . rand()" :key="'widget_id_' . rand()" :widget_id="$widget['id']" :widget_x="$widget['x']" :widget_y="$widget['y']" :widget_auto_position="$widget['autoPosition']" :making_changes=$make_changes></livewire:dashboard.dashboard-widget>
            @endforeach
        </div>
    </div>

    @script
        <script type="text/javascript">
                // Once all the of the Livewire widget components are initalised, initalise the grid stack instance.
                var count = 0
                Livewire.hook('component.init', ({ component, cleanup }) => {
                    if(component.name === 'dashboard.dashboard-widget'){
                        count++
                        if(count === $wire.structure.length){
                            count = 0

                            initializeGridStack();
                        }
                    }
                })

                // If there aren't any Livewire widget components, we still need to initalise the grid stack instance.
                Livewire.hook('morph.updating', ({ el, component, toEl, skip, childrenOnly }) => {
                    initializeGridStack();
                })

                var grid;
                var serialisedData;

                let options = {
                    column: 6,
                    animate: true,
                    cellHeight: '11rem',
                    margin: '10px',
                    float: false,
                    alwaysShowResizeHandle: 'mobile',
                    disableDrag: true,
                    disableResize: true,
                    disableOneColumnMode: true,
                    removable: '#delete_widget',
                };

                function initializeGridStack() {
                    options.disableDrag = !$wire.make_changes;
                    options.disableResize = !$wire.make_changes;

                    grid = GridStack.init(options);
                    $('.grid-stack-start').removeClass('grid-stack-start');
                    serialisedData = grid.save();
                }

                $(document).on('Add-Widget', (event, widget_id) => {
                    serialisedData = grid.save(false);
                    Livewire.dispatch("add-widget", {widget_id: widget_id, data: serialisedData});
                })

                $(document).on('Serialise-Widget-Data', event => {
                    serialisedData = grid.save(false);
                    Livewire.dispatch('save-widget-data', {data: serialisedData});
                })

                $(document).on('Resize-Widget', (event, item, w, h) => {
                    grid.update(item, {w:w,h:h});
                })
        </script>
    @endscript
</div>
