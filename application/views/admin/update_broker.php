<div class="modal-body">
	<div class="row">
    	<div class="col-xs-12">
        	<div class="box">
                <div class="box-header with-border" align="center">
                  <h3 class="box-title"><strong>Update Broker</strong></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<form id="form_update_broker" action="<?=site_url('admin/save_broker/'.$broker->id_broker);?>" method="post">
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" name="broker_name" class="form-control" value="<?=$broker->name;?>" />
                    </div>
                    <div class="form-group">
                      <label>Link IB</label>
                      <input type="text" name="link_ib" class="form-control" value="<?=$broker->link_ib;?>" />
                    </div>
                    <div class="form-group">
                      <label>Link Client</label>
                      <input type="text" name="link_client" class="form-control" value="<?=$broker->link_client;?>" />
                    </div>
                    <div class="form-group">
						<button type="submit" id="btn_update_broker" class="btn btn-block btn-primary">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url('media/js/jquery.form.min.js');?>"></script>
<script type="text/javascript">
	$(function(){
		$("#form_update_broker").ajaxForm({
			beforeSubmit : function(){
				$("#btn_update_broker").text('Submitting..').attr('disabled',true);
			},
			success : function(res){
				$("#btn_update_broker").text('Submit').attr('disabled',false);
				if(res != 'ok')
				alert('Error update data');
				else
				document.location.reload();
			}
		});
	});
</script>