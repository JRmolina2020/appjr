<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" <li class="nav-item">
    <li>
      <a href="{{ url('bills') }}" class="nav-link">
        <i class="fi fi-credit-card"></i>
        <p>Gastos</p>
      </a>
    </li>
    <li>
      <a href="{{ url('investments') }}" class="nav-link">
        <i class="fi fi-credit-card"></i>
        <p>Inversión</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ url('perfil') }}" class="nav-link">
        <i class="fi fi-user-secret"></i>
        <p>Perfil</p>
      </a>
    </li>

    @hasrole('administrador')
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="fi fi-world-o"></i>
        <p>
          Seguridad
          <i class="right fi fi-more-v-a"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('usuarios') }}" class="nav-link">
            <i class="fi fi-male"></i>
            <p>
              Usuarios
            </p>
          </a>
        </li>
      </ul>

      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('roles') }}" class="nav-link">
            <i class="fi fi-persons"></i>
            <p>
              Roles
            </p>
          </a>
        </li>
      </ul>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('permisos') }}" class="nav-link">
            <i class="fi fi-preview"></i>
            <p>
              Permisos
            </p>
          </a>
        </li>
      </ul>
    </li>
    @endrole

    <li class="nav-item has-treeview">
      <a class="nav-link" onclick="event.preventDefault(); 
          document.getElementById('logout-form').submit();" href="{{ route('logout') }}">
        <i class="fi fi-frowning"></i>
        <p>Salir</p>
      </a>
    </li>
    <form id="logout-form" action="{{route('logout')}}" method="POST" style="display:none;">
      {{ csrf_field() }}
    </form>
  </ul>
</nav>