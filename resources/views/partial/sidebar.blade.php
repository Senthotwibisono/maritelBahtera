<ul class="menu">
    <li class="sidebar-title">Menu</li>
   
    <li class="sidebar-item @if(Request::is('home') || Request::is('/')) active @endif">
        <a href="/" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>

     <li class="sidebar-item @if(Request::is('invoice/*') || Request::is('/invoice/*')) active @endif">
        <a href="{{route('invoice.index')}}" class='sidebar-link'>
            <i class="fas fa-dollar"></i>
            <span>Penawaran</span>
        </a>
    </li>

     <li class="sidebar-item @if(Request::is('voyage/*') || Request::is('/voyage/*')) active @endif">
        <a href="{{route('voyage.index')}}" class='sidebar-link'>
            <i class="fas fa-ship"></i>
            <span>Ves Schedule</span>
        </a>
    </li>

    <li class="sidebar-item  has-sub @if(Request::is('master/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-collection-fill"></i>
            <span>Master</span>
        </a>
        <ul class="submenu @if(Request::is('master/*')) active @endif">
            <li class="submenu-item @if(Request::is('master/country/*')) active @endif">
                <a href="{{route('master.country.index')}}">Master Country</a>
            </li>
            <li class="submenu-item @if(Request::is('master/item/*')) active @endif">
                <a href="{{route('master.item.index')}}">Master Item</a>
            </li>
            <li class="submenu-item @if(Request::is('master/layout/*')) active @endif">
                <a href="{{route('master.layout.index')}}">Master Lay Out</a>
            </li>
            <li class="submenu-item @if(Request::is('master/port/*')) active @endif">
                <a href="{{route('master.port.index')}}">Master Port</a>
            </li>
            <li class="submenu-item @if(Request::is('master/vessel/*')) active @endif">
                <a href="{{route('master.vessel.index')}}">Master Vessel</a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item @if(Request::is('userSystem/*') || Request::is('/userSystem/*')) active @endif">
        <a href="{{route('user.index')}}" class='sidebar-link'>
            <i class="fas fa-user"></i>
            <span>User Management</span>
        </a>
    </li>
 
</ul> 