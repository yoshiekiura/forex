<!-- DATA TABLES -->
<link href="<?=base_url()?>/media/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Broker
    <small></small>
    <span class="pull-right">
  		<a href="<?=site_url()?>/admin/insert_broker" data-toggle="modal" data-target="#insert_broker"><button class="btn btn-primary">Tambah Broker</button></a>
  	</span>
  </h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="box">
      <div class="box-header">
        <h3 class="box-title">Daftar Broker</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="broker_table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Logo</th>
              <th>Name</th>
              <th>Link IB</th>
              <th>Link Client</th>
              <th>Jual</th>
              <th>Beli</th>
              <th>Stock</th>
              <th style="width:140px">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
		  if($broker->num_rows() > 0) {
			  foreach($broker->result() as $row) {
				  if(is_file("media/images/logo_broker/".$row->id_broker.".png"))
				  $image = $row->id_broker.".png";
				  else
				  $image = "no-logo.png";
			  ?>
            <tr>
            	<td><img width="100" src="<?=base_url('media/images/logo_broker/'.$image);?>"</td>
                <td><?=$row->name;?></td>
                <td><?=$row->link_ib;?></td>
                <td><?=$row->link_client;?></td>
                <td><?=number_format($row->jual,2);?></td>
                <td><?=number_format($row->beli,2);?></td>
                <td><?=number_format($row->stock,2);?></td>
                <td align="left"><a href="<?=site_url()?>/admin/update_broker/<?=$row->id_broker;?>" data-toggle="modal" data-target="#update_broker"><span><i class="glyphicon glyphicon-pencil"></i></span> Update Broker</a><br />
                <a href="<?=site_url()?>/admin/delete_broker/<?=$row->id_broker;?>" class="link-delete-broker" onclick="return confirm('Yakin akan menghapus broker ini ?')"><span><i class="glyphicon glyphicon-trash"></i></span> Delete</a></td>
            </tr>
            <?php
			  }
		  } ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
</section><!-- /.content -->

<div class="modal fade" id="insert_broker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>

<div class="modal fade" id="update_broker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>

<!-- DATA TABES SCRIPT -->
<script src="<?=base_url()?>media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?=base_url()?>media/js/dataTables.bootstrap.js" type="text/javascript"></script>

<script type="text/javascript">
  
  $(document).ready(function(){   
	 $('#insert_broker, #update_broker').on('hidden.bs.modal', function() {  
		  $(this).removeData('bs.modal');  
	 });
	 $(".link-delete-broker").click(function(event){
		 event.preventDefault();
		  var e = $(this);
		  var url = e.attr('href');
		  $.post(url);
		  e.parent().parent().remove();
	 });
  });		 
</script>
