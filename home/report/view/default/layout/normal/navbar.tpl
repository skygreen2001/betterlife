    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle  collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only"></span>
            <i class="glyphicon glyphicon-briefcase"></i>
          </button>
          <button id="btn-toggle-sidebar" type="button" class="navbar-toggle collapsed">
              <span class="sr-only"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{$url_base}index.php?go=admin.index.index">
            <i class="glyphicon glyphicon-grain"></i> {$site_name}
          </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="{$url_base}index.php?go=report.index.index">首页</a></li>
            <li class="dropdown">
              <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-user"></span>
                <span class="username">报表管理</span>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="dLabel">
                <li><a href="{$url_base}index.php?go=report.reportone.index&rtype=userReport"><span class="glyphicon glyphicon-edit"></span>用户统计</a></li>
                <li><a href="{$url_base}index.php?go=report.reportone.index&rtype=blogReport"><span class="glyphicon glyphicon-edit"></span>博客统计</a></li>
              </ul>
            </li>

            <li id="searchbar-li" class="search-toggle collapsed" data-toggle="collapse" data-target="#searchbar" aria-expanded="false" aria-controls="searchbar">
              <a>
                <span><span class="menu-search-text">搜索</span><span class="fa fa-search" aria-hidden="true"></span></span>
              </a>
            </li>
            <li><a id="btn-layout-small"><i class="glyphicon glyphicon-resize-small"></i></a></li>
          </ul>
        </div>
        <div id="searchbar" class="collapse">
          <div id="searchbar-inner">
            <form method="get" action="" class="searchbar-form">
              <input type="search" class="form-control" name="search" autocomplete="off" autofocus="autofocus" placeholder="搜你所想">
              <i id="searchbar-close" class="fa fa-remove search-toggle"></i>
            </form>
          </div>
        </div>
      </div>
    </nav>
