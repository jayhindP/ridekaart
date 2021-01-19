
<script>
    $( document ).ready(function() {
    $("#master").addClass('active');
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
    <li><a href="#">Add location</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        
        <div class="col-md-12" style="<?=$_GET['page']!='form'?'display:none;':''?>">
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" action="<?php echo site_url('site/location_submit'); ?>" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Add/Edit Locations</strong> Master</h3>
                        <a href="?page=list" class="btn btn-info btn-rounded pull-right"><i class="fa fa-list"></i> Back To list</a>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Location Name</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-sitemap"></span></span>
                                    <input type="hidden" name="location_id" id="location_id" value="<?=$data['location_id']?>">
                                    <input type="text" name="location_name" class="form-control" placeholder='Enter Name Of location' value="<?=$data['location_name']?>" required>
                                </div>                                
                            </div>
                            <!--<div class="col-md-6 col-xs-12">-->
                            <!--    <label class="col-md-4">location Image (Optional)</label>-->
                            <!--    <div class="input-group col-md-8">-->
                            <!--        <span class="input-group-addon"><span class="fa fa-image"></span></span>-->
                            <!--        <input type="file" name="location_image" class="form-control">-->
                            <!--    </div>                                -->
                            <!--</div>-->
                        </div>

                        <div class="panel-footer">
                            <a  class="btn btn-default" onclick="reset();">Clear Form</a>
                            <input type="submit" id="submit" class="btn btn-primary pull-right" value="Submit">
                        </div> <!-- END panel-footer -->
                    </div> <!-- END panel-body -->
                </form>
            </div> <!-- END panel panel-default -->
      
        </div> <!-- END col-md-6 -->

       
        <div class="col-md-12" style="<?=$_GET['page']!='form'?'':'display:none;'?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Locations</strong> List</h3>
                    <a href="?page=form" class="btn btn-info btn-rounded pull-right"><i class="fa fa-plus"></i> Add New</a>
                </div>
                <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                <div class="panel-body">
                    <table class="table table-striped table-bordered datatable">
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <!--<th>location Image</th>-->
                                <th>location Name</th>
                                <th>Addded By</th>
                                <th>Entry Time</th>
                                <th>Status</th>
                                <th>Update</th>
                                <!--<th>Delete</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach($location as $r)
                            {
                                $i++;                                
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <!--<td><image src="<?=base_url($r->location_image)?>" style="max-width:64px"></td>-->
                                <td><?=$r->location_name?></td>
                                <td><?php echo $this->base->user_name($r->entryby); ?></td>
                                <td><?=date('d M,Y h:i A',strtotime($r->entrydt))?></td>
                                <td>
                                    <a href="<?=site_url('site/change_location_status').'?q='.$r->location_id?>" onclick="return confirm('Do You want Change the Status Of This location')" class="btn btn-rounded <?=$r->status?'btn-primary':'btn-danger'?>"><i class="fa fa-edit"></i> <?=$r->status?'Active':'Inactive'?></a>
                                </td>
                                <td>
                                    <a href="<?=site_url('site/location').'?page=form&q='.$r->location_id?>" class="btn btn-primary btn-rounded"><i class="fa fa-pencil"></i></a>
                                </td>
                                <!--<td>-->
                                <!--    <a href="#" onclick="open_msg('<?=site_url("site/location_delete")."?q=".$r->location_id; ?>');" class="btn btn-primary btn-rounded"><i class="fa fa-trash-o"></i></a>-->
                                <!--</td>-->
                            </tr>
                            <?php                            
                            }
                            ?>
                        </tbody>
                    </table>
                </div> <!-- END panel-body -->
            </div> <!-- END panel panel-default -->
        </div> <!-- END col-md-6 -->
        
    </div> <!-- END row -->
</div> <!-- END page-content-wrap -->
<!-- END PAGE CONTENT WRAPPER -->      