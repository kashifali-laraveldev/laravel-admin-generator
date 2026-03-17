<nav id="sidebar">
    <div class="sidebar-header">
        <div class="nav_list">
            <a href="{{ url('admin') }}" id="nav_link"
                class="nav_link {{ request()->is('admin') ? 'active-sidebar' : '' }}">
                <i class="fa-solid fa-gauge-high fa-lg"></i>
                <span class="nav_name">Dashboard</span>
            </a>
            <a href="{{ url('admin/auth_groups') }}" id="nav_link"
                class="nav_link {{ request()->is('admin/auth_groups*') ? 'active-sidebar' : '' }}">
                <i class="fa-solid fa-layer-group fa-lg"></i>
                <span class="nav_name">Auth Group</span>
            </a>
            <a href="{{ url('admin/users') }}" id="nav_link"
                class="nav_link {{ request()->is('admin/users*') ? 'active-sidebar' : '' }}">
                <i class="fa-solid fa-universal-access fa-lg"></i>
                <span class="nav_name">User</span>
            </a>
            <a href="{{ url('admin/auth_user_group') }}" id="nav_link"
                class="nav_link {{ request()->is('admin/auth_user_group*') ? 'active-sidebar' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span class="nav_name">Auth Group User</span>
            </a>
            <a href="{{ url('admin/crud') }}" id="nav_link"
                class="nav_link {{ request()->is('admin/crud') || request()->is('admin/crud/*') ? 'active-sidebar' : '' }}">
                <i class="fa-solid fa-list"></i>
                <span class="nav_name">CRUD</span>
            </a>
            <a href="{{ url('admin/crud-schema') }}" id="nav_link"
                class="nav_link {{ request()->is('admin/crud-schema') || request()->is('admin/crud-schema/*') ? 'active-sidebar' : '' }}">
                <i class="fa-solid fa-table"></i>
                <span class="nav_name">Schema Builder</span>
            </a>
        </div>
    </div>
</nav>
