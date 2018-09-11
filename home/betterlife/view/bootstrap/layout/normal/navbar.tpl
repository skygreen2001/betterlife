  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle  collapsed" id="btn-toggle-navbar" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <button type="button" class="navbar-toggle collapsed" id="btn-toggle-sidebar" data-toggle="collapse" data-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
          <span class="sr-only"></span>
          <i class="glyphicon glyphicon-briefcase"></i>
        </button>
        <button type="button" class="navbar-toggle collapsed" id="btn-toggle-searchbar" data-toggle="collapse" data-target="#searchbar" aria-expanded="false" aria-controls="searchbar">
          <span class="sr-only"></span>
          <i class="fa fa-search"></i>
        </button>
        <a class="navbar-brand" href="{$url_base}index.php?go={$appName}.index.index">
          <i class="glyphicon glyphicon-grain"></i> {$site_name}
        </a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="{$url_base}index.php?go={$appName}.index.index">首页</a></li>
          <li><a href="{$url_base}index.php?go={$appName}.blog.index">博客</a></li>
          <li><a href="{$url_base}index.php?go={$appName}.featured.index">发现</a></li>
          <li><a href="{$url_base}index.php?go={$appName}.us.index">我们</a></li>
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
            <input id="t-search" type="search" class="form-control" name="search" autocomplete="off" autofocus="autofocus" placeholder="搜你所想">
            <i id="searchbar-close" class="fa fa-remove search-toggle"></i>
          </form>
        </div>
      </div>
    </div>
  </nav>
