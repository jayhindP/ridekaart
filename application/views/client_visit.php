
<script>
$( document ).ready(function() {
    $("#client_visit").addClass('active');
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
.image-responsive{
    height: 55px;
    width:55px;
    border-radius:50%;
}
</style>

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li><a href="">Master</a></li>
    <li><a href="#">Client Visit</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        
        <div class="col-md-12">
            <div class="panel panel-default">
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Client Followup</strong> Search</h3>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-3 col-xs-12">
                                <label>By Employee</label>
                                <select name="user_id" class="form-control">
                                    <option value=''>-Choose User-</option>
                                    <?php
                                    foreach($user_list as $r){
                                        $selected = $r->user_id==$user_id?'selected':'';
                                        echo "<option value='$r->user_id' $selected>$r->name</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>By Client</label>
                                <select name="client_id" class="form-control">
                                    <option value=''>-Choose Client/Shop-</option>
                                    <?php
                                    foreach($client_list as $r){
                                        $selected = $r->user_id==$client_id?'selected':'';
                                        echo "<option value='$r->user_id' $selected>$r->name</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>By From - Date</label>
                                <input type="date" name="from" class="form-control" value="<?=$from?>">
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>By To - Date</label>
                                <input type="date" name="to" class="form-control" value="<?=$to?>">
                            </div>
                        </div>

                        <div class="panel-footer text-center">
                            <button type="submit" id="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        </div> <!-- END panel-footer -->
                    </div> <!-- END panel-body -->
                </form>
            </div> <!-- END panel panel-default -->
      
        </div> <!-- END col-md-6 -->

       
        <div class="col-md-12">
            <form class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Clients Followup</strong> List</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Date</th>
                                    <th>Shop Name</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>Visit</th>
                                    <th>Order</th>
                                    <th>Payment</th>
                                    <th>Due</th>
                                    <th>Description</th>
                                    <th>Visit By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach($client_visit as $r)
                                {
                                    $i++;                                
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo date("d M Y",strtotime($r->date)); ?></td>
                                    <td><?php echo $r->client_name; ?></td>
                                    <td><?php echo $r->client_mobile; ?></td>
                                    <td><?php echo $r->client_address; ?></td>
                                    <td><label class="label <?php echo $r->visited?"label-success":"label-danger"; ?>"><?php echo $r->visited?"Yes":"No"; ?></label></td>
                                    <td><label class="label <?php echo $r->orders?"label-success":"label-danger"; ?>"><?php echo $r->orders?"Yes":"No"; ?></label></td>
                                    <td><label class="label <?php echo $r->payment?"label-success":"label-danger"; ?>"><?php echo $r->payment?"Yes":"No"; ?></label></td>
                                    <td><label class="label <?php echo $r->due?"label-success":"label-danger"; ?>"><?php echo $r->due?"Yes":"No"; ?></label></td>
                                    <td><?php echo $r->remark; ?></td>
                                    <td><?php echo $r->name; ?></td>
                                </tr>
                                <?php                            
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- END panel-body -->
            </div> <!-- END panel panel-default -->
            </form>
        </div> <!-- END col-md-6 -->
        
    </div> <!-- END row -->
</div> <!-- END page-content-wrap -->
<!-- END PAGE CONTENT WRAPPER -->      