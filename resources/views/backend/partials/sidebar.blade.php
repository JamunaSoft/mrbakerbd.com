        <aside class="main-sidebar col-10 col-md-3 col-lg-2 px-0">
          <div class="main-navbar">
            <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
              <a class="navbar-brand w-100 mr-0" href="{{ route('admin.dashboard') }}" style="line-height: 25px;">
                <div class="d-table m-auto">
                  <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 25px;" src="{{ asset('images/'. Cache::get('settings')->logo) }}" alt="Logo">
                  <span class="d-none d-md-inline ml-1">{{ Cache::get('settings')->name }}</span>
                </div>
              </a>
              <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
              </a>
            </nav>
          </div>
          <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
            <div class="input-group input-group-seamless ml-3">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-search"></i>
                </div>
              </div>
              <input class="navbar-search form-control" type="text" placeholder="Search..." aria-label="Search">
            </div>
          </form>
          <div class="nav-wrapper">
            <h6 class="main-sidebar__nav-title">Overview</h6>
            <ul class="nav nav--no-borders flex-column">
              <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                  <i class="material-icons">dashboard</i>
                  <span>Dashboard</span>
                </a>
              </li>
            </ul>
            <h6 class="main-sidebar__nav-title">Management</h6>
            <ul class="nav nav--no-borders flex-column">

                @php
                    $user = auth()->user();
                @endphp

                @if($user && $user->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('admin.users.index')) {{ __('active') }} @elseif(Route::is('admin.user.create')) {{ __('active') }} @elseif(Route::is('admin.user.edit')) {{ __('active') }} @endif" href="{{ route('admin.users.index') }}">
                            <i class="material-icons">supervisor_account</i>
                            <span>Users</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('admin.pages.index')) {{ __('active') }} @elseif(Route::is('admin.page.create')) {{ __('active') }} @elseif(Route::is('admin.page.edit')) {{ __('active') }} @endif" href="{{ route('admin.pages.index') }}">
                            <i class="material-icons">pages</i>
                            <span>Pages</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('admin.categories.index')) {{ __('active') }} @elseif(Route::is('admin.category.create')) {{ __('active') }} @elseif(Route::is('admin.category.edit')) {{ __('active') }} @endif" href="{{ route('admin.categories.index') }}">
                            <i class="material-icons">category</i>
                            <span>Categories</span>
                        </a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="{{ route('admin.flavour.index') }}">--}}
{{--                            <i class="material-icons">local_cafe</i>--}}
{{--                            <span>Flavours</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('admin.slides.index')) {{ __('active') }} @elseif(Route::is('admin.slide.create')) {{ __('active') }} @elseif(Route::is('admin.slide.edit')) {{ __('active') }} @endif" href="{{ route('admin.slides.index') }}">
                            <i class="material-icons">flip</i>
                            <span>Slides</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('admin.products.index')) {{ __('active') }} @elseif(Route::is('admin.product.create')) {{ __('active') }} @elseif(Route::is('admin.product.edit')) {{ __('active') }} @endif" href="{{ route('admin.products.index') }}">
                            <i class="material-icons">layers</i>
                            <span>Products</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('admin.customers.index')) {{ __('active') }} @elseif(Route::is('admin.customer.create')) {{ __('active') }} @elseif(Route::is('admin.customer.edit')) {{ __('active') }} @endif" href="{{ route('admin.customers.index') }}">
                            <i class="material-icons">person</i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.contact.index') }}">
                            <i class="material-icons">contact_mail</i>
                            <span>Contacts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.feedback.index') }}">
                            <i class="material-icons">feedback</i>
                            <span>Feedbacks</span>
                        </a>
                    </li>
                @endif
                @if($user && ($user->hasRole('Admin') || $user->hasRole('Manager')))
                                    <li class="nav-item">
                                        <a class="nav-link @if(Route::is('admin.orders.index')) {{ __('active') }} @elseif(Route::is('admin.order.create')) {{ __('active') }} @elseif(Route::is('admin.order.edit')) {{ __('active') }} @endif" href="{{ route('admin.orders.index') }}">
                                            <i class="material-icons">view_list</i>
                                            <span>Orders</span>
                                        </a>
                                    </li>
                @endif

            </ul>
          </div>
        </aside>
