<nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
         
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
       

            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-chat-text"></i>
                <span class="navbar-badge badge text-bg-danger">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="{{asset('dist//assets/img/user1-128x128.jpg')}}"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-end fs-7 text-danger"
                          ><i class="bi bi-star-fill"></i
                        ></span>
                      </h3>
                      <p class="fs-7">Call me whenever you can...</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="{{asset('dist//assets/img/user8-128x128.jpg')}}"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        John Pierce
                        <span class="float-end fs-7 text-secondary">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">I got your message bro</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="{{asset('dist//assets/img/user3-128x128.jpg')}}"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Nora Silvester
                        <span class="float-end fs-7 text-warning">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">The subject goes here</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
            </li>
            <!--end::Messages Dropdown Menu-->

            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-envelope me-2"></i> 4 new messages
                  <span class="float-end text-secondary fs-7">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-people-fill me-2"></i> 8 friend requests
                  <span class="float-end text-secondary fs-7">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                  <span class="float-end text-secondary fs-7">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
              </div>
            </li>
            <!--end::Notifications Dropdown Menu-->

      


          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>


    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

      {{-- Brand --}}
      <div class="sidebar-brand">
        <a href="#" class="brand-link">
          <img src="{{ asset('dist/assets/img/AdminLTELogo.png') }}"
              class="brand-image opacity-75 shadow"
              alt="Logo">
          <span class="brand-text fw-light">School System</span>
        </a>
      </div>

      {{-- User Panel --}}
      <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
        <div class="image ps-3">
          <img src="{{ asset('dist/assets/img/user3-128x128.jpg') }}"
              class="img-circle elevation-2"
              style="width:40px;height:40px;object-fit:cover;">
        </div>
        <div class="info ps-3">
          <a href="#" class="d-block text-white">
            {{ Auth::user()->name }}
          </a>
        </div>
      </div>

      {{-- Sidebar Menu --}}
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview">

            {{-- Admin --}}
              @if(Auth::user()->user_type == 1)
                 <li class="nav-item">
                        <a href="{{ url('admin/dashboard') }}"
                          class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/admin/list') }}"
                          class="nav-link {{ request()->is('admin/admin*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person"></i>
                            <p>Admin</p>
                        </a>
                    </li>
                      </li>
                         <li class="nav-item">
                        <a href="{{ url('admin/teacher/list') }}"
                          class="nav-link {{ request()->is('admin/teacher*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person"></i>
                            <p>Teacher</p>
                        </a>
                    </li>
                      <li class="nav-item">
                        <a href="{{ url('admin/student/list') }}"
                          class="nav-link {{ request()->is('admin/student*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person"></i>
                            <p>Student</p>
                        </a>
                    </li>
                         <li class="nav-item">
                        <a href="{{ url('admin/parent/list') }}"
                          class="nav-link {{ request()->is('admin/parent*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person"></i>
                            <p>Parent</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/class/list') }}"
                          class="nav-link {{ request()->is('admin/class*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Class</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/subject/list') }}"
                          class="nav-link {{ request()->is('admin/subject*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book"></i>
                            <p>Subject</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/assign_subject/list') }}"
                          class="nav-link {{ request()->is('admin/assign_subject*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book"></i>
                            <p>Assign Subject</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/profile/change_password') }}"
                          class="nav-link {{ request()->is('admin/change_password*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book"></i>
                            <p>Change Password</p>
                        </a>
                    </li>
                @endif


            {{-- Teacher --}}
            @if(Auth::user()->user_type == 2)
               <li class="nav-item">
                        <a href="{{ url('teacher/dashboard') }}"
                          class="nav-link {{ request()->is('teacher/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
              <li class="nav-item">
                        <a href="{{ url('teacher/profile/change_password') }}"
                          class="nav-link {{ request()->is('admin/change_password*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book"></i>
                            <p>Change Password</p>
                        </a>
                    </li>
            @endif

            {{-- Student --}}
            @if(Auth::user()->user_type == 3)
                 <li class="nav-item">
                        <a href="{{ url('student/dashboard') }}"
                          class="nav-link {{ request()->is('student/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
              <li class="nav-item">
                        <a href="{{ url('student/profile/change_password') }}"
                          class="nav-link {{ request()->is('student/change_password*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book"></i>
                            <p>Change Password</p>
                        </a>
                    </li>
            @endif

            {{-- Parent --}}
            @if(Auth::user()->user_type == 4)
                 <li class="nav-item">
                        <a href="{{ url('parent/dashboard') }}"
                          class="nav-link {{ request()->is('parent/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
              <li class="nav-item">
                        <a href="{{ url('parent/profile/change_password') }}"
                          class="nav-link {{ request()->is('parent/change_password*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book"></i>
                            <p>Change Password</p>
                        </a>
                    </li>
            @endif

            {{-- Accountant --}}
            @if(Auth::user()->user_type == 5)
                 <li class="nav-item">
                        <a href="{{ url('accountant/dashboard') }}"
                          class="nav-link {{ request()->is('accountant/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
              <li class="nav-item">
                        <a href="{{ url('accountant/profile/change_password') }}"
                          class="nav-link {{ request()->is('accountant/change_password*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book"></i>
                            <p>Change Password</p>
                        </a>
                    </li>
            @endif

            {{-- Logout --}}
            <li class="nav-item mt-3">
              <a href="{{ route('logout') }}"
                class="nav-link text-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon bi bi-box-arrow-right"></i>
                <p>Logout</p>
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
              </form>
            </li>

          </ul>
        </nav>
      </div>
    </aside>
