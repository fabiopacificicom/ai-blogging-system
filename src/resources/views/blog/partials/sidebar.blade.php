<div class="sidebar d-none col-auto order-1 d-lg-inline-block pt-2 px-3 mt-lg-3" x-data="{
                    open: null,
                    collapseSidebar(){
                        this.open = !JSON.parse(this.open)
                        localStorage.setItem('sidebar_open', this.open);
                        /* First time its null as the variable was never stored in the localStorage */
                    }
                }" x-init="open = await (JSON.parse(localStorage.getItem('sidebar_open')))">

    <div class="card p-2 p-lg-4 shadow" class="bg-secondary-subtle">
        <button class="btn btn-sm btn-dark" type="button" x-on:click="collapseSidebar()">
            <template x-if="open">
                <i class="bi bi-arrow-bar-left"></i>
            </template>
            <template x-if="!open">
                <i class="bi bi-arrow-bar-right"></i>
            </template>

        </button>
        @include('pacificdev::blog.partials.navigation')
    </div>
</div>
