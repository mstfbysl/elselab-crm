{{-- For submenu --}}
<ul class="menu-content">
  @if(isset($menu))
  @foreach($menu as $submenu)
  <li @if($submenu->slug === Route::currentRouteName()) class="active permission-selector" @endif  style="display: none" class="permission-selector" data-permission-id="{{ $submenu->permission_id }}">
    <a href="{{isset($submenu->url) ? url($submenu->url):'javascript:void(0)'}}" class="d-flex align-items-center" target="{{isset($submenu->newTab) && $submenu->newTab === true  ? '_blank':'_self'}}">
      @if(isset($submenu->icon))
      <i class="{{ $submenu->icon }}"></i>
      @endif
      <span class="menu-item text-truncate">{{ __('menu.'.$submenu->name) }}</span>
    </a>
    @if (isset($submenu->submenu))
    @include('panels/submenu', ['menu' => $submenu->submenu])
    @endif
  </li>
  @endforeach
  @endif
</ul>
