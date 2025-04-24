<div class="page-main-header">
  <div class="main-header-right row m-0">
    <div class="main-header-left">
      <div class="logo-wrapper"><a href="{{ route('index') }}"><img class="img-fluid" src="{{asset('assets/images/logo/logo.png')}}" alt=""></a></div>
      <div class="dark-logo-wrapper"><a href="{{ route('index') }}"><img class="img-fluid" src="{{asset('assets/images/logo/dark-logo.png')}}" alt=""></a></div>
      <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle">    </i></div>
    </div>

    <div class="nav-right col pull-right right-menu p-0">
      <ul class="nav-menus">
        {{-- <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>

        <li> --}}
            <div class="mode"><i class="fa fa-moon-o"></i></div>
        </li>
        <li>

            <a href="{{ route('panier.index') }}">
              <div class="cart-container" style="position: relative;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cart-icon">
                  <circle cx="9" cy="21" r="1"></circle>
                  <circle cx="20" cy="21" r="1"></circle>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
              </div>
            </a>
          </li>

        <li class="onhover-dropdown p-0">
          <a class="btn btn-primary-light" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
        </li>
      </ul>
    </div>
    <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
  </div>
</div>
<!-- Placez ce script dans la section head de votre document -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
      // Récupérer la valeur stockée dès que possible
      const storedCount = parseInt(localStorage.getItem('cartCount') || '0');
      const badge = document.querySelector('.cart-badge');

      if (badge) {
        if (storedCount > 0) {
          badge.textContent = storedCount;
          badge.style.display = 'block';
        } else {
          badge.style.display = 'none';
        }
      }
    });
  </script>
  @push('styles')
  <style>
    /* Ce sélecteur est plus spécifique que celui qui définit la couleur rouge */
    .custom-blue-badge {
    background-color: #3b82f6; /* ou toute autre nuance de bleu de votre choix */
    /* Les autres propriétés sont déjà définies dans votre style inline */
  }
  </style>
  @endpush

