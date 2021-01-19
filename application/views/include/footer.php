		<script>
			var actv_pg = "<?php echo $this->uri->segment(2); ?>";
			$("#"+actv_pg).addClass('active');
// 			$("#mCSB_1_dragger_vertical").css("margin-bootm","0px");
		</script>
		</div>
		<!-- END PAGE CONTENT -->
	</div>
	<!-- END PAGE CONTAINER -->
	
	<!-- DELETE BOX-->
	<div class="message-box animated delete_modal fadeIn" data-sound="alert" id="mb-delete">
		<div class="mb-container">
			<div class="mb-middle">
				<div class="mb-title"><span class="fa fa-trash-o"></span> Are You Sure <strong>?</strong> </div>
				<div class="mb-content">
					<p>Are you sure you want to Delete this record?</p>
					<p>Press No if you want to keep this record. Press Yes to Delete this record.</p>
				</div>
				<div class="mb-footer">
					<div class="pull-right">
						<a href="#" id="delete_log" class="btn btn-success btn-lg">Yes</a>
						<button class="btn btn-default btn-lg mb-control-close">No</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END DELETE BOX-->
	
	<!-- MESSAGE BOX-->
	<div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
		<div class="mb-container">
			<div class="mb-middle">
				<div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
				<div class="mb-content">
					<p>Are you sure you want to log out?</p>
					<p>Press No if youwant to continue work. Press Yes to logout current user.</p>
				</div>
				<div class="mb-footer">
					<div class="pull-right">
						<a href="<?php echo site_url('site/logout'); ?>" class="btn btn-success btn-lg">Yes</a>
						<button class="btn btn-default btn-lg mb-control-close">No</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END MESSAGE BOX-->
	
	<!-- START PRELOADS -->
	<audio id="audio-alert" src="<?php echo base_url(); ?>audio/alert.mp3" preload="auto"></audio>
	<audio id="audio-fail" src="<?php echo base_url(); ?>audio/fail.mp3" preload="auto"></audio>
	<!-- END PRELOADS -->
	
	<!-- START TEMPLATE -->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/settings.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/plugins.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/actions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/demo_dashboard.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/demo_charts_morris.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/demo_charts_nvd3.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/demo_charts_rickshaw.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/demo_maps.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/faq.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/demo_tasks.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/demo_icons.js"></script>
	
    
	<script>
        $('input').bind('DOMAttrModified textInput input keyup paste',function()
        {
            var sspace = $(this).val().replace(/ +/g, ' ');
            if ($(this).val() != sspace)
            {
                $(this).val(sspace);
            }
        });
        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            {
                return false;
            }
            return true;
        }
	</script>
	<!-- END TEMPLATE -->
	
	<!--- DataTables js and css Start --->
    <!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>-->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    <!--- DataTables js and css End --->
  </body>
</html>