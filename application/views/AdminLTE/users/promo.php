                    <div class="box box-info">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>Promo Package</label>
                                        <div class="input-group input-group-sm">
	                                        <select name="promo_package" class="form-control">
<?php
$package = $view_data['package'];
if(count($package) > 0){
	foreach($package as $key=>$val){
		$selected = '';
?>
												<option value="<?php echo $val->sp_pk;?>"<?php echo $selected;?>><?php echo $val->sp_title;?></option>
<?php
	}
}
?>
	                                        </select>
                                            <span class="input-group-btn">
                                                <button type="button" class="send_btn btn btn-info btn-flat">產生</button>
                                            </span>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- page js -->
                    <script src="/assets/js/custom/users/promo.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace">
                    </div>
