<script src="https://cdn.ckeditor.com/4.5.11/standard/ckeditor.js"></script>
<script>
$( document ).ready(function() {
    $("#pages").addClass('active');
    CKEDITOR.replace('content');
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
    <li><a href="#">Add Pages</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-6">
        
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" action="<?php echo site_url('site/pages_submit'); ?>" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Add Pages</strong> Master</h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                        </ul>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label class="col-md-4">Page Name</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="pages_id" id="pages_id" value="<?=$data['pages_id']?>">
                                    <select name="name" class="form-control" required>
                                        <option value=''>-select-</option>
                                        <option value='About Us'>About Us</option>
                                        <option value='FAQ'>FAQ</option>
                                        <option value='Privacy'>Privacy</option>
                                        <option value='Terms'>Terms</option>
                                    </select>
                                    <script>$('select[name=name]').val('<?=$data['name']?>')</script>
                                </div>                                
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label class="col-md-12 text-center">** Page content **</label>
                                <div class="col-md-12">
                                    <textarea name="content" id="content" class="form-control" placeholder="Page content"><?=$data['content']?></textarea>
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

       
        <div class="col-md-6">
            <form class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Pages</strong> List</h3>
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
                                <th>Pages Name</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $id = "";
                            foreach($pages as $r)
                            {
                                $i++;                                
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $r->name; ?></td>
                                <td>
                                    <a href="<?=site_url('site/pages').'?q='.$r->pages_id?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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