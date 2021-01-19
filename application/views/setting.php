
<script>
$( document ).ready(function() {
    $("#setting").addClass('active');
});   
</script>


<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li><a href="">Master</a></li>
    <li><a href="#">Add setting</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
        
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong><i class="fa fa-cogs"></i> Setting</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                        </ul>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Delivery Charge</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-inr"></span></span>
                                    <input type="text" name="delivery_charge" class="form-control" placeholder='Enter delivery charge' value="<?=$data['delivery_charge']?>">
                                </div>                                
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Max Bill Amount <br><small>(to Apply Delivery Charge)</small></label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-inr"></span></span>
                                    <input type="text" name="max_delivery_amt" class="form-control" placeholder='Enter Max Amt' value="<?=$data['max_delivery_amt']?>">
                                </div>                                
                            </div>
                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary pull-right" name="save_setting"><i class="fa fa-save"></i> Save Changes</button>
                        </div> <!-- END panel-footer -->
                    </div> <!-- END panel-body -->
                </form>
            </div> <!-- END panel panel-default -->
      
        </div> <!-- END col-md-6 -->

       
        
    </div> <!-- END row -->
</div> <!-- END page-content-wrap -->
<!-- END PAGE CONTENT WRAPPER -->      