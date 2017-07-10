                    <div class="row">
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
                                    <li class="list-group-item">
                                        <b>舊密碼</b>
                                        <input type="password" name="old_password" value="" class="pull-right" size="50" />
                                        <?php echo form_error('old_password'); ?>
                                    </li>
                                    <li class="list-group-item">
                                        <b>新密碼</b>
                                        <input type="password" name="new_password" value="" class="pull-right" size="50" />
                                        <?php echo form_error('new_password'); ?>
                                    </li>
                                </ul>
                                <input type="submit" value="修改密碼" class="btn-primary btn-block" />
                                <?php echo $title;?>
                            </div>
                        <?php echo form_close(); ?>
                        </div>
                    </div>
                    <!-- page js -->
                    <script src="/assets/js/custom/homes/personal.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace"></div>
