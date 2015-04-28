<ol class="breadcrumb">
    <li><a href="<?=base_url()."dashboard"?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_url().$this->router->fetch_class()?>"><?=$this->router->fetch_class()?></a></li>
    <li class="active"><?=$this->router->fetch_method()?></li>
</ol>