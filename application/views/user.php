
<script>
$( document ).ready(function() {
    $("#add_user").addClass('active');
    
    $('form[name=categoryModal]').on('submit',(function(e) {
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
                    $("select[name='category_name']").append("<option value='"+obj.result['category_name']+"'>"+obj.result['category_name']+"</option>");
                    $("#categoryModal").modal('hide');
                    $.toast({
                        heading: 'Success',
                        showHideTransition: 'slide',
                        icon: 'success',
                        text: "Category has been Added",
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
function change_company_type()
{
    let company_type = $("select[name=company_type]").val();
    if(company_type == 'Group'){
        $("input[name=no_of_member]").prop("required",true).val(2);
        $("#noOfMember").show();
    }else{
        $("input[name=no_of_member]").prop("required",false).val(0);
        $("#noOfMember").hide();
    }
    create_member();
}
function create_member()
{
    let html = "";
    let no_of_member = parseInt($("input[name=no_of_member]").val());
    if(parseInt(no_of_member) < 2 && $("select[name=company_type]").val()=='Group'){
        $("input[name=no_of_member]").val(2);
        no_of_member=2;
    }
    let startFrom = 1;
    let members =<?=json_encode($members?$members:array())?>;
    for(m in members){
        if(parseInt(startFrom) <= parseInt(no_of_member)){
            html += "<tr class='new-member-row'><td>"+startFrom+"</td><td><input type='hidden' name='user_idM[]' value='"+members[m]['user_id']+"'><input type='text' name='first_nameM[]' value='"+members[m]['first_name']+"' class='form-control' placeholder='Enter First Name' required></td><td><input type='text' name='last_nameM[]' value='"+members[m]['last_name']+"' class='form-control' placeholder='Enter Last Name' required></td><td><input type='text' name='mobileM[]' value='"+members[m]['mobile']+"' class='form-control numbers' onkeypress='return isNumberKey(event)' placeholder='Enter Mobile' minlength='7' maxlength='8' required></td><td><input type='text' name='mobile2M[]' value='"+members[m]['mobile2']+"' class='form-control numbers' onkeypress='return isNumberKey(event)' placeholder='Enter Mobile' minlength='7' maxlength='8' ></td></tr>";
            startFrom++;
        }
    }
    
    if(parseInt(no_of_member) > 0)
    {
        for(let counter=startFrom; counter <= parseInt(no_of_member); counter++) 
        {
            html += "<tr class='new-member-row'><td>"+counter+"</td><td><input type='hidden' name='user_idM[]'><input type='text' name='first_nameM[]' class='form-control' placeholder='Enter First Name' required></td><td><input type='text' name='last_nameM[]' class='form-control' placeholder='Enter Last Name' required></td><td><input type='text' name='mobileM[]' class='form-control numbers' onkeypress='return isNumberKey(event)' placeholder='Enter Mobile' minlength='7' maxlength='8' required></td><td><input type='text' name='mobile2M[]' class='form-control numbers' onkeypress='return isNumberKey(event)' placeholder='Enter Mobile' minlength='7' maxlength='8' ></td></tr>";
        }
        $("#create_member").html(html);
        $(".members-contact").show();
    }else{
        $("#create_member").html("");
        $(".members-contact").hide();
    }
}
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
    <li><a href="#">Add Beneficiary</a></li>    
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        
        <div class="col-md-12" style="<?=$_GET['page']!='form'?'display:none;':''?>">
            <div class="panel panel-default" style="<?=$form?'':'display:none;'?>" >
                <form class="form-horizontal" action="<?php echo site_url('site/user_submit'); ?>" method="post" enctype="multipart/form-data">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Add Contact Details</strong> Master</h3>
                        <a href="?page=list" class="btn btn-info btn-rounded pull-right"><i class="fa fa-list"></i> Back To list</a>
                    </div>   
                    <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-2 col-xs-12">
                                <label>First Name*</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                    <input type="hidden" name="user_id" id="user_id" value="<?=$data['user_id']?>">
                                    <input type="hidden" name="type" value="user">
                                    <input type="text" name="first_name" class="form-control" placeholder='Enter First Name' value="<?=$data['first_name']?>" required>
                                </div>                                
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label>Last Name*</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                    <input type="text" name="last_name" class="form-control" placeholder='Enter Last Name' value="<?=$data['last_name']?>" required>
                                </div>                                
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label>Mobile*</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                    <input type="text" name="mobile" class="form-control numbers" onkeypress="return isNumberKey(event)" placeholder='Enter Mobile' minlength="7" maxlength="8" value="<?=$data['mobile']?>" required>
                                </div>                                
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label>Alternet Mobile</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                    <input type="text" name="mobile2" class="form-control numbers" onkeypress="return isNumberKey(event)" placeholder='Enter Mobile' minlength="7" maxlength="8" value="<?=$data['mobile2']?>" >
                                </div>                                
                            </div> 
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-4 col-xs-12">
                                <label>Category/Sector &</label> <a href="#" data-toggle="modal" data-target="#categoryModal"><i class="fa fa-plus"></i> Add New</a>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-caret-down"></span></span>
                                    <select name="category_name" class="form-control" required>
                                        <option value=''>-Choose Category-</option>
                                        <?php
                                        foreach($category_list as $r){
                                            $selected = $r->category_name==$data['category_name']?'selected':'';
                                            echo "<option value='$r->category_name' $selected>$r->category_name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-4 col-xs-12">
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
                            <div class="col-md-2 col-xs-12">
                                <label>Latitude</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-map-marker"></span></span>
                                    <input type="text" name="lat" class="form-control" placeholder='00.0000' value="<?=$data['lat']?>">
                                </div>                                
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label>Longitude</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-map-marker"></span></span>
                                    <input type="text" name="lon" class="form-control" placeholder='00.0000' value="<?=$data['lon']?>">
                                </div>                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-4 col-xs-12">
                                <label>Company Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-university"></span></span>
                                    <input type="text" name="company_name" class="form-control" placeholder='Enter Company Name' value="<?=$data['company_name']?>">
                                </div>                                
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label>Company Type</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-caret-down"></span></span>
                                    <select name="company_type" onchange="change_company_type();" class="form-control">
                                        <option value="Indivisual" <?=$data['company_type']=='Indivisual'?'selected':''?>>Indivisual</option>
                                        <option value="Group" <?=$data['company_type']=='Group'?'selected':''?>>Group</option>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-4 col-xs-12" id="noOfMember" style="<?=$data['company_type']=='Group'?'':'display:none;'?>">
                                <label>No Of Members</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                                    <input type="text" name="no_of_member"  value="<?=$data['no_of_member']?$data['no_of_member']:2?>"  min="2"  onchange="create_member();" onkeypress="return isNumberKey(event)" class="form-control" placeholder='Enter No Of Members'>
                                </div>                                
                            </div>
                        </div>
                        
                        <div class="panel panel-default members-contact" style="<?=$data['company_type']=='Group'?'':'display:none;'?>">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Member's Contact</strong> Details</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Mobile</th>
                                                        <th>Alternet Mobile</th>
                                                    </tr>
                                                    <tbody id="create_member">
                                                        <?php
                                                        $i=0;
                                                        foreach($members as $r)
                                                        {
                                                            $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?=$i?></td>
                                                                <td>
                                                                    <input type="hidden" name="user_idM[]" value="<?=$r->user_id?>">
                                                                    <input type="text" name="first_nameM[]" value="<?=$r->first_name?>" class="form-control" placeholder="Enter First Name" required="">
                                                                </td>
                                                                <td><input type="text" name="last_nameM[]" value="<?=$r->last_name?>" class="form-control" placeholder="Enter Last Name" required=""></td>
                                                                <td><input type="text" name="mobileM[]" value="<?=$r->mobile?>" class="form-control numbers" onkeypress="return isNumberKey(event)" placeholder="Enter Mobile" minlength="7" maxlength="8" required=""></td>
                                                                <td><input type="text" name="mobile2M[]" value="<?=$r->mobile2?>" class="form-control numbers" onkeypress="return isNumberKey(event)" placeholder="Enter Mobile" minlength="7" maxlength="8"></td></tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
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
                    <h3 class="panel-title"><strong>Contact Details </strong> List</h3>
                    <a href="?page=form" class="btn btn-info btn-rounded pull-right"><i class="fa fa-plus"></i> Add New</a>
                </div>
                <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>Alternet Mobile</th>
                                    <th>Category/Sector</th>
                                    <th>Location</th>
                                    <th>Company</th>
                                    <th>Company Type</th>
                                    <th>No Of Members</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Added By</th>
                                    <th>EntryTime</th>
                                    <th>Status</th>
                                    <th>Update</th>
                                    <!--<th>Delete</th>-->
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
                                    <td>(+267) <?php echo $r->mobile; ?></td>
                                    <td>(+267) <?php echo $r->mobile2; ?></td>
                                    <td><?php echo $r->category_name; ?></td>
                                    <td><?php echo $r->address; ?></td>
                                    <td><?php echo $r->company_name; ?></td>
                                    <td><?php echo $r->company_type; ?></td>
                                    <td><?php echo $r->no_of_member; ?></td>
                                    <td><?php echo $r->lat; ?></td>
                                    <td><?php echo $r->lon; ?></td>
                                    <td><?php echo $this->base->user_name($r->entryby); ?></td>
                                    <td><?=date('d M,Y h:i A',strtotime($r->entrydt))?></td>
                                    <td>
                                        <a href="<?=site_url('site/change_user_status').'?q='.$r->user_id?>" onclick="return confirm('Do You want Change the Status Of This User')" class="btn btn-rounded <?=$r->status?'btn-primary':'btn-danger'?>"><i class="fa fa-edit"></i> <?=$r->status?'Active':'Inactive'?></a>
                                    </td>
                                    <td>
                                        <a href="<?=site_url('site/user').'?page=form&q='.$r->user_id?>" class="btn btn-primary btn-rounded"><i class="fa fa-pencil"></i></a>
                                    </td>
                                    <!--<td>-->
                                    <!--    <a href="#" onclick="open_msg('<?=site_url("site/user_delete")."?q=".$r->user_id; ?>');" class="btn btn-rounded btn-primary"><i class="fa fa-trash-o"></i></a>-->
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
<div id="categoryModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="min-width: 60%;">
        <div class="modal-content panel panel-default">
            <form name="categoryModal" class="form-horizontal" action="<?=site_url('api/add_category')?>" method="post" enctype="multipart/form-data">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Add Category/Sector </strong> Master</h3>
                    <a href="#" type="button" class="pull-right" data-dismiss="modal"><i class="fa fa-times"></i></a>
                </div>   
                <h4 style="color: red;text-align: center;"><?php echo $this->session->flashdata('err_msg'); ?></h4>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-xs-12">
                            <label>Category Name</label>
                            <input type="hidden" name="entryby" value="<?=$this->session->userdata('auth_id')?>">
                            <input type="hidden" name="entrydt" value="<?=date('Y-m-d H:i:s')?>">
                            <input type="text" name="category_name" class="form-control" placeholder='Enter Category Name' value="<?=$data['category_name']?>" required>
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
