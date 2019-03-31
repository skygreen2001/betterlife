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
          <ul class="nav navbar-nav navbar-right" style="display: none;">
            <li><a href="{$url_base}index.php?go=report.index.index">首页</a></li>
            <li><a href="{$url_base}index.php?go=report.reportUserCount.lists">月增用户数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportTripModelCount.lists">月增线路数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportTripCount.lists">月增活动数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEAccountCount.lists">月增企业数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportRechargeCount.lists">月充值数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportETripCount.lists">企办活动数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportMTripCount.lists">月办活动数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportMTripCountAvg.lists">月办活动平均人数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportMTeamAvgPeople.lists">月战队平均人数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEArtistCount.lists">企设计师数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportETripModelCount.lists">企设线路数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEMTripModelCount.lists">企月设线路数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEHasTripCount.lists">企建活动数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEMTripCount.lists">企月增活动数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEMBTripCount.lists">企月办活动数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportETripAvgPeople.lists">企业活动平均人数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportETeamAvgPeople.lists">企业战队平均人数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportERechargeCount.lists">企充值数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEConsumption.lists">企消耗数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportELeftCount.lists">企结余数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEMRechargeCount.lists">企月充值数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEMConsumption.lists">企月消耗数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEMLeftCount.lists">企月结余数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportECertifyTime.lists">企认证时间</a></li>
            <li><a href="{$url_base}index.php?go=report.reportERechargeTime.lists">企充值时间表</a></li>
            <li><a href="{$url_base}index.php?go=report.reportEBTripTime.lists">企办活动时间表</a></li>
            <li><a href="{$url_base}index.php?go=report.reportArtistTripModel.lists">设计师线路</a></li>
            <li><a href="{$url_base}index.php?go=report.reportArtistTMCount.lists">设计师线路数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportArtistMTMCount.lists">设计师月线路数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportTMByTCount.lists">线路被活动引用数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportArtistTMByTAvgCount.lists">设计师线被活引用平均数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportArtistTMByTMaxCount.lists">设计师线被活引用最大数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportArtistMTMByTAvgCount.lists">设计师月线被活引用平均数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportArtistMTMByTMaxCount.lists">设计师月线路被活动引用最大数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportTMByTrip.lists">被引用活动</a></li>
            <li><a href="{$url_base}index.php?go=report.reportTMByTHoldCount.lists">引用活动举办数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportTripConsumption.lists">已办活动冻结数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportTripReturnCount.lists">已办活动退还数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportTripSettleCount.lists">已办活动结算总数</a></li>
            <li><a href="{$url_base}index.php?go=report.reportMTripSettleCount.lists">月办活动结算总数</a></li>
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
