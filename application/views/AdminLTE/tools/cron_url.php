					<div class="callout callout-info">
						<h4>提示</h4>
						<p><a href="/cron/users/registered_hour" target="_blank">[10 * * * *]每小時會員註冊數</a></p>
						<p><a href="/cron/logins/day" target="_blank">[11 10 * * *]每天不重複登入數計算</a></p>
						<p><a href="/cron/logins/month" target="_blank">[11 10 1 * *]每月不重複登入數計算</a></p>
						<p><a href="/cron/brightcove/report" target="_blank">[30 * * * *]影片觀看數據</a></p>
						<p><a href="/cron/brightcove/report_type" target="_blank">[30 03 * * *]影片觀看數據</a></p>
						<p><a href="/cron/users/copy_mysql" target="_blank">[40 * * * *]複製會員資料</a></p>
						<p><a href="/cron/users/new_copy_mysql" target="_blank">[50 * * * *]新複製會員資料</a></p>
						<p><a href="/cron/coupons/coupon_sn" target="_blank">[*/1 * * * *]序號產生</a></p>
						<p><a href="/cron/coupons/coupon_sn_expired" target="_blank">[* */2 * * *]過期序號</a></p>
						<p><a href="/cron/paykits/day_orders" target="_blank">[*/1 * * * *]爬paykit日訂單(100)</a></p>
						<p><a href="/cron/paykits/orders" target="_blank">[*/1 * * * *]爬每筆訂單資料(50)</a></p>
						<p><a href="/cron/episodes/load" target="_blank">[0 * * * *]串流資料取得(50)</a></p>
						<p><a href="/cron/users/add_user" target="_blank">[*/(7/13) * * * *]灌會員</a></p>
						<p><span class="ajax_test">AJAX TEST</span></p>
					</div>
					<script type="text/javascript">
					$(document).ready(function(){
						$('.ajax_test').off('click').on('click', function() {
								$.ajax({
									url: 'http://plugin-background.vidol.tv/api/tools/datatest.json',
									type: 'GET',
									cache: false,
									dataType: 'jsonp',
									error: function(xhr){
										alert('Ajax request error');
									},
									statusCode: {  
										200: function(data, statusText, xhr) {
											alert('成功!');
										} 
									} 
								});
						});
					});
					</script>
