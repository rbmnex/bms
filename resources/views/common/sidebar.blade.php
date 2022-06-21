<nav id="sidebarMenu" class="sidebar d-md-block bg-primary text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
        <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" class="fas fa-times" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation"></a>
            </div>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{route('home')}}" class="nav-link">
                    <span class="sidebar-icon"><span class="fas fa-file-alt"></span></span>
                    <span>Overview</span>
                </a>
            </li>
            <li class="nav-item">
                <span class="nav-link collapsed d-flex justify-content-between align-items-center" id="menu-inventory" data-toggle="collapse" data-target="#submenu-app-inventory">
                    <span>
                        <span class="sidebar-icon"><span class="fas fa-warehouse"></span></span>
                        Inventory
                    </span>
                    <span class="link-arrow"><span class="fas fa-chevron-right"></span></span>
                </span>
                <div class="multi-level collapse" role="list" id="submenu-app-inventory" aria-expanded="false">
                    <ul class="flex-column nav">
                        <li class="nav-item " id="bridgeDir"><a class="nav-link" href="{{route('bridge.view')}}?action=list"><span class="">Bridge Directory</span></a></li>
                        @if(Auth::user()->hasRole(['Administrator','Registrar']))
                        <li class="nav-item " id="bridgeTask"><a class="nav-link" href="{{route('bridge.view')}}?action=inbox"><span class="">Inbox</span></a></li>
                        @endif
                        @if(Auth::user()->hasRole(['Administrator','Verifier','Certifier']))
                        <li class="nav-item " id="bridgeTask"><a class="nav-link" href="{{route('bridge.view')}}?action=task"><span class="">Task</span></a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @if(Auth::user()->hasRole(['Administrator','Registrar']))
            <li class="nav-item">
                <span class="nav-link collapsed d-flex justify-content-between align-items-center" id="menu-inventory" data-toggle="collapse" data-target="#submenu-app-registration">
                    <span>
                        <span class="sidebar-icon"><span class="fas fa-warehouse"></span></span>
                        Registration
                    </span>
                    <span class="link-arrow"><span class="fas fa-chevron-right"></span></span>
                </span>
                <div class="multi-level collapse" role="list" id="submenu-app-registration" aria-expanded="false">
                    <ul class="flex-column nav">
                        <li class="nav-item " id="bridgeReg"><a class="nav-link" href="{{route('bridge.form')}}"><span class="">Bridge Registration</span></a></li>
                        <li class="nav-item " id="passageList"><a class="nav-link" href="{{route('road.list')}}"><span class="">Road Management</span></a></li>
                        @if(Auth::user()->hasRole('Administrator'))
                        <li class="nav-item " id="routeList"><a class="nav-link" href="{{route('route.list')}}"><span class="">Route Management</span></a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            <li class="nav-item">
                <span class="nav-link collapsed d-flex justify-content-between align-items-center" id="menu-inspection" data-toggle="collapse" data-target="#submenu-app-inspection">
                    <span>
                        <span class="sidebar-icon"><span class="fas fa-certificate"></span></span>
                        Inspection
                    </span>
                    <span class="link-arrow"><span class="fas fa-chevron-right"></span></span>
                </span>
                <div class="multi-level collapse" role="list" id="submenu-app-inspection" aria-expanded="false">
                    <ul class="flex-column nav">
                    @if(Auth::user()->hasRole(['Administrator','Inspector','Registrar','VIP']))
                    <li class="nav-item " id="bridgeList"><a class="nav-link" href="{{route('inspect.view')}}?action=list"><span class="">Bridge Listing</span></a></li>
                    @endif
                    @if(Auth::user()->hasRole(['Administrator','Inspector','Registrar']))
                    <li class="nav-item " id="inspectInbox"><a class="nav-link" href="{{route('inspect.view')}}?action=inbox"><span class="">Inbox</span></a></li>
                    @endif
                    @if(Auth::user()->hasRole(['Administrator','Certifier']))
                    <li class="nav-item " id="inspectTask"><a class="nav-link" href="{{route('inspect.view')}}?action=task"><span class="">Task</span></a></li>
                    @endif
                    </ul>
                </div>
            </li>
            @if(Auth::user()->hasRole(['Administrator']))
            <li class="nav-item">
                <span class="nav-link collapsed  d-flex justify-content-between align-items-center" id="menu-management" data-toggle="collapse" data-target="#submenu-app-management">
                    <span>
                        <span class="sidebar-icon"><span class="fas fa-cogs"></span></span>
                        Management
                    </span>
                    <span class="link-arrow"><span class="fas fa-chevron-right"></span></span>
                </span>
                <div class="multi-level collapse" role="list" id="submenu-app-management" aria-expanded="false">
                    <ul class="flex-column nav">

                        <li class="nav-item" id="userMgnt"><a class="nav-link" href="{{route('user.list')}}"><span class="">User Management</span></a></li>
                        <li class="nav-item" id="categoryMgnt"><a class="nav-link" href="{{route('category.list')}}"><span class="">Category Management</span></a></li>
                        <li class="nav-item" id="lookupMgnt"><a class="nav-link" href="{{route('lookup.list')}}"><span class="">Lookup Management</span></a></li>
                        <li class="nav-item" id="officeMgnt"><a class="nav-link" href="{{route('office.list')}}"><span class="">Office Management</span></a></li>
                    </ul>
                </div>
            </li>
            @endif
			<li class="nav-item">

				 <a href="{{asset('files/Bridge management system (BMS),user manual.pdf')}}" class="nav-link">
                    <span class="sidebar-icon"><span class="fas fa-file-alt"></span></span>
                    <span>User Manual</span>
                </a>
			</li>
        </ul>
    </div>
</nav>
