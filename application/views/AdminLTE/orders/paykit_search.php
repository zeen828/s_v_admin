                    <div class="box box-info">
                        <div class="box-body">
                            <div class="form-group">
                                <label>請輸入transtionID</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="keyword" value="<?php echo $this->uri->segment(4);?>" class="form-control" placeholder="請輸入transtionID...">
                                    <span class="input-group-btn">
                                        <button type="button" class="search_btn btn btn-info btn-flat">查詢</button>
                                    </span>
                                </div>
                                <label>相關連結</label>
                                <div>
                                	<a href="/backend/users/search"><span class="fa fa-search"></span>會員查詢</a>
                                	<a href="/backend/orders/search"><span class="fa fa-search"></span>訂單查詢</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- page js -->
                    <script src="/assets/js/custom/orders/paykit_search.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace">
                    </div>
