
<script>
$( document ).ready(function() {
    $("#change_password").addClass('active');
});   
</script>

<script>
function open_msg(link)
{
    $("#delete_log").prop('href',link);
    $("#mb-delete").modal('show');
}
</script>
<style>
td .image-responsive{
    height: 55px;
    width: auto;
}
</style>

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li><a href="">Master</a></li>
    <li><a href="#">change password</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row form-group">
        
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Change Your Password</strong> here</h3>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-4">Old Password</label>
                            <div class="input-group col-md-8">
                                <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                <input type="text" name="old_password" class="form-control" placeholder='Enter Old Password' required>
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">New Password</label>
                            <div class="input-group col-md-8">
                                <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                <input type="text" name="password" class="form-control" placeholder='Enter New Password' required>
                            </div>                                
                        </div>
                    </div> <!-- END panel-body -->
                    <div class="panel-footer">
                        <button type="reset" class="btn btn-default">Clear Form</button>
                        <input type="submit" name="change_password" class="btn btn-primary pull-right" value="Submit">
                    </div> <!-- END panel-footer -->
                </form>
            </div> <!-- END panel panel-default -->
        </div> <!-- END col-md-6 -->

       
        
    </div> <!-- END row -->
</div> <!-- END page-content-wrap -->
<!-- END PAGE CONTENT WRAPPER -->      