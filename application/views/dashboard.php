<script>
	var actv_pg = "<?php echo $this->uri->segment(2); ?>";
	$("#"+actv_pg).addClass('active');
	
$(document).ready(function (){
    Morris.Donut({
        element: 'dashboard-donut-1',
        data: [
            {label: "Returned", value: 2513},
            {label: "New", value: 764},
            {label: "Registred", value: 311}
        ],
        colors: ['#33414E', '#1caf9a', '#FEA223'],
        resize: true
    });

    Morris.Line({
      element: 'dashboard-line-1',
      data: [
        { y: '2014-10-10', a: 2,b: 4},
        { y: '2014-10-11', a: 4,b: 6},
        { y: '2014-10-12', a: 7,b: 10},
        { y: '2014-10-13', a: 5,b: 7},
        { y: '2014-10-14', a: 6,b: 9},
        { y: '2014-10-15', a: 9,b: 12},
        { y: '2014-10-16', a: 18,b: 20}
      ],
      xkey: 'y',
      ykeys: ['a','b'],
      labels: ['Sales','Event'],
      resize: true,
      hideHover: true,
      xLabels: 'day',
      gridTextSize: '10px',
      lineColors: ['#1caf9a','#33414E'],
      gridLineColor: '#E5E5E5'
    }); 
});
</script>

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
	<li><a href="#">Home</a></li>
	<li class="active">Dashboard</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
	<!-- START WIDGETS -->
	<div class="row">
		<div class="col-md-3">
			<!-- START WIDGET REGISTRED -->
			<div class="widget widget-default widget-item-icon" style="cursor:pointer;">
				<div class="widget-item-left">
					<span class="fa fa-users"></span>
				</div>
				<div class="widget-data">
					<div class="widget-int num-count"><?=$user?></div> 
					<div class="widget-title">Admin Users</div>
					<div class="widget-subtitle">On your Mobile App</div>
				</div>
				<div class="widget-controls">
					<a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
				</div>
			</div>
			<!-- END WIDGET REGISTRED -->
		</div>
		
		<div class="col-md-3">
			<!-- START WIDGET REGISTRED -->
			<div class="widget widget-default widget-item-icon" style="cursor:pointer;">
				<div class="widget-item-left">
					<span class="fa fa-sitemap"></span>
				</div>
				<div class="widget-data">
					<div class="widget-int num-count"><?=$location?></div> 
					<div class="widget-title">Categories</div>
					<div class="widget-subtitle">On your Mobile App</div>
				</div>
				<div class="widget-controls">
					<a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
				</div>
			</div>
			<!-- END WIDGET REGISTRED -->
		</div>
		<div class="col-md-3">
			<!-- START WIDGET REGISTRED -->
			<div class="widget widget-default widget-item-icon" style="cursor:pointer;">
				<div class="widget-item-left">
					<span class="fa fa-shopping-cart"></span>
				</div>
				<div class="widget-data">
					<div class="widget-int num-count"><?=$category?></div> 
					<div class="widget-title">Products</div>
					<div class="widget-subtitle">On your Mobile App</div>
				</div>
				<div class="widget-controls">
					<a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
				</div>
			</div>
			<!-- END WIDGET REGISTRED -->
		</div>
		<div class="col-md-3">
			<!-- START WIDGET CLOCK -->
			<div class="widget widget-default widget-padding-sm">
				<div class="widget-big-int plugin-clock">00:00</div>
				<div class="widget-subtitle plugin-date">Loading...</div>
				<div class="widget-controls">
					<a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="left" title="Remove Widget"><span class="fa fa-times"></span></a>
				</div>
				<div class="widget-buttons widget-c3">
					<div class="col">
						<a href="#"><span class="fa fa-clock-o"></span></a>
					</div>
					<div class="col">
						<a href="#"><span class="fa fa-bell"></span></a>
					</div>
					<div class="col">
						<a href="#"><span class="fa fa-calendar datepicker"></span></a>
					</div>
				</div>
			</div>
			<!-- END WIDGET CLOCK -->
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Donut Chart</h3>
				</div>
				<div class="panel-body">
					<div id="dashboard-donut-1"></div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Line Chart</h3>
				</div>
				<div class="panel-body">
					<div id="dashboard-line-1"></div>
				</div>
			</div>
		</div>
	</div>

	

</div>
<!-- END PAGE CONTENT WRAPPER -->
