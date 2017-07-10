                    <div class="row">
                        <div class="box box-primary">
                            <div class="box-body box-profile">
                                <img class="profile-user-img img-responsive img-circle" src="<?php echo $user['user_img'];?>" alt="User profile picture">
                                <h3 class="profile-username text-center">Vidol - <?php echo $user['group'];?></h3>
                                <p class="text-muted text-center"><?php echo $user['username'];?></p>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>信箱</b> <a class="pull-right"><?php echo $user['email'];?></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>密碼修改完成</b>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- page js -->
                    <script src="/assets/js/custom/homes/personal.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace"></div>
