<body class="skin-purple">
    <!-- header logo: style can be found in header.less -->
   <header class="header">
            <a class="logo" href="<?=base_url()?>">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                Admin
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav role="navigation" class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a role="button" data-toggle="offcanvas" class="navbar-btn sidebar-toggle" href="#">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?=$this->user_session['name']?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu" style="min-width: 200px;">
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a class="btn btn-default btn-flat" href="<?=base_url()."profile/edit"?>">Edit Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a class="btn btn-default btn-flat" href="<?=base_url()."index/logout"?>">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
