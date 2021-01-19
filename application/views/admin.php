
<script>
$( document ).ready(function() {
    $("#add_admin").addClass('active');
    
    $('form[name=constituencyModal]').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:'json',
            success:function(obj){
                if(obj.status===1){
                    $("select[name='category_name']").append("<option value='"+obj.result['constituency_name']+"'>"+obj.result['constituency_name']+"</option>");
                    $("#constituencyModal").modal('hide');
                    $.toast({
                        heading: 'Success',
                        showHideTransition: 'slide',
                        icon: 'success',
                        text: "Constituency has been Added",
                        position:'top-right',
                        delay:8000
                    }); 
                }else{
                    $.toast({
                        heading: obj.result,
                        showHideTransition: 'slide',
                        icon: 'warning',
                        text: obj.result,
                        position:'top-right',
                        delay:8000
                    });
                }
            },
        });
    }));
    $('form[name=locationModal]').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:'json',
            success:function(obj){
                if(obj.status===1){
                    $("select[name='address']").append("<option value='"+obj.result['location_name']+"'>"+obj.result['location_name']+"</option>");
                    $("#locationModal").modal('hide');
                    $.toast({
                        heading: 'Success',
                        showHideTransition: 'slide',
                        icon: 'success',
                        text: "Location has been Added",
                        position:'top-right',
                        delay:8000
                    }); 
                }else{
                    $.toast({
                        heading: obj.result,
                        showHideTransition: 'slide',
                        icon: 'warning',
                        text: obj.result,
                        position:'top-right',
                        delay:8000
                    });
                }
            },
        });
    }));
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
    <li><a href="#">Add Sub-Admin</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        
        <div class="col-md-12" style="<?=$_GET['page']!='form' || $this->session->userdata('subadmin')==1?'display:none;':''?>">
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" action="<?php echo site_url('site/admin_submit'); ?>" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Add Sub Admin</strong> Master</h3>
                        <a href="?page=list" class="btn btn-info btn-rounded pull-right"><i class="fa fa-list"></i> Back To list</a>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-3 col-xs-12">
                                <label>First Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                    <input type="hidden" name="user_id" id="user_id" value="<?=$data['user_id']?>">
                                    <input type="text" name="first_name" class="form-control" placeholder='Enter First Name' value="<?=$data['first_name']?>" required>
                                </div>                                
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                    <input type="text" name="last_name" class="form-control" placeholder='Enter Last Name' value="<?=$data['last_name']?>" required>
                                </div>                                
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>Mobile</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                    <input type="text" name="mobile" class="form-control" placeholder='Enter Mobile' title="Please Enter Valid Mobile Number" onkeypress="return isNumberKey(event)" minlength="7" maxlength="8" value="<?=$data['mobile']?>" required>
                                </div>                                
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>Work Line</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                    <input type="text" name="mobile2" class="form-control" placeholder='Enter Mobile' title="Please Enter Valid digit Mobile Number" onkeypress="return isNumberKey(event)" minlength="7" maxlength="8" value="<?=$data['mobile']?>">
                                </div>                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-3 col-xs-12">
                                <label>Constituency *</label> <a href="#" data-toggle="modal" data-target="#constituencyModal"><i class="fa fa-plus"></i> Add New</a>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-caret-down"></span></span>
                                    <select name="category_name" class="form-control" required>
                                        <option value=''>-Choose Constituency-</option>
                                        <?php
                                        foreach($constituency_list as $r){
                                            $selected = $r->constituency_name==$data['category_name']?'selected':'';
                                            echo "<option value='$r->constituency_name' $selected>$r->constituency_name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>Location *</label> <a href="#" data-toggle="modal" data-target="#locationModal"><i class="fa fa-plus"></i> Add New</a>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-caret-down"></span></span>
                                    <select name="address" class="form-control" required>
                                        <option value=''>-Choose Location-</option>
                                        <?php
                                        foreach($location_list as $r){
                                            $selected = $r->location_name==$data['address']?'selected':'';
                                            echo "<option value='$r->location_name' $selected>$r->location_name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>Email Id</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-envelope"></span></span>
                                    <input type="email" name="email" class="form-control" placeholder='Enter Login Email' value="<?=$data['email']?>">
                                </div>                                
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label>Password</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                    <input type="text" name="password" class="form-control" placeholder='Enter Login Password' value="<?=$data['password']?>">
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

       
        <div class="col-md-12" style="<?=$_GET['page']!='form'?'':'display:none;'?>">
            <form class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Sub-Admin </strong> List</h3>
                    <a href="?page=form" class="btn btn-info btn-rounded pull-right" style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>"><i class="fa fa-plus"></i> Add New</a>
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
                                    <th>Constituency</th>
                                    <th>Location</th>
                                    <th>Mobile</th>
                                    <th>Work Line</th>
                                    <th>Email</th>
                                    <th style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>">Password</th>
                                    <th style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>">Status</th>
                                    <th style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>">Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach($user as $r)
                                {
                                    $i++;                                
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $r->first_name; ?></td>
                                    <td><?php echo $r->last_name; ?></td>
                                    <td><?php echo $r->category_name; ?></td>
                                    <td><?php echo $r->address; ?></td>
                                    <td><?php echo $r->mobile; ?></td>
                                    <td><?php echo $r->mobile2; ?></td>
                                    <td><?php echo $r->email; ?></td>
                                    <td style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>"><?php echo $r->password; ?></td>
                                    <td style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>">
                                        <a href="<?=site_url('site/change_admin_status').'?q='.$r->user_id?>" onclick="return confirm('Do You want Change the Status Of This User')" class="btn btn-rounded <?=$r->status?'btn-primary':'btn-danger'?>"><i class="fa fa-edit"></i> <?=$r->status?'Active':'Inactive'?></a>
                                    </td>
                                    <td style="<?=$this->session->userdata('subadmin')==1?'display:none;':''?>">
                                        <a href="<?=site_url('site/admin').'?page=form&q='.$r->user_id?>" class="btn btn-primary btn-rounded"><i class="fa fa-pencil"></i></a>
                                    </td>
                                    <!--<td>-->
                                    <!--    <a href="#" onclick="open_msg('<?=site_url("site/admin_delete")."?q=".$r->user_id; ?>');" class="btn btn-rounded btn-primary"><i class="fa fa-trash-o"></i></a>-->
                                    <!--</td>-->
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


<!-- Modal -->
<div id="constituencyModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="min-width: 60%;">
        <div class="modal-content panel panel-default">
            <form name="constituencyModal" class="form-horizontal" action="<?=site_url('api/add_constituency')?>" method="post" enctype="multipart/form-data">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Add Constituency </strong> Master</h3>
                    <a href="#" type="button" class="pull-right" data-dismiss="modal"><i class="fa fa-times"></i></a>
                </div>   
                <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-xs-12">
                            <label>Constituency Name</label>
                            <input type="hidden" name="entryby" value="<?=$this->session->userdata('auth_id')?>">
                            <input type="hidden" name="entrydt" value="<?=date('Y-m-d H:i:s')?>">
                            <input type="text" name="constituency_name" class="form-control" placeholder='Enter constituency Name' required>
                        </div>
                    </div>
                </div> <!-- END panel-body -->
                <div class="panel-footer text-center">
                    <button type="submit" name="submit" class="btn btn-primary submit-btn">Submit Details</button>
                </div>
            </form>
        </div> <!-- END panel-->
    </div>
</div>

<!-- Modal -->
<div id="locationModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="min-width: 60%;">
        <div class="modal-content panel panel-default">
            <form name="locationModal" class="form-horizontal" action="<?=site_url('api/add_location')?>" method="post" enctype="multipart/form-data">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Add Location </strong> Master</h3>
                    <a href="#" type="button" class="pull-right" data-dismiss="modal"><i class="fa fa-times"></i></a>
                </div>   
                <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-xs-12">
                            <label>Location Name</label>
                            <input type="hidden" name="entryby" value="<?=$this->session->userdata('auth_id')?>">
                            <input type="hidden" name="entrydt" value="<?=date('Y-m-d H:i:s')?>">
                            <input type="text" name="location_name" class="form-control" placeholder='Enter location Name' value="<?=$data['location_name']?>" required>
                        </div>
                    </div>
                </div> <!-- END panel-body -->
                <div class="panel-footer text-center">
                    <button type="submit" name="submit" class="btn btn-primary submit-btn">Submit Details</button>
                </div>
            </form>
        </div> <!-- END panel-->
    </div>
</div>
