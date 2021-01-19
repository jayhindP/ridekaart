
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
    <li><a href="#">Add offer</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
        
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" action="<?php echo site_url('site/offer_submit'); ?>" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Add offer</strong> Master</h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                        </ul>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Offer Title</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-offer"></span></span>
                                    <input type="hidden" name="offer_id" id="offer_id" value="<?=$data['offer_id']?>">
                                    <input type="text" name="offer_title" class="form-control" placeholder='Enter Name Of offer' value="<?=$data['offer_title']?>" required>
                                </div>                                
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Offer Description</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-offer"></span></span>
                                    <input type="text" name="description" class="form-control" placeholder='Enter Name Of offer' value="<?=$data['description']?>" required>
                                </div>                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Valid From</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-offer"></span></span>
                                    <input type="date" name="valid_from" class="form-control" value="<?=$data['valid_from']?>" required>
                                </div>                                
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Valid To</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-offer"></span></span>
                                    <input type="date" name="valid_to" class="form-control" value="<?=$data['valid_to']?>" required>
                                </div>                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Discount Type</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-offer"></span></span>
                                    <select name="discount_type" class="form-control">
                                        <option value='Fixed' <?=$data['discount_type'] != 'Percent'?'selected':''?> >Fixed</option>
                                        <option value='Percent' <?=$data['discount_type'] != 'Percent'?'':'selected'?> >Percent</option>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">Discount Value</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-offer"></span></span>
                                    <input type="number" name="discount_value" placeholder="00" class="form-control" value="<?=$data['discount_value']?>" required>
                                </div>                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <label class="col-md-4">offer Image</label>
                                <div class="input-group col-md-8">
                                    <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                                    <input type="file" name="offer_image"  class="form-control" <?=$data['offer_image']?'':'required'?>>
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
                    <h3 class="panel-title"><strong>offers</strong> List</h3>
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
                                <th>Offer Title</th>
                                <th>Offer Description</th>
                                <th>Valid From</th>
                                <th>Valid To</th>
                                <th>Discount Type</th>
                                <th>Discount Value</th>
                                <th>Image</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $id = "";
                            foreach($offer as $r)
                            {
                                $i++;                                
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $r->offer_title; ?></td>
                                <td><?php echo $r->description; ?></td>
                                <td><?php echo $r->valid_from; ?></td>
                                <td><?php echo $r->valid_to; ?></td>
                                <td><?php echo $r->discount_type; ?></td>
                                <td><?php echo $r->discount_value; ?></td>
                                <td><img  height="50" width="130" src="<?=base_url($r->offer_image)?>" alt="<?=$r->offer_name?>"></td>
                                <td>
                                    <a href="<?=site_url('site/offer').'?q='.$r->offer_id?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                </td>
                                <td>
                                    <a href="#" onclick="open_msg('<?=site_url("site/offer_delete")."?q=".$r->offer_id; ?>');" class="btn btn-primary"><i class="fa fa-trash-o"></i></a>
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