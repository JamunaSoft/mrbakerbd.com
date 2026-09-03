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
              @php $users_route = App\Models\UserPrivilege::where('user_id', Auth::user()->id)->where('route_name', 'admin.users.index')->first(); @endphp
              @if(!is_null($users_route))
              <li class="nav-item">
                <a class="nav-link @if(Route::is('admin.users.index')) {{ __('active') }} @elseif(Route::is('admin.user.create')) {{ __('active') }} @elseif(Route::is('admin.user.edit')) {{ __('active') }} @endif" href="{{ route('admin.users.index') }}">
                  <i class="material-icons">supervisor_account</i>
                  <span>Users</span>
                </a>
              </li>
              @endif
              @php $pages_route = App\Models\UserPrivilege::where('user_id', Auth::user()->id)->where('route_name', 'admin.pages.index')->first(); @endphp
              @if(!is_null($pages_route))
              <li class="nav-item">
                <a class="nav-link @if(Route::is('admin.pages.index')) {{ __('active') }} @elseif(Route::is('admin.page.create')) {{ __('active') }} @elseif(Route::is('admin.page.edit')) {{ __('active') }} @endif" href="{{ route('admin.pages.index') }}">
                  <i class="material-icons">pages</i>
                  <span>Pages</span>
                </a>
              </li>
              @endif
              @php $categories_route = App\Models\UserPrivilege::where('user_id', Auth::user()->id)->where('route_name', 'admin.categories.index')->first(); @endphp
              @if(!is_null($categories_route))
              <li class="nav-item">
                <a class="nav-link @if(Route::is('admin.categories.index')) {{ __('active') }} @elseif(Route::is('admin.category.create')) {{ __('active') }} @elseif(Route::is('admin.category.edit')) {{ __('active') }} @endif" href="{{ route('admin.categories.index') }}">
                  <i class="material-icons">category</i>
                  <span>Categories</span>
                </a>
              </li>
              @endif
              @php $slides_route = App\Models\UserPrivilege::where('user_id', Auth::user()->id)->where('route_name', 'admin.slides.index')->first(); @endphp
              @if(!is_null($slides_route))
              <li class="nav-item">
                <a class="nav-link @if(Route::is('admin.slides.index')) {{ __('active') }} @elseif(Route::is('admin.slide.create')) {{ __('active') }} @elseif(Route::is('admin.slide.edit')) {{ __('active') }} @endif" href="{{ route('admin.slides.index') }}">
                  <i class="material-icons">flip</i>
                  <span>Slides</span>
                </a>
              </li>
              @endif
              @php $products_route = App\Models\UserPrivilege::where('user_id', Auth::user()->id)->where('route_name', 'admin.products.index')->first(); @endphp
              @if(!is_null($products_route))
              <li class="nav-item">
                <a class="nav-link @if(Route::is('admin.products.index')) {{ __('active') }} @elseif(Route::is('admin.product.create')) {{ __('active') }} @elseif(Route::is('admin.product.edit')) {{ __('active') }} @endif" href="{{ route('admin.products.index') }}">
                  <i class="material-icons">layers</i>
                  <span>Products</span>
                </a>
              </li>
              @endif
              @php $customers_route = App\Models\UserPrivilege::where('user_id', Auth::user()->id)->where('route_name', 'admin.customers.index')->first(); @endphp
              @if(!is_null($customers_route))
              <li class="nav-item">
                <a class="nav-link @if(Route::is('admin.customers.index')) {{ __('active') }} @elseif(Route::is('admin.customer.create')) {{ __('active') }} @elseif(Route::is('admin.customer.edit')) {{ __('active') }} @endif" href="{{ route('admin.customers.index') }}">
                  <i class="material-icons">person</i>
                  <span>Customers</span>
                </a>
              </li>
              @endif
              @php $orders_route = App\Models\UserPrivilege::where('user_id', Auth::user()->id)->where('route_name', 'admin.orders.index')->first(); @endphp
              @if(!is_null($orders_route))
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
