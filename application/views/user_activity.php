
<script>
$( document ).ready(function() {
    $("#user_activity").addClass('active');
});   
</script>

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li><a href="">Master</a></li>
    <li><a href="#">Sub-Admin Activity</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        
        <div class="col-md-12"  style="<?=$list?'':'display:none;'?>">
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Sub Admin</strong> Activity Search</h3>
                    </div>   
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-4 col-xs-12">
                                <label>By Subadmin</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-caret-down"></span></span>
                                    <select name="user_id" class="form-control">
                                        <option value=''>-Choose Name-</option>
                                        <?php
                                        foreach($user_list as $r){
                                            $selected = $r->user_id==$user_id?'selected':'';
                                            echo "<option value='$r->user_id' $selected>$r->first_name $r->last_name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label>From-Date</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    <input type="date" name="from" class="form-control" value="<?=$from?>" onchange="$('input[name=to]').prop('min',this.value)">
                                </div>                                
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label>To-Date</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    <input type="date" name="to" class="form-control" value="<?=$to?>" min="<?=$from?>">
                                </div>                                
                            </div>
                        </div>

                        <div class="panel-footer text-center">
                            <button type="submit" class="btn btn-primary btn-rounded"><i class="fa fa-search"></i> Search</button>
                        </div> <!-- END panel-footer -->
                    </div> <!-- END panel-body -->
                </form>
            </div> <!-- END panel panel-default -->
      
        </div> <!-- END col-md-6 -->

       
        <div class="col-md-12" style="<?=$list?'':'display:none;'?>">
            <form class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Sub-Admin </strong> Activity</h3>
                </div>
                <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered  datatable">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Activity</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach($user_activity as $r)
                                {
                                    $i++;                                
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $r->first_name; ?></td>
                                    <td><?php echo $r->last_name; ?></td>
                                    <td><?php echo $r->action; ?></td>
                                    <td><?=date('d M,Y h:i A',strtotime($r->entrydt))?></td>
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

