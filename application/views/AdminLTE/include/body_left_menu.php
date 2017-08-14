            <aside class="main-sidebar">
            	<section class="sidebar">
            		<!-- Sidebar user panel -->
            		<div class="user-panel">
            			<div class="pull-left image">
            				<img src="<?php echo $header['user']['user_img'];?>" class="img-circle" alt="User Image">
            			</div>
            			<div class="pull-left info">
            				<p><?php echo $header['user']['username'];?></p>
            				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            			</div>
            		</div>
            		<!-- sidebar menu: : style can be found in sidebar.less -->
            		<ul class="sidebar-menu">
            			<li class="header">MAIN NAVIGATION</li>
<?php if($this->flexi_auth->is_privileged(array('Users View', 'Users Add', 'Users Edit', 'Users Del'))) {?>
            			<li class="Users treeview"><a href="#"> <i class="fa fa-users"></i> <span>會員資料</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/users/registered_hour"><i class="fa fa-bar-chart"></i> 註冊數(小時)</a></li>
            					<li><a href="/backend/users/registered_day"><i class="fa fa-bar-chart"></i> 註冊數(每日)</a></li>
<!-- <li><a href="/backend/users/registered_month"><i class="fa fa-bar-chart"></i> 註冊數(每月)</a></li> -->
<!-- <li><a href="/backend/users/age"><i class="fa fa-bar-chart"></i> 年齡層</a></li> -->
            					<li><a href="/backend/users/login_day"><i class="fa fa-bar-chart"></i> 登入數(每日)</a></li>
            					<li><a href="/backend/users/login_month"><i class="fa fa-bar-chart"></i> 登入數(每月)</a></li>
            					<li><a href="/backend/users/search"><i class="fa fa-search"></i> 會員查詢</a></li>
            					<li><a href="/backend/users/login_log"><i class="fa fa-align-center"></i> 會員登入紀錄</a></li>
<?php if($this->flexi_auth->is_privileged(array('Orders View', 'Orders Add', 'Orders Edit', 'Orders Del'))) {?>
            					<li><a href="/backend/users/order"><i class="fa fa-credit-card"></i> 會員訂單紀錄</a></li>
<?php }?>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Votes View', 'Votes Add', 'Votes Edit', 'Votes Del'))) {?>
            			<li class="Votes treeview"><a href="#"> <i class="fa fa-line-chart"></i> <span>投票系統</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/votes/mrplay"><i class="fa fa-qrcode"></i> 玩很大進校園</a></li>
            					<li><a href="/backend/votes/mrplay_list"><i class="fa fa-bar-chart"></i> 玩很大進校園報表</a></li>
            					<li><a href="/backend/votes/mrplay_gifts_list"><i class="fa fa-bar-chart"></i> 玩粉感恩大放送</a></li>
            					<li><a href="/backend/votes/bromance_meetings_list"><i class="fa fa-bar-chart"></i> 愛上哥們贈東京票</a></li>
            					<li><a href="/backend/votes/ob_iphone8_list"><i class="fa fa-bar-chart"></i> OB嚴選送iphone8</a></li>
<?php if($this->flexi_auth->is_privileged(array('Votes Config View', 'Votes Config Add', 'Votes Config Edit', 'Votes Config Del'))) {?>
            					<li><a href="/backend/vote_config/config"><i class="fa fa-cog"></i> 投票系統設定</a></li>
<?php }?>
							</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Lottery View', 'Lottery Add', 'Lottery Edit', 'Lottery Del'))) {?>
            			<li class="Lottery treeview"><a href="#"> <i class="fa fa-star"></i> <span>抽獎活動</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/lotteries/iphone8_week1"><i class="fa fa-space-shuttle"></i> OB嚴選送iphone8(第一周)</a></li>
            					<li><a href="/backend/lotteries/iphone8_week2"><i class="fa fa-space-shuttle"></i> OB嚴選送iphone8(第二周)</a></li>
            					<li><a href="/backend/lotteries/system"><i class="fa fa-star"></i> 抽獎系統</a></li>
<?php if($this->flexi_auth->is_privileged(array('Lottery Config View', 'Lottery Config Add', 'Lottery Config Edit', 'Lottery Config Del'))) {?>
            					<li><a href="/backend/lotteries/config"><i class="fa fa-cog"></i> 抽獎系統設定</a></li>
<?php }?>
							</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Analytics View', 'Analytics Add', 'Analytics Edit', 'Analytics Del'))) {?>
            			<li class="Analytics treeview"><a href="#"> <i class="fa fa-jsfiddle"></i> <span>數據報表</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/analytics/brightcove"><i class="fa fa-jsfiddle"></i> Brightcove</a></li>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Dealers View', 'Dealers Add', 'Dealers Edit', 'Dealers Del'))) {?>
            			<li class="Dealers treeview"><a href="#"> <i class="fa fa-diamond"></i> <span>經銷商管理</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/dealers/dealer"><i class="fa fa-diamond"></i> 經銷商管理</a></li>
            					<li><a href="/backend/dealers/user_binding"><i class="fa fa-diamond"></i> 設備綁定管理</a></li>
            					<li><a href="/backend/dealers/ovo_ad"><i class="fa fa-desktop"></i> OVO動態牆管理</a></li>
            					<li><a href="/backend/dealers/version"><i class="fa fa-desktop"></i> 電視盒公版版本管理</a></li>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Orders View', 'Orders Add', 'Orders Edit', 'Orders Del'))) {?>
            			<li class="Orders treeview"><a href="#"> <i class="fa fa-money"></i> <span>訂單管理</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/orders/search"><i class="fa fa-search"></i> 訂單查詢</a></li>
            					<li><a href="/backend/orders/paykit_search"><i class="fa fa-search"></i> paykit訂單查詢</a></li>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Billing View', 'Billing Add', 'Billing Edit', 'Billing Del'))) {?>
            			<li class="Billings treeview"><a href="#"> <i class="fa fa-btc"></i> <span>計費管理</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/billings/package"><i class="fa fa-object-group"></i> 產品包管理</a></li>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Coupons View', 'Coupons Add', 'Coupons Edit', 'Coupons Del', 'Coupons set View', 'Coupons set Add', 'Coupons set Edit', 'Coupons set Del'))) {?>
            			<li class="Coupons treeview"><a href="#"> <i class="fa fa-btc"></i> <span>序號管理</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/coupons/coupon_set"><i class="fa fa-object-group"></i> 序號設定</a></li>
            					<li><a href="/backend/coupons/coupon"><i class="fa fa-object-ungroup"></i> 序號</a></li>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Payments View', 'Payments Add', 'Payments Edit', 'Payments Del'))) {?>
            			<li class="Payments treeview"><a href="#"> <i class="fa fa-btc"></i> <span>金流通路管理</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/payments/payment"><i class="fa fa-object-group"></i> 金流通路管理</a></li>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Tools View', 'Tools Add', 'Tools Edit', 'Tools Del'))) {?>
            			<li class="Tools treeview"><a href="#"> <i class="fa fa-wheelchair"></i> <span>工具幫手</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/tools/qrcode"><i class="fa fa-qrcode"></i> QRcode產生</a></li>
            					<li><a href="/backend/tools/slide"><i class="fa fa-television"></i> 節目表</a></li>
            					<li><a href="/backend/tools/ios"><i class="fa fa-apple"></i> ios設定檔</a></li>
            					<li><a href="http://app.vidol.tv/chatroom/" target="_blank"><i class="fa fa-apple"></i> chatroom</a></li>
<?php if($this->flexi_auth->is_privileged(array('Boards Edit'))) {?>
            					<li><a href="/backend/tools/board"><i class="fa fa-refresh"></i> 留言轉移</a></li>
<?php }?>
								<li><a href="/backend/tools/tmp"><i class="fa fa-refresh"></i> 臨時上傳</a></li>
								<li><a href="/backend/tools/cron_url"><i class="fa fa-refresh"></i> 觀看排程網址</a></li>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Rests View', 'Rests Add', 'Rests Edit', 'Rests Del'))) {?>
            			<li class="Rests treeview"><a href="#"> <i class="fa fa-terminal"></i> <span>RESTful管理</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
            					<li><a href="/backend/rests/key"><i class="fa fa-key"></i> 金鑰管理</a></li>
            				</ul>
            			</li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Accounts View', 'Groups View', 'Privileges View'))) {?>
            			<li class="Accounts Groups Privileges Systems treeview"><a href="#"> <i class="fa fa-cog"></i> <span>系統管理</span> <i class="fa fa-angle-left pull-right"></i></a>
            				<ul class="treeview-menu">
<?php if($this->flexi_auth->is_privileged(array('Accounts View', 'Accounts Add', 'Accounts Edit', 'Accounts De'))) {?>
            					<li><a href="/backend/accounts/account"><i class="fa fa-user"></i> 帳號管理</a></li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Groups View', 'Groups Add', 'Groups Edit', 'Groups Del'))) {?>
            					<li><a href="/backend/groups/group"><i class="fa fa-group"></i> 群組管理</a></li>
<?php }?>
<?php if($this->flexi_auth->is_privileged(array('Privileges View', 'Privileges Add', 'Privileges Edit', 'Privileges Del'))) {?>
								<li><a href="/backend/privileges/privilege"><i class="fa fa-eye"></i> 權限管理</a></li>
								<li><a href="/backend/privileges/account"><i class="fa fa-eye"></i> 帳號權限</a></li>
            					<li><a href="/backend/privileges/group"><i class="fa fa-eye"></i> 群組權限</a></li>
<?php }?>
								<li><a href="/backend/systems/log"><i class="fa fa-file-text-o"></i> 系統記錄</a></li>
            				</ul>
            			</li>
<?php }?>
            		</ul>
            	</section>
            </aside>
