<div id="sidebar" class="sidebar responsive sidebar-fixed">
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <button class="btn btn-success">
                <i class="ace-icon fa fa-signal"></i>
            </button>
            <button class="btn btn-info">
                <i class="ace-icon fa fa-pencil"></i>
            </button>
            <button class="btn btn-warning">
                <i class="ace-icon fa fa-users"></i>
            </button>
            <button class="btn btn-danger">
                <i class="ace-icon fa fa-cogs"></i>
            </button>
        </div>
        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>
            <span class="btn btn-info"></span>
            <span class="btn btn-warning"></span>
            <span class="btn btn-danger"></span>
        </div>
    </div>

    <ul class="nav nav-list">

        <li class="hover">
            <a href="/">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>

            <b class="arrow"></b>
        </li>
        <li class="hover <?php //echo in_array(Yii::$app->request->pathInfo,['supplier','packaging-post','stock-location'])?'active':''; ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon ace-icon fa fa-cogs"></i>
                <span class="menu-text">
                    <?php echo Yii::t('app/app', 'Basic information') ?>
                </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>

            <ul class="submenu">
                <li class="hover">
                    <a href="/supplier">
                         <i class="menu-icon fa fa-caret-right"></i>
                            <?php echo Yii::t('app/app', 'Suppliers') ?>
                    </a>
                </li>
                <li class="hover">
                    <a href="/stock-location">
                         <i class="menu-icon fa fa-caret-right"></i>
                            <?= Yii::t('app/app', 'Product Locations'); ?>
                    </a>
                </li>
                <li class="hover">
                    <a href="/packaging-post">
                         <i class="menu-icon fa fa-caret-right"></i>
                            <?= Yii::t('app/app', 'Packaging and Post'); ?>
                    </a>
                </li>

            </ul>

        </li>
        <li class="hover <?php //echo in_array(Yii::$app->request->pathInfo,['product','category','product/create','product/view','product/update'])?'active':''; ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon ace-icon fa fa-cubes"></i>
                <span class="menu-text">
                    <?php echo Yii::t('app/product', 'product') ?>
                </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>

            <ul class="submenu">
                <li class="hover">
                    <a href="/product/create">
                         <i class="menu-icon fa fa-caret-right"></i>
                            <?= Yii::t('app/product', 'Create Product'); ?>
                    </a>
                </li>
                <li class="hover">
                    <a href="/product">
                         <i class="menu-icon fa fa-caret-right"></i>
                            <?php echo Yii::t('app/product', 'product list') ?>
                    </a>
                </li>
                <li class="hover">
                    <a href="/category">
                         <i class="menu-icon fa fa-caret-right"></i>
                            <?= Yii::t('app/category', 'Categories'); ?>
                    </a>
                </li>


            </ul>

        </li>
        <li class="hover">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon ace-icon fa fa-user-secret"></i>
                <span class="menu-text">
                    <?php echo Yii::t('app/ebayaccount', 'Ebay Accounts') ?>
                </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>

            <ul class="submenu">
                <li class="hover">
                    <a href="/ebay-account">
                         <i class="menu-icon fa fa-caret-right"></i>
                            Account List
                    </a>
                </li>
                <li class="hover">
                    <a href="/listing-template">
                         <i class="menu-icon fa fa-caret-right"></i>
                            Listing Template
                    </a>
                </li>

            </ul>
        </li>
        <li class="hover">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon ace-icon fa fa-list"></i>
                <span class="menu-text">
                    <?php echo Yii::t('app/app', 'Listings') ?>
                </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>

            <ul class="submenu">
                <li class="hover">
                    <a href="/listing">
                         <i class="menu-icon fa fa-caret-right"></i>
                            <?php echo Yii::t('app/app', 'Active Listings', []); ?>
                    </a>
                </li>
                <li class="hover">
                    <a href="/listing/sync">
                         <i class="menu-icon fa fa-caret-right"></i>
                            <?php echo Yii::t('app/app', 'Sync Listings', []); ?>
                    </a>
                </li>

            </ul>
        </li>
      <li class="hover">
          <a href="#" class="dropdown-toggle">
              <i class="menu-icon ace-icon fa fa-money"></i>
              <span class="menu-text">
                  <?php echo Yii::t('app/app', 'Orders') ?>
              </span>
              <b class="arrow fa fa-angle-down"></b>
          </a>
          <b class="arrow"></b>

          <ul class="submenu">
              <li class="hover">
                  <a href="/order/fetch-index">
                       <i class="menu-icon fa fa-caret-right"></i>
                          <?php echo Yii::t('app/app', 'Get Orders', []); ?>
                  </a>
              </li>
              <li class="hover">
                  <a href="/order/">
                       <i class="menu-icon fa fa-caret-right"></i>
                          <?php echo Yii::t('app/app', 'Ebay Orders', []); ?>
                  </a>
              </li>
          </ul>
      </li>
      </ul>

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>
