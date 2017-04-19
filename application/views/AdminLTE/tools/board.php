                    <div class="box box-info">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>原本Video Type</label>
                                        <select name="video_type" class="form-control">
                                            <option value="episode">episode</option>
                                            <option value="channel">channel</option>
                                            <option value="live" selected>live</option>
                                            <option value="event">event</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-8">
                                        <label>原本Video Type NO</label>
										<input type="text" name="video_no" class="form-control" placeholder="輸入影片編號...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label>修改後Video Type</label>
                                        <select name="update_video_type" class="form-control">
                                            <option value="episode" selected>episode</option>
                                            <option value="channel">channel</option>
                                            <option value="live">live</option>
                                            <option value="event">event</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-8">
                                        <label>修改後Video Type NO</label>
										<input type="text" name="update_video_no" class="form-control" placeholder="輸入影片編號...">
                                    </div>
                                </div>
								<div class="box-footer">
									<button type="submit" class="send_btn btn btn-primary">Submit</button>
								</div>
                            </div>
                        </div>
                    </div>
                    <!-- page js -->
                    <script src="/assets/js/custom/tools/board.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace">
                    </div>
