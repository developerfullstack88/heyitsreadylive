<header class="header white-bg">
    <div class="navbar-header">
      <div class="row">
        <div class="col-md-12 col-8">
          <div class="top-nav ">
              <!--search & user info start-->
              <ul class="nav justify-content-end top-menu">
                  <!-- user login dropdown start-->
                  <li class="dropdown language">
                      <a data-close-others="true" data-hover="dropdown" data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
                          <img src="{{getLangFlag()}}" alt="">
                          <span class="username">{{getLangName()}}</span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu" x-placement="bottom-start">

                          {!! getLangHtmlWithoutLogin() !!}
                      </ul>
                  </li>
              </ul>
          </div>
        </div>
      </div>

    </div>
  </header>
