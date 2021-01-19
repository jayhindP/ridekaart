
<script>
$( document ).ready(function() {
    $("#master").addClass('active');
    $(".select2").select2({
        placeholder: 'All Users'
    });
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
    <li><a href="#">Send Notification</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
        
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" action="<?php echo site_url('site/notification_submit'); ?>" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Add Notification</strong> Master</h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                        </ul>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label class="col-md-2">Notification Title</label>
                                <div class="col-md-10">
                                    <input type="hidden" name="notification_id" id="notification_id" value="<?=$data['notification_id']?>">
                                    <input type="text" name="notification_title" class="form-control" placeholder='Enter Name Of notification' value="<?=$data['notification_title']?>" required>
                                </div>                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label class="col-md-2">Notification (Message)</label>
                                <div class="col-md-10">
                                    <textarea type="text" name="notification" class="form-control" placeholder='Message' required><?=$data['notification']?></textarea>
                                </div>                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label class="col-md-2">Send To</label>
                                <div class="col-md-10">
                                    <select name="send_to[]" class="form-control select2" multiple>
                                        <option value='' disabled>All Users</option>
                                        <?php 
                                        $send_to = explode(",", $data['send_to']);
                                        foreach($user_list as $r){
                                            $selected = in_array($r->user_id, $send_to) ? 'selected' :'';
                                            echo "<option value='$r->user_id' $selected>$r->name ($r->mobile)</option>";
                                        }
                                        ?>
                                    </select>
                                </div>                                
                            </div>
                        </div>

                        <div class="panel-footer">
                            <a  class="btn btn-default" onclick="reset();">Clear Form</a>
                            <input type="submit" id="submit" class="btn btn-primary pull-right" value="Submit">
                        </div> <!-- END panel-footer -->
                    </div> <!-- END panel-body -->
                </form>
            </div> <!-- END panel panel-default -->
      
        </div> <!-- END col-md-6 -->

       
        <div class="col-md-12">
            <form class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Notifications</strong> List</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>Notification Title</th>
                                <th>Message</th>
                                <th>Send To</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $id = "";
                            foreach($notification as $r)
                            {
                                $i++;         
                                $send_to = explode(",", $r->send_to);
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $r->notification_title; ?></td>
                                <td><?php echo $r->notification; ?></td>
                                <td><?php echo $send_to ? count($send_to).' Users' : 'All Users'; ?> </td>
                                <td>
                                    <a href="<?=site_url('site/notification').'?q='.$r->notification_id?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                </td>
                                <td>
                                    <a href="#" onclick="open_msg('<?=site_url("site/notification_delete")."?q=".$r->notification_id; ?>');" class="btn btn-primary"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            <?php                            
                            }
                            ?>
                        </tbody>
                    </table>
                </div> <!-- END panel-body -->
            </div> <!-- END panel panel-default -->
            </form>
        </div> <!-- END col-md-6 -->
        
    </div> <!-- END row -->
</div> <!-- END page-content-wrap -->
<!-- END PAGE CONTENT WRAPPER -->      