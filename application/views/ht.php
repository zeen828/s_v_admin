<link data-jsfiddle="common" rel="stylesheet" media="screen" href="/assets/plugins/handsontable/2014-2015/handsontable.full.css">

                    <div class="box box-info">
                    	<div class="box-body">
                    		<div class="form-group">
                    			<label>ht</label>
                        		<div class="input-group input-group-sm">
                        			<input type="text" name="keyword" class="form-control" placeholder="請輸入ID MemberID 或 E-mail...">
        							<span class="input-group-btn">
                        				<button type="button" class="search_btn btn btn-info btn-flat">查詢</button>
                        			</span>
                        		</div>
                    		</div>
                    	</div>
                    </div>
                    <div id="example"></div>
                    <!-- page js -->
                    <script src="/assets/plugins/handsontable/2014-2015/handsontable.full.js?<?php echo time();?>"></script>
                    <script type="text/javascript">
                    $(document).ready(function(){
                        var data = <?php echo $view_data;?>;
                        var container = document.getElementById('example');
                        var hot = new Handsontable(container,
                        {
	                        data: data
                        });
                    });
					</script>
                    <div class="javascript_workspace">
                    </div>
