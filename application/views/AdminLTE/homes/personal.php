                    <div class="row">
                    	<?php echo validation_errors(); ?>
                        <div class="box box-primary">
                        <?php echo form_open('/backend/homes/personal'); ?>
                            <div class="box-body box-profile">
                                <img class="profile-user-img img-responsive img-circle" src="<?php echo $user['user_img'];?>" alt="User profile picture">
                                <h3 class="profile-username text-center">Vidol - <?php echo $user['group'];?></h3>
                                <p class="text-muted text-center"><?php echo $user['username'];?></p>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>信箱</b> <a class="pull-right"><?php echo $user['email'];?></a>
                                    </li>
                                </ul>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>舊密碼</b>
                                        <input type="password" name="old_password" value="" size="50" />
                                    </li>
                                </ul>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>新密碼</b>
                                        <input type="password" name="new_password" value="" size="50" />
                                    </li>
                                </ul>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>確認密碼</b>
                                        <input type="password" name="confirm_password" value="" size="50" />
                                    </li>
                                </ul>
                                <input type="submit" value="修改密碼" />
                            </div>
                        <?php echo form_close(); ?>
                        </div>
                    </div>
                    <!-- page js -->
                    <script src="/assets/js/custom/homes/personal.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace"></div>
