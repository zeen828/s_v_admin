                    <div class="box box-info">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>Size</label>
                                        <select name="qrcode_size" class="form-control">
                                            <option value="4">小</option>
                                            <option value="8" selected>中</option>
                                            <option value="10">大</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-8">
                                        <label>輸入網址</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="qrcode_data" class="form-control" placeholder="輸入網址...">
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
                    <script src="/assets/js/custom/tools/qrcode.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace">
                    </div>
