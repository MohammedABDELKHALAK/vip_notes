
<style>
    .sidebar{
        /* width: 250px;
        height: auto; /* Full height of the viewport 
        overflow-y: auto; Allow scrolling if content exceeds the height */
        
    }
    .sidebar a{
        /* background-color: rgb(161, 179, 31); */
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
        width: 250px;
        transition: width 0.8s ease;
    }

    .sidebar i {
        
        font-size:22px;
    }

    .sidebar p{
        /* background-color: blueviolet; */
         margin: 0; /*Removes margin around p */
        display: inline; /*Ensures it stays inline with icon*/
        
    }

     /* Hover Effect */
     .sidebar .nav-link:hover {
        background-color:rgba(23, 163, 184, 0.31); /* Change background color on hover */
        color: #fff; /* Keep text white */
    }
</style>

<!-- Sidebar -->
<div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="">
        <ul class="nav nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
            <li class="nav-item ">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="bi bi-speedometer2"></i>
                    <p>
                        {{ __('Dashboard') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{ __('Users') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('notes.index') }}" class="nav-link">
                    <i class="bi bi-journal-plus " ></i>
                    <p>
                        {{ __('Notes') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('expired.notes') }}" class="nav-link">
                    <i class="bi bi-ban " ></i>
                    <p>
                        {{ __('Expired Notes') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('recieved.notes') }}" class="nav-link">
                    <i class="bi bi-inbox" ></i>
                    <p>
                        {{ __('Recieved Notes') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('about') }}" class="nav-link">
                    <i class="bi bi-info-circle"></i>
                    <p>
                        {{ __('About us') }}
                    </p>
                </a>
            </li>

            {{-- <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-solid fa-book "></i>
            
                    <p>
                        Pages
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-journal-plus pr-1 ml-3"></i>
                            
                             Exemple Page 
                        </a>
                    </li>
                </ul>
            </li> --}}

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->