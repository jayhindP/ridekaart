<?php
$auth_type = $this->session->userdata('auth_type');
$auth_id = $this->session->userdata('auth_id');
?>
<script>
$(document).ready(function()
{
    var actv_pge = "<?php echo $this->uri->segment(2); ?>";
    $("#full_loader").hide();
});

</script>
<!-- START PAGE CONTAINER -->
<div class="page-container">
	<!-- START PAGE SIDEBAR -->
	<div class="page-sidebar">
		<!-- START X-NAVIGATION -->
		<ul class="x-navigation">
			<li class="xn-logo">
				<a href="<?php echo site_url('site/dashboard'); ?>" style="font-size: 16px;"> <?=$_SESSION['company']?></a>
				<a href="#" class="x-navigation-control"></a>
			</li>
			<li class="xn-profile">
				<a href="#" class="profile-mini">
					<img src="<?=$this->session->userdata('auth_image')?>" alt="<?=$_SESSION['company']?>" style="height:80px;"/>
				</a>
				<div class="profile">
					<div class="profile-image">
					    <img src="<?=$this->session->userdata('auth_image')?>" alt="<?=$_SESSION['company']?>" style="border:none;"/>
					</div>
				</div>
			</li>
			
			<!--For Dashboard-->
			<li id="dashboard">
				<a href="<?php echo site_url('site/dashboard'); ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>
			</li>

			<li class="xn-openable">
				<a href="#"><span class="fa fa-shopping-cart"></span> <span class="xn-text">Order Details</span></a>
				<ul>
					<li><a href="#"><span class="fa fa-plus"></span> New Orders</a></li>
					<li><a href="#"><span class="fa fa-thumbs-up"></span> Accepted Orders</a></li>
					<li><a href="#"><span class="fa fa-check"></span> Delivered Orders</a></li>
					<li><a href="#"><span class="fa fa-times"></span> Rejected Orders</a></li>
				</ul>
			</li>

			<li class="xn-openable">
				<a href="#"><span class="fa fa-users"></span> <span class="xn-text">Admin Users</span></a>
				<ul>
					<li><a href="#"><span class="fa fa-plus"></span> Add User</a></li>
					<li><a href="#"><span class="fa fa-thumbs-up"></span> Add Roles</a></li>
					<li><a href="#"><span class="fa fa-list"></span> Admin Users List</a></li>
				</ul>
			</li>

			<li class="xn-openable">
				<a href="#"><span class="fa fa-car"></span> <span class="xn-text">Drivers</span></a>
				<ul>
					<li><a href="#"><span class="fa fa-plus"></span> Add Driver</a></li>
					<li><a href="#"><span class="fa fa-usd"></span> Add Driver Commission</a></li>
					<li><a href="#"><span class="fa fa-list"></span> Drivers List</a></li>
				</ul>
			</li>

			<li class="xn-openable">
				<a href="#"><span class="fa fa-shopping-cart"></span> <span class="xn-text">Inventory</span></a>
				<ul>
					<li><a href="#"><span class="fa fa-sitemap"></span> Add Product Categories</a></li>
					<li><a href="#"><span class="fa fa-university"></span> Add Product Brands</a></li>
					<li><a href="#"><span class="fa fa-cube"></span> Add Product Sizes</a></li>
					<li><a href="#"><span class="fa fa-plus"></span> Add Products</a></li>
					<li><a href="#"><span class="fa fa-list"></span> All Products</a></li>
				</ul>
			</li>

			<li class="xn-openable">
				<a href="#"><span class="fa fa-cubes"></span> <span class="xn-text">Boxes</span></a>
				<ul>
					<li><a href="#"><span class="fa fa-plus"></span> Add New Boxe</a></li>
					<li><a href="#"><span class="fa fa-image"></span> Add New Banner</a></li>
					<li><a href="#"><span class="fa fa-list"></span> All Boxes</a></li>
				</ul>
			</li>

			<li class="xn-openable">
				<a href="#"><span class="fa fa-gift"></span> <span class="xn-text">Promo Codes</span></a>
				<ul>
					<li><a href="#"><span class="fa fa-plus"></span> Add New Promocode</a></li>
					<li><a href="#"><span class="fa fa-list"></span> All Promocodes</a></li>
				</ul>
			</li>

			<li class="xn-openable">
				<a href="#"><span class="fa fa-comments-o"></span> <span class="xn-text">Notifications</span></a>
				<ul>
					<li><a href="#"><span class="fa fa-plus"></span> Push Notifications</a></li>
					<li><a href="#"><span class="fa fa-envelope"></span> Send Emails</a></li>
				</ul>
			</li>


			<!-- <li id="add_user">
				<a href="<?php echo site_url('site/user'); ?>"><span class="fa fa-users"></span> <span class="xn-text">Contact List</span></a>
			</li>
			<li id="location">
				<a href="<?php echo site_url('site/location'); ?>"><span class="fa fa-map-marker"></span> <span class="xn-text">Locations</span></a>
			</li>
			<li id="category">
				<a href="<?php echo site_url('site/category'); ?>"><span class="fa fa-sitemap"></span> <span class="xn-text">Sector</span></a>
			</li>
			<li id="add_banner" style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>">
				<a href="<?php echo site_url('site/banner'); ?>"><span class="fa fa-image"></span> <span class="xn-text">Banners</span></a>
			</li>
			<li id="add_admin">
				<a href="<?php echo site_url('site/admin'); ?>"><span class="fa fa-lock"></span> <span class="xn-text">Sub-admins</span></a>
			</li>
			<li id="user_activity" style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>">
				<a href="<?php echo site_url('site/user_activity'); ?>"><span class="fa fa-clock-o"></span> <span class="xn-text">Sub-Admin's Activity</span></a>
			</li> -->
			<!--<li id="setting">-->
			<!--	<a href="<?php echo site_url('site/setting'); ?>"><span class="fa fa-cogs"></span> <span class="xn-text">Setting</span></a>-->
			<!--</li>-->
			
			<!--<li id="pages">-->
			<!--	<a href="<?php echo site_url('site/pages'); ?>"><span class="fa fa-file-o"></span> <span class="xn-text">Pages</span></a>-->
			<!--</li>-->
			
			<!--<li id="sub_category">-->
			<!--	<a href="<?php echo site_url('site/sub_category'); ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Sub Categories</span></a>-->
			<!--</li>-->
			<!--<li id="notification">-->
			<!--	<a href="<?php echo site_url('site/notification'); ?>"><span class="fa fa-comments-o"></span> <span class="xn-text">Notifications</span></a>-->
			<!--</li>-->
			<!--<li id="contact_us">-->
			<!--	<a href="<?php echo site_url('site/contact_us'); ?>"><span class="fa fa-headphones"></span> <span class="xn-text">Customer Service</span></a>-->
			<!--</li>-->
		</ul>
		<!-- END X-NAVIGATION -->
	</div>
	<!-- END PAGE SIDEBAR -->
	
	<!-- PAGE CONTENT -->
	<div class="page-content">
		<!-- START X-NAVIGATION VERTICAL -->
		<ul class="x-navigation x-navigation-horizontal x-navigation-panel">
			<!-- TOGGLE NAVIGATION -->
			<li class="xn-icon-button">
				<a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
			</li>
			<!-- END TOGGLE NAVIGATION -->
			
			<!-- SEARCH -->
			<li class="xn-search">
				<form role="form">
					<input type="text" id="search_bar" name="search_bar" onkeyup="get_search_data(this.value);" placeholder="Search  for  pages . . ."/>
					<div id="search_div">
						<ul id="list" style="background: rgb(31, 31, 31); display: block;">
						</ul>
					</div>
				</form>
			</li>
			<!-- END SEARCH -->
			
			
			<!-- SIGN OUT -->
			<li class="xn-icon-button pull-right">
				<a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>
			</li>

			<li class="pull-right">
				<a href="<?=site_url('site/change_password')?>"><span class="fa fa-user"></span><?=$this->session->userdata('auth_name')?> </a>
			</li>

		
			<!-- END SIGN OUT -->
			<!-- NOTIFICATION -->
			<!--onclick="get_notification('');"-->
            <li class="xn-icon-button pull-right" style="display:none;" id="notify_icon">
                <a href="#"><span class="fa fa-bell"></span></a>
                <div class="informer informer-danger" id="notify_count"></div>
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="fa fa-bell"></span> Notification</h3>                                
                        <div class="pull-right">
                            <span class="label label-danger" id="notify_count2">4 New</span>
                        </div>
                    </div>
                    <div class="panel-body list-group list-group-contacts scroll"  style="height: 291px;">
                        <div id="notify_li" >
                            
                        </div>
                    </div>     
                    <div class="panel-footer text-center">
                        <a href="#">Show all Notification</a>
                    </div>                            
                </div>                        
            </li>
            <!-- END NOTIFICATION -->
		</ul>
		<!-- END X-NAVIGATION VERTICAL -->
		<div id="header_loader_div" class="panel-refresh-layer" style="width:1109px;height:800px;display:none;"><img src="<?php echo base_url(); ?>/img/loaders/default.gif"></div>