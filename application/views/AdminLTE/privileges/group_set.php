                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">群組權限設定</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <input type="hidden" name="group_id" value="<?php echo $this->uri->segment(4);?>">
                            <table class="table table-hover">
                                <tbody>
<?php
if(count($view_data)>0){
    foreach ($view_data as $master_key_=>$master_val){
?>
                                    <tr>
                                        <th><?php echo $master_val['title']?></th>
<?php
if(count($master_val['data'])>0){
    foreach ($master_val['data'] as $slave_key=>$slave_val){
        $checkbox = ($slave_val['checkbox']) ? 'checked' : '';
?>
                                        <th>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="privileges" value="<?php echo $slave_val['pk']?>" <?php echo $checkbox;?>>
                                                    <?php echo $slave_val['action']?>
                                                </label>
                                            </div>
                                        </th>
<?php
    }
}
?>
                                    </tr>
<?php
    }
}
?>
                                </tbody>
                            </table>
                            <div class="box-footer">
                                <button type="button" class="send_btn btn btn-block btn-info">設定</button>
                            </div>
                        </div>
                    </div>
                    <!-- iCheck 1.0.1 -->
                    <script src="/assets/plugins/iCheck/icheck.min.js"></script>
                    <!-- page js -->
                    <script src="/assets/js/custom/privileges/group_set.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace"></div>
